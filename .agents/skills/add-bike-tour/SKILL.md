---
name: add-bike-tour
description: >-
  Add a new bike tour to the bike-tours showcase (content/extra/bike-tours).
  Use this skill whenever the user wants to add, import, or publish a new cycling
  tour/ride to an existing location (Berlin, Mallorca, Allgäu, etc.) or a new location
  using a Komoot URL and a GPX file.
---

# Add Bike Tour Skill

This skill guides the agent through adding a new curated bike tour to the **"A better Komoot"** collection located at [`content/extra/bike-tours`](../../../content/extra/bike-tours).

## Automated Helper Script

A helper script is provided at [`scripts/add_tour.py`](../../../.agents/skills/add-bike-tour/scripts/add_tour.py) to execute the complete workflow. You can inspect existing locations and add new tours using this script.

To list existing locations:
```bash
.agents/skills/add-bike-tour/scripts/add_tour.py --list-locations
```

To add a tour:
```bash
.agents/skills/add-bike-tour/scripts/add_tour.py \
  --location "<location_slug>" \
  [--location-name "<Display Name>"] \
  --komoot-url "<komoot_url>" \
  --gpx-path "<path_to_gpx>" \
  [--trim | --no-trim]
```

---

## Step-by-Step Workflow

When this skill is triggered, execute the following steps in sequence:

### 1. Ask About the Location

1. Check existing locations by running:
   ```bash
   .agents/skills/add-bike-tour/scripts/add_tour.py --list-locations
   ```
2. Present the existing locations to the user (e.g., Berlin, Allgäu, Mallorca) and offer the choice to select an existing one or create a new location.
3. If the user chooses to create a new location:
   - Ask for the display name (e.g., `French Riviera`, `Dolomites`).
   - Derive or ask for the location slug (e.g., `french-riviera`, `dolomites`).

### 2. Ask for the Komoot URL

Ask the user for the Komoot tour URL (e.g., `https://www.komoot.com/tour/123456789`).

### 3. Ask for the GPX File Location

Ask the user for the path to the GPX file on disk. Validate that the file exists before proceeding.

### 4. Ask Whether to Trim the GPX File

Ask the user whether to trim the GPX file using [`trim_gpx.py`](../../../trim_gpx.py):
- **Trim** (`--trim`): Trims a random 500–1000 meters from the start and finish of the track (recommended for privacy to avoid showing exact home/start locations), strips elevation, simplifies points, and removes timestamps.
- **Do not trim** (`--no-trim`): Keeps the complete start and finish intact, while still stripping elevation, simplifying points, and removing timestamps.

### 5. Execute the Addition

Run [`scripts/add_tour.py`](../../../.agents/skills/add-bike-tour/scripts/add_tour.py) with the collected arguments:

```bash
.agents/skills/add-bike-tour/scripts/add_tour.py \
  --location "<slug>" \
  --komoot-url "<url>" \
  --gpx-path "<path_to_gpx>" \
  --trim   # or --no-trim
```
*(If adding a new location, include `--location-name "<Display Name>"` so the radio button in `index.html` is added automatically.)*

The script performs:
1. Fetching the tour title, canonical URL, cover photo, map preview, and total elevation gain (rounded to the nearest 10 meters) from Komoot.
2. Deriving the file slug from the tour title (lowercase, accents converted to ASCII, spaces to underscores, non-alphanumeric/hyphen/underscore removed, consecutive underscores collapsed, leading/trailing underscores stripped).
3. Running [`trim_gpx.py`](../../../trim_gpx.py) (using `.env/bin/python` or system python).
4. Saving the GPX to `content/extra/bike-tours/<slug>/<title_slug>.gpx`.
5. Saving the cover photo to `content/extra/bike-tours/<slug>/<title_slug>.jpg` and map snapshot to `content/extra/bike-tours/<slug>/<title_slug>_map.jpg`.
6. Appending `["<title_slug>.gpx", "<title>", "<canonical_url>", <elevation_gain>]` to `content/extra/bike-tours/<slug>_rides.json`.
7. If a new location was created, creating the folder and adding the radio selector to [`content/extra/bike-tours/index.html`](../../../content/extra/bike-tours/index.html).

### 6. Verify and Report

Verify the changes:
1. Run `git status` or inspect the files created in `content/extra/bike-tours/<slug>/`.
2. Inspect the updated entry in `content/extra/bike-tours/<slug>_rides.json` (including the 4th element with the rounded elevation gain).
3. Provide a clear summary to the user with the tour title, elevation gain, file slug, destination files, and links to the modified files.
