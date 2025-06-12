import gpxpy
import gpxpy.gpx
import random
import sys
import os
from geopy.distance import geodesic

# - trims randomly the start and end of a GPX track (disable with --no-trim)
# - removes all timestamps from the GPX file
# - simplifies the track by removing points that are closer than a specified distance
# - strips all elevation data from the GPX file
# Usage: python trim_gpx.py file.gpx [--no-trim]
# Requirements: python3 -m pip install gpxpy geopy

def simplify_track(gpx, min_distance_meters):
    """
    Simplifies all tracks in the GPX object by removing points that are
    closer than a specified minimum distance to the previous point.

    Args:
        gpx (gpxpy.gpx.GPX): The GPX object to simplify.
        min_distance_meters (float): The minimum distance between points.
                                     Points closer than this will be removed.
    """
    for track in gpx.tracks:
        for segment in track.segments:
            if len(segment.points) < 2:
                continue

            simplified_points = [segment.points[0]]  # Always keep the first point
            last_kept_point = segment.points[0]

            for i in range(1, len(segment.points)):
                current_point = segment.points[i]
                dist = geodesic(
                    (last_kept_point.latitude, last_kept_point.longitude),
                    (current_point.latitude, current_point.longitude)
                ).meters

                if dist >= min_distance_meters:
                    simplified_points.append(current_point)
                    last_kept_point = current_point

            # Always keep the last point to preserve the track's end
            if len(segment.points) > 1 and segment.points[-1] not in simplified_points:
                simplified_points.append(segment.points[-1])

            print(f"Simplified segment from {len(segment.points)} to {len(simplified_points)} points.")
            segment.points = simplified_points


def strip_elevation(gpx):
    """
    Removes all elevation data from the GPX object.

    Args:
        gpx (gpxpy.gpx.GPX): The GPX object to modify.
    """
    for track in gpx.tracks:
        for segment in track.segments:
            for point in segment.points:
                point.elevation = None
    for waypoint in gpx.waypoints:
        waypoint.elevation = None
    for route in gpx.routes:
        for point in route.points:
            point.elevation = None

def process_gpx(input_file, output_file, start_trim_meters, end_trim_meters,
                do_simplify, min_simplify_dist, do_strip_elevation):
    """
    Processes a GPX file by trimming, simplifying, and stripping data.

    Args:
        input_file (str): The path to the input GPX file.
        output_file (str): The path to save the processed GPX file.
        start_trim_meters (float): The distance to trim from the beginning.
        end_trim_meters (float): The distance to trim from the end.
        do_simplify (bool): Whether to perform track simplification.
        min_simplify_dist (float): Minimum distance for simplification.
        do_strip_elevation (bool): Whether to remove elevation data.
    """
    try:
        with open(input_file, 'r') as gpx_file:
            gpx = gpxpy.parse(gpx_file)
    except FileNotFoundError:
        print(f"Error: Input file not found at '{input_file}'")
        sys.exit(1)
    except Exception as e:
        print(f"An error occurred while parsing the GPX file: {e}")
        sys.exit(1)

    # --- Feature: Strip elevation data ---
    if do_strip_elevation:
        strip_elevation(gpx)
        print(" - Stripped all elevation data.")

    # --- Feature: Simplify track by removing superfluous points ---
    if do_simplify:
        simplify_track(gpx, min_simplify_dist)
        print(f" - Simplified track with a minimum distance of {min_simplify_dist} meters.")

    # --- Original Feature: Remove all timestamp information ---
    gpx.time = None
    if gpx.metadata_extensions:
        gpx.metadata_extensions = []
    for track in gpx.tracks:
        track.extensions = []
        for segment in track.segments:
            segment.extensions = []
            for point in segment.points:
                point.time = None
                point.extensions = []
    for waypoint in gpx.waypoints:
        waypoint.time = None
    for route in gpx.routes:
        for point in route.points:
            point.time = None
    print(" - Removed all timestamps.")
    # -----------------------------------------------------------

    # --- Original Feature: Trim start and end of the track ---
    # This block is skipped if start_trim_meters and end_trim_meters are 0
    if start_trim_meters > 0 or end_trim_meters > 0:
        for track in gpx.tracks:
            for segment in track.segments:
                # Skip segments that are too short to process
                if len(segment.points) < 2:
                    continue

                points = segment.points
                total_distance = 0
                distances = [0]

                # Calculate cumulative distance along the segment
                for i in range(1, len(points)):
                    start_point = points[i-1]
                    end_point = points[i]
                    dist = geodesic((start_point.latitude, start_point.longitude),
                                    (end_point.latitude, end_point.longitude)).meters
                    total_distance += dist
                    distances.append(total_distance)

                # Find the start index for trimming
                start_index = 0
                if start_trim_meters > 0:
                    for i, dist in enumerate(distances):
                        if dist >= start_trim_meters:
                            start_index = i
                            break

                # Find the end index for trimming
                end_index = len(points)
                if end_trim_meters > 0:
                    for i in range(len(distances) - 1, -1, -1):
                        if (total_distance - distances[i]) >= end_trim_meters:
                            end_index = i + 1
                            break

                # If the track is shorter than the trim distances, the segment could become empty
                if start_index >= end_index:
                    print(f"Warning: A track segment is shorter than the trim distance. The resulting segment will be empty.")
                    segment.points = []
                else:
                    # Trim the points from the segment
                    segment.points = points[start_index:end_index]
    # -----------------------------------------------------------

    with open(output_file, 'w') as gpx_file:
        # Use 'gpx_1_1' to ensure compatibility and avoid issues with missing time/elevation
        gpx_file.write(gpx.to_xml(version='1.1'))

if __name__ == '__main__':
    # --- Check for command-line arguments ---
    if len(sys.argv) < 2 or len(sys.argv) > 3:
        print(f"Usage: python {sys.argv[0]} <your_gpx_file.gpx> [--no-trim]")
        sys.exit(1)

    input_gpx_file = sys.argv[1]
    no_trim_flag = False
    if len(sys.argv) == 3:
        if sys.argv[2] == '--no-trim':
            no_trim_flag = True
        else:
            print(f"Error: Unknown argument '{sys.argv[2]}'. Did you mean '--no-trim'?")
            sys.exit(1)

    # --- Configuration ---
    # Automatically generate the output filename
    file_name_part, file_extension = os.path.splitext(input_gpx_file)
    output_gpx_file = f"{file_name_part}_processed{file_extension}"

    # Set trim distances to 0 if the --no-trim flag is used
    trim_start_distance_meters = 0
    trim_end_distance_meters = 0
    if not no_trim_flag:
        trim_start_distance_meters = random.randint(500, 1000)
        trim_end_distance_meters = random.randint(500, 1000)

    # --- Configuration for other features ---
    STRIP_ELEVATION = True
    SIMPLIFY_TRACK = True
    MIN_SIMPLIFICATION_DISTANCE_METERS = 15.0
    # -------------------------------------------

    print(f"Processing '{input_gpx_file}'...")

    process_gpx(input_gpx_file, output_gpx_file,
                trim_start_distance_meters, trim_end_distance_meters,
                SIMPLIFY_TRACK, MIN_SIMPLIFICATION_DISTANCE_METERS, STRIP_ELEVATION)

    print("\nProcessing complete!")
    print(f"Successfully processed '{input_gpx_file}' and saved to '{output_gpx_file}'")
    if not no_trim_flag:
        print(f" - Trimmed start by: {trim_start_distance_meters} meters")
        print(f" - Trimmed end by:   {trim_end_distance_meters} meters")
    else:
        print(" - Trimming was skipped (--no-trim).")
