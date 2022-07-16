Title: Tips For FFMPEG
Date: 2016-02-21 21:30
Modified: 2016-12-01 21:10
Category: Linux
Tags: ffmpeg, linux, video
Slug: tips-for-ffmpeg
Authors: Romain Pellerin
Summary: A few tips with FFMPEG

`-acodec` is an alias for `-c:a` which is an alias for `-codec:a` (audio stream).  
`-vcodec` is an alias for `-c:v` which is an alias for `-codec:v` (video stream).

`-af` is an alias for `-filter:a` (audio stream).  
`-vf` is an alias for `-filter:v` (video stream).

# Cross fade a video for seamless loops

    :::bash
    curl -o ./video-crossfade -O https://raw.githubusercontent.com/joeyhoer/video-crossfade/master/video-crossfade.sh
    chmod +x ./video-crossfade
    ./video-crossfade.sh -f 2 -o output.mp4 input.mp4 # 2 second crossfade

# Scale a 2.7K (2704x1520) video down to 1080p

    :::bash
    ffmpeg -i input.mp4 -vf "scale=1920:1080,setsar=1" -acodec copy output.mp4

# Convert a video framerate to NTSC (29.97002997)

    :::bash
    ffmpeg -i input.mp4 -r ntsc -acodec copy output.mp4

# Split a video in several chunks of same duration

    :::bash
    ffmpeg -i input.mp4 -c copy -map 0 -segment_time 00:00:30 -f segment -reset_timestamps 1 output%03d.mp4

# Add embedded subtitles

    :::bash
    sudo apt install subtitlecomposer

In subtitlecomposer, import the video, add subtitles, and export the file as `filename.ass`. Edit the exported text file, change the font from 16px to 22. Then:

    :::bash
    ffmpeg -i input.mp4 -vf ass=filename.ass output.mp4

# Create a video out of several images

    :::bash
    ffmpeg -r 24 -f image2 -s 1440x1080 -i image%04d.jpg -vcodec libx264 -crf 25 -pix_fmt yuv420p output.mp4 # image0001.jpg, image0002.jpg, etc

# Create a GIF from a video

    :::bash
    palette="/tmp/palette.png"

    filters="fps=15,scale=320:-1:flags=lanczos"

    ffmpeg -v warning -i input.mp4 -vf "$filters,palettegen" -y $palette
    ffmpeg -v warning -i input.mp4 -i $palette -lavfi \
        "$filters [x]; [x][1:v] paletteuse" -y output.gif

# Create a GIF from images (01.jpg, 02.jpg, 03.jpg, etc...)

    :::bash
    ffmpeg -f image2 -framerate 1 -i %02d.jpg output.gif

# Crop an OGV video

    :::bash
    ffmpeg -i input.ogv -vcodec libtheora -vf crop=700:400:0:0 -f ogg output.ogv

# Merge an audio track (delayed) with a video, and cut the final video according to the shortest stream

    :::bash
    ffmpeg -itsoffset 00:00:15 -i input.mp4 -i audio.mp3 -acodec copy -vcodec copy \
        -map 0:0 -map 1:0 -shortest output.mp4

# Cut a video (set the starting time and the duration)

Two options mostly.

1. Process all the input and then precisely cut the re-encoded output at the requested time, the rest of the input that came out before is discarded. It is very slow because it has to process the beginning of the input even though it is discarded.

        :::bash
        ffmpeg -i input.mp4 -ss 00:00:13 -t 00:09:00 output.mp4

2. Seek in input (fast but imprecise, [can only cut at key frames](https://www.quora.com/What-is-the-difference-between-an-I-Frame-and-a-Keyframe-in-video-encoding)) and do not re-encode to preserve quality:

        :::bash
        ffmpeg -ss 00:00:13 -i input.mp4 -t 00:09:00 -c copy -avoid_negative_ts make_zero output.mp4

[More information](https://trac.ffmpeg.org/wiki/Seeking).

# Extract EVERY image from a video

    :::bash
    ffmpeg -i foo.avi -r 1 -s WxH -f image2 foo-%03d.jpeg

# [Create a 24fps video from many images](http://trac.ffmpeg.org/wiki/Create%20a%20video%20slideshow%20from%20images)

    :::bash
    ffmpeg -framerate 1 -i foo-%02d.jpg -c:v libx264 -r 24 -pix_fmt yuv420p foo.mp4

# Accelerate a video by 2

    :::bash
    ffmpeg -i input.mp4 -filter:v "setpts=0.5*PTS" output.mp4

# Keep the original metadata of a MP4 file

    :::bash
    ffmpeg -i original_video.mp4 -c:v libx264 -c:a copy -map_metadata 0 transcoded_video.mp4
    touch -r original_video.mp4 transcoded_video.mp4

# Flip by 180° a video and fix the metadata

    :::bash
    ffmpeg -i input.mp4 -vf "transpose=2,transpose=2" -metadata:s:v:0 rotate=0 output.mp4

# Remove the audio stream

    :::bash
    ffmpeg -i input.mp4 -an -vcodec copy output.mp4

# Convert from .mov to .mp4

    :::bash
    ffmpeg -i input.mov -c:v libx264 -c:a copy output.mp4

# [Concatenate several MP4 files](https://trac.ffmpeg.org/wiki/Concatenate)

    :::bash
    find . -type f -iname "*.MP4" -exec ffmpeg -i '{}' -c copy -bsf:v h264_mp4toannexb -f mpegts '{}.ts' \;
    ffmpeg -i "concat:$(ls *.ts | tr '\n' '|' | head -c -1)" -c copy -bsf:a aac_adtstoasc output.mp4

# Fade out last 2 seconds of audio track

    :::bash
    ffmpeg -i output-with-sound.mp4 -c:v copy -filter_complex "areverse, afade=d=2, areverse" output-with-sound-fadeout.mp4

# Merge two videos placed side by side with audio from first video

    :::bash
    ffmpeg -i left.mp4 -i right.mp4 \
      -filter_complex "[0:v][1:v]hstack=inputs=2[myvideo]" \
      -map "[myvideo]" -map "0:a" wide.mp4

You might want to scale it down and add black padding at the top and bottom:

    :::bash
    ffmpeg -i left.mp4 -i right.mp4 \
      -filter_complex "[0:v][1:v]hstack=inputs=2[myvideo];[myvideo]scale=1920:-1,pad=1920:1080:0:270[myvideo]" \
      -map "[myvideo]" -map "0:a" wide.mp4
    :::bash

Alternatively, you can crop the initial two inputs and directly produce a 1920x1080 video:

    :::bash
    ffmpeg -i left.mp4 -i right.mp4 \
      -filter_complex "[0:v]crop=960:1080:480:0[left];[1:v]crop=960:1080:480:0[right];[left][right]hstack=inputs=2[myvideo]" \
      -map "[myvideo]" -map "0:a" 16-9-video.mp4

If one of the two videos is not correctly synced with the other one, you can delay an input. Add `-ss 00:00:0x` before the `-i input` that needs adjustment.

# Put one video on top of another one (overlay), alternate between the two, and use audio from first video

    :::bash
    ffmpeg -i initial.mp4 -i ontop.mp4 -filter_complex \
      "[0:0][1:0]overlay=enable='between(t\,19,28)'[myvideo];[myvideo][1:0]overlay=enable='between(t\,37,45)'[myvideo]" \
      -map "[myvideo]" -map "0:a" -shortest output.mp4

# Replace the audio track with another

    :::bash
    ffmpeg -i output.mp4 -i yo.mp3 -map 0:0 -map 1 -vcodec copy -acodec copy output2.mp4

# [(Down) Scaling a video from 1080p to 720p](https://trac.ffmpeg.org/wiki/Scaling%20(resizing)%20with%20ffmpeg)

    :::bash
    ffmpeg -i input.mp4 -vf scale=1280:-1 -acodec copy output.mp4

# Concat MTS files to MP4

    :::bash
    ffmpeg -i "concat:$(ls *.MTS | tr '\n' '|' | head -c -1)" -vcodec copy -acodec aac -ab 512k -cutoff 22050 -sn output.mp4

# [Reduce the size of a video](https://unix.stackexchange.com/a/337359/194594)

    :::bash
    ffmpeg -i input.mp4 -vcodec libx265 -crf 24 -r 25 output.mp4
    # OR if libx265 is not supported
    ffmpeg -i input.mp4 -vcodec libx264 -crf 24 -r 25 output.mp4

# Resources

- [FFmpeg tips](https://ehret.me/ffmpeg-tips/)
- [jumpcutter](https://github.com/carykh/jumpcutter)
