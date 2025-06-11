import gpxpy
import gpxpy.gpx
import random
import sys
import os
from geopy.distance import geodesic

# Usage: python trim_gpx.py file.gpx
# Requirements: python3 -m pip install gpxpy geopy

def trim_gpx(input_file, output_file, start_trim_meters, end_trim_meters):
    """
    Trims a specified distance from the start and end of a GPX track
    and removes all timestamp information from the file.

    Args:
        input_file (str): The path to the input GPX file.
        output_file (str): The path to save the processed GPX file.
        start_trim_meters (float): The distance to trim from the beginning.
        end_trim_meters (float): The distance to trim from the end.
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

    # --- Remove all timestamp information ---
    gpx.time = None
    if gpx.metadata_extensions:
        # For simplicity, clear all metadata extensions.
        # A more complex approach could parse and remove only time-related extensions.
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
    # ----------------------------------------

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
            for i, dist in enumerate(distances):
                if dist >= start_trim_meters:
                    start_index = i
                    break

            # Find the end index for trimming
            end_index = len(points)
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

    with open(output_file, 'w') as gpx_file:
        # Use 'gpx_1_1' to ensure compatibility and avoid issues with missing time
        gpx_file.write(gpx.to_xml(version='1.1'))

if __name__ == '__main__':
    # --- Check for command-line argument ---
    if len(sys.argv) != 2:
        # sys.argv[0] is the script name itself
        print(f"Usage: python {sys.argv[0]} <your_gpx_file.gpx>")
        sys.exit(1) # Exit the script if no file is provided

    # --- Configuration ---
    input_gpx_file = sys.argv[1] # Get filename from the first command-line argument

    # Automatically generate the output filename, e.g., "my_run.gpx" -> "my_run_trimmed.gpx"
    file_name_part, file_extension = os.path.splitext(input_gpx_file)
    output_gpx_file = f"{file_name_part}_trimmed{file_extension}"

    # Generate a random integer between 500 and 1000 meters for trimming
    trim_start_distance_meters = random.randint(500, 1000)
    trim_end_distance_meters = random.randint(500, 1000)
    # ---------------------

    trim_gpx(input_gpx_file, output_gpx_file, trim_start_distance_meters, trim_end_distance_meters)
    print(f"Successfully trimmed '{input_gpx_file}' and saved to '{output_gpx_file}'")
    print(f" - Trimmed start by: {trim_start_distance_meters} meters")
    print(f" - Trimmed end by:   {trim_end_distance_meters} meters")
    print(f" - Removed all timestamps.")
