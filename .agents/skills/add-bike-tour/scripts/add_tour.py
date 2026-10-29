#!/usr/bin/env python3
"""
Helper script to add a bike tour to content/extra/bike-tours.
Handles:
1. Discovering existing locations and creating new ones.
2. Fetching Komoot ride title, cover photo, map preview, and elevation gain.
3. Processing/trimming GPX using trim_gpx.py.
4. Saving assets and updating the corresponding location JSON manifest.
"""

import argparse
import glob
import html
import json
import os
import re
import subprocess
import sys
import unicodedata
import urllib.request
from pathlib import Path

# Base paths
REPO_ROOT = Path(__file__).resolve().parents[4]
BIKE_TOURS_DIR = REPO_ROOT / "content" / "extra" / "bike-tours"
INDEX_HTML = BIKE_TOURS_DIR / "index.html"
TRIM_GPX_SCRIPT = REPO_ROOT / "trim_gpx.py"
ENV_PYTHON = REPO_ROOT / ".env" / "bin" / "python"

USER_AGENT = (
    "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 "
    "(KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
)


def get_existing_locations():
    """Returns a list of dicts: [{'slug': 'berlin', 'name': 'Berlin', 'count': 31}, ...]"""
    locations = []
    for json_file in sorted(BIKE_TOURS_DIR.glob("*_rides.json")):
        slug = json_file.stem.replace("_rides", "")
        count = 0
        try:
            with open(json_file, "r", encoding="utf-8") as f:
                data = json.load(f)
                count = len(data)
        except Exception:
            pass

        # Try to find display name in index.html
        display_name = slug.capitalize()
        if INDEX_HTML.exists():
            html_text = INDEX_HTML.read_text(encoding="utf-8")
            match = re.search(
                rf'value="{re.escape(slug)}".*?<div class="w-full text-base font-semibold">(.*?)</div>',
                html_text,
                re.DOTALL,
            )
            if match:
                display_name = match.group(1).strip()

        locations.append({"slug": slug, "name": display_name, "count": count})
    return locations


def add_location_to_index_html(slug, display_name):
    """Adds a new radio button for the location in content/extra/bike-tours/index.html."""
    if not INDEX_HTML.exists():
        return

    content = INDEX_HTML.read_text(encoding="utf-8")
    if f'value="{slug}"' in content:
        return  # Already present

    # Find the region selector container
    selector_pattern = r'(<!-- Region Selector -->\s*<div[^>]*>)(.*?)(</div>\s*<!-- Map Bounds Filter -->)'
    match = re.search(selector_pattern, content, re.DOTALL)
    if not match:
        print(f"Warning: Could not automatically locate Region Selector in {INDEX_HTML}")
        return

    prefix, inner_html, suffix = match.groups()

    new_button = f"""          <div>
            <input
              type="radio"
              id="{slug}"
              name="region"
              value="{slug}"
              class="hidden peer"
            />
            <label
              for="{slug}"
              class="inline-flex items-center justify-between w-full px-4 py-2 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100"
            >
              <div class="block">
                <div class="w-full text-base font-semibold">{display_name}</div>
              </div>
            </label>
          </div>\n"""

    new_inner_html = inner_html.rstrip() + "\n" + new_button
    updated_content = content[: match.start()] + prefix + new_inner_html + suffix + content[match.end() :]
    INDEX_HTML.write_text(updated_content, encoding="utf-8")
    print(f"Added '{display_name}' ({slug}) radio button to {INDEX_HTML}")


def slugify_title(title):
    """
    Converts a ride title into a clean filename slug:
    - Lowercase
    - Accents replaced with ASCII counterparts
    - Spaces replaced with underscores
    - Characters outside a-zA-Z0-9_- removed
    - Consecutive underscores collapsed and leading/trailing underscores stripped
    """
    s = unicodedata.normalize("NFKD", title)
    s = "".join([c for c in s if not unicodedata.combining(c)])
    s = s.lower()
    s = s.replace(" ", "_")
    s = re.sub(r"[^a-z0-9_-]", "", s)
    s = re.sub(r"_+", "_", s)
    s = s.strip("_")
    return s


def fetch_komoot_metadata(url):
    """Fetches the Komoot webpage and extracts title and image URLs."""
    # Sanitize URL: extract canonical tour URL
    m = re.search(r"(https?://(?:www\.)?komoot\.[a-z.]+/tour/\d+)", url)
    canonical_url = m.group(1) if m else url.split("?")[0].split("#")[0]

    req = urllib.request.Request(canonical_url, headers={"User-Agent": USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=15) as resp:
            page_content = resp.read().decode("utf-8")
    except Exception as e:
        raise RuntimeError(f"Failed to fetch Komoot page at {canonical_url}: {e}")

    # Extract title
    title = None
    og_title_m = re.search(r'<meta\s+property=["\']og:title["\']\s+content=["\']([^"\']+)["\']', page_content)
    if og_title_m:
        raw_title = og_title_m.group(1)
        # Typically formatted as "Title | road ride | Komoot"
        title = raw_title.split(" | ")[0].strip()
    else:
        title_tag_m = re.search(r"<title>(.*?)</title>", page_content)
        if title_tag_m:
            raw_title = title_tag_m.group(1)
            title = raw_title.split(" | ")[0].strip()

    if title:
        title = html.unescape(title)
    else:
        title = "Bike Tour"

    # Extract images
    og_images = re.findall(r'<meta\s+property=["\']og:image["\']\s+content=["\']([^"\']+)["\']', page_content)
    og_images = [html.unescape(img) for img in og_images]

    main_photo_url = None
    map_photo_url = None

    for img in og_images:
        if "tourpic-vector" in img or "maps.komoot.net" in img:
            if not map_photo_url:
                map_photo_url = img
        else:
            if not main_photo_url:
                main_photo_url = img

    if not main_photo_url and og_images:
        main_photo_url = og_images[0]

    # Fetch elevation gain from API
    elevation_up = None
    tour_id_m = re.search(r"/tour/(\d+)", canonical_url)
    if tour_id_m:
        api_url = f"https://www.komoot.com/api/v007/tours/{tour_id_m.group(1)}"
        api_req = urllib.request.Request(api_url, headers={"User-Agent": USER_AGENT})
        try:
            with urllib.request.urlopen(api_req, timeout=10) as api_resp:
                tour_data = json.loads(api_resp.read().decode("utf-8"))
                raw_ele = tour_data.get("elevation_up")
                if raw_ele is not None:
                    elevation_up = int(round(raw_ele / 10.0) * 10)
        except Exception as e:
            print(f"Warning: Failed to fetch elevation from Komoot API: {e}")

    return {
        "canonical_url": canonical_url,
        "title": title,
        "main_photo_url": main_photo_url,
        "map_photo_url": map_photo_url,
        "elevation_up": elevation_up,
    }


def download_file(url, target_path):
    """Downloads a file from url and saves it to target_path."""
    req = urllib.request.Request(url, headers={"User-Agent": USER_AGENT})
    with urllib.request.urlopen(req, timeout=15) as resp:
        data = resp.read()
    target_path.parent.mkdir(parents=True, exist_ok=True)
    with open(target_path, "wb") as f:
        f.write(data)


def process_gpx_file(input_gpx_path, should_trim):
    """Runs trim_gpx.py on the input file, returning the path to the processed GPX."""
    input_path = Path(input_gpx_path).resolve()
    if not input_path.exists():
        raise FileNotFoundError(f"GPX file not found: {input_path}")

    python_bin = ENV_PYTHON if ENV_PYTHON.exists() else Path(sys.executable)
    cmd = [str(python_bin), str(TRIM_GPX_SCRIPT), str(input_path)]
    if not should_trim:
        cmd.append("--no-trim")

    print(f"Executing: {' '.join(cmd)}")
    result = subprocess.run(cmd, capture_output=True, text=True)
    if result.returncode != 0:
        print("trim_gpx.py output:\n", result.stdout)
        print("trim_gpx.py error:\n", result.stderr)
        raise RuntimeError(f"trim_gpx.py failed with code {result.returncode}")

    processed_path = input_path.with_name(f"{input_path.stem}_processed{input_path.suffix}")
    if not processed_path.exists():
        raise FileNotFoundError(f"Expected processed file {processed_path} does not exist.")

    return processed_path


def add_tour(location_slug, location_name, komoot_url, gpx_path, should_trim, title_override=None):
    """Main workflow to add a tour."""
    slug = location_slug.lower().strip()
    loc_dir = BIKE_TOURS_DIR / slug
    json_path = BIKE_TOURS_DIR / f"{slug}_rides.json"

    # Ensure location directory exists
    loc_dir.mkdir(parents=True, exist_ok=True)

    # Initialize json file if it doesn't exist
    if not json_path.exists():
        json_path.write_text("[]\n", encoding="utf-8")
        if location_name:
            add_location_to_index_html(slug, location_name)

    # 1. Fetch Komoot metadata
    print(f"Fetching Komoot metadata from {komoot_url}...")
    metadata = fetch_komoot_metadata(komoot_url)
    title = title_override or metadata["title"]
    print(f"Tour Title: {title}")
    print(f"Canonical URL: {metadata['canonical_url']}")
    if metadata.get("elevation_up") is not None:
        print(f"Elevation Gain: {metadata['elevation_up']}m")

    # 2. Determine file slug
    file_slug = slugify_title(title)
    if not file_slug:
        file_slug = "bike_tour"

    # Avoid collision if a file with the same slug already exists
    base_slug = file_slug
    counter = 2
    while (loc_dir / f"{file_slug}.gpx").exists():
        file_slug = f"{base_slug}_{counter}"
        counter += 1
    print(f"Assigned File Slug: {file_slug} (location: {slug})")

    # 3. Process GPX
    print(f"Processing GPX file: {gpx_path} (Trim: {should_trim})...")
    processed_gpx = process_gpx_file(gpx_path, should_trim)

    # 4. Copy GPX to target destination
    target_gpx = loc_dir / f"{file_slug}.gpx"
    target_gpx.write_bytes(processed_gpx.read_bytes())
    print(f"Saved GPX to: {target_gpx}")

    # 5. Download photos
    if metadata["main_photo_url"]:
        target_photo = loc_dir / f"{file_slug}.jpg"
        print(f"Downloading main photo from {metadata['main_photo_url']}...")
        download_file(metadata["main_photo_url"], target_photo)
        print(f"Saved photo to: {target_photo}")
    else:
        print("Warning: No main photo found on Komoot.")

    if metadata["map_photo_url"]:
        target_map = loc_dir / f"{file_slug}_map.jpg"
        print(f"Downloading map preview from {metadata['map_photo_url']}...")
        try:
            download_file(metadata["map_photo_url"], target_map)
            print(f"Saved map preview to: {target_map}")
        except Exception as e:
            print(f"Warning: Failed to download map preview: {e}")

    # 6. Update JSON manifest
    rides = []
    if json_path.exists():
        try:
            with open(json_path, "r", encoding="utf-8") as f:
                rides = json.load(f)
        except Exception:
            rides = []

    new_entry = [
        f"{file_slug}.gpx",
        title,
        metadata["canonical_url"],
    ]
    if metadata.get("elevation_up") is not None:
        new_entry.append(metadata["elevation_up"])
    rides.append(new_entry)

    with open(json_path, "w", encoding="utf-8") as f:
        json.dump(rides, f, indent=2, ensure_ascii=False)
        f.write("\n")

    print(f"Updated {json_path} successfully!")
    print(f"\nAll set! Added '{title}' to {slug} as '{file_slug}.gpx'.")


def main():
    parser = argparse.ArgumentParser(description="Add a new bike tour to content/extra/bike-tours")
    parser.add_argument("--list-locations", action="store_true", help="List existing locations")
    parser.add_argument("--location", help="Location slug (e.g. berlin, allgaeu, mallorca, or a new location)")
    parser.add_argument("--location-name", help="Display name for a new location (e.g. 'French Riviera')")
    parser.add_argument("--komoot-url", help="URL of the Komoot tour")
    parser.add_argument("--gpx-path", help="Path to the original GPX file")
    parser.add_argument("--trim", action="store_true", default=None, help="Trim the start and end of the GPX")
    parser.add_argument("--no-trim", dest="trim", action="store_false", help="Do not trim start and end")
    parser.add_argument("--title", help="Optional override for the tour title")

    args = parser.parse_args()

    if args.list_locations:
        locs = get_existing_locations()
        print("Existing locations:")
        for loc in locs:
            print(f"  - {loc['name']} (slug: {loc['slug']}, {loc['count']} rides)")
        return

    if not args.location or not args.komoot_url or not args.gpx_path or args.trim is None:
        parser.print_help()
        sys.exit(1)

    add_tour(
        location_slug=args.location,
        location_name=args.location_name,
        komoot_url=args.komoot_url,
        gpx_path=args.gpx_path,
        should_trim=args.trim,
        title_override=args.title,
    )


if __name__ == "__main__":
    main()
