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

# Create a GIF

    :::bash
    palette="/tmp/palette.png"

    filters="fps=15,scale=320:-1:flags=lanczos"

    ffmpeg -v warning -i input.mp4 -vf "$filters,palettegen" -y $palette
    ffmpeg -v warning -i input.mp4 -i $palette -lavfi \
        "$filters [x]; [x][1:v] paletteuse" -y output.gif

# Crop an OGV video

    :::bash
    ffmpeg -i input.ogv -vcodec libtheora -vf crop=700:400:0:0 -f ogg output.ogv

# Merge an audio track (delayed) with a video, and cut the final video according to the shortest stream

    :::bash
    ffmpeg -itsoffset 00:00:15 -i input.mp4 -i audio.mp3 -acodec copy -vcodec copy \
        -map 0:0 -map 1:0 -shortest output.mp4

# Cut a video (set the starting time and the duration)

    :::bash
    ffmpeg -i input.mp4 -ss 00:00:13 -t 00:09:00 -c copy output.mp4

In case you have a few seconds of blank video at the beginning, it is due to key-frames. Here is a command, way less accurate, but that will fix it:

    :::bash
    ffmpeg -ss 00:00:13 -i input.mp4 -t 00:09:00 -c copy -avoid_negative_ts 1 output.mp4

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
    ffmpeg -i input1.mp4 -c copy -bsf:v h264_mp4toannexb -f mpegts intermediate1.ts
    ffmpeg -i input2.mp4 -c copy -bsf:v h264_mp4toannexb -f mpegts intermediate2.ts
    ffmpeg -i "concat:intermediate1.ts|intermediate2.ts" -c copy -bsf:a aac_adtstoasc output.mp4

# Replace the audio track with another

    :::bash
    ffmpeg -i output.mp4 -i yo.mp3 -map 0:0 -map 1 -vcodec copy -acodec copy output2.mp4

# [(Down) Scaling a video from 1080p to 720p](https://trac.ffmpeg.org/wiki/Scaling%20(resizing)%20with%20ffmpeg)

    :::bash
    ffmpeg -i input.mp4 -vf scale=1280:-1 -acodec copy output.mp4

