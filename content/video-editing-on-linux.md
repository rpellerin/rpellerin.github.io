Title: Video Editing on Linux
Date: 2018-05-13 17:30
Category: Linux
Tags: video, linux
Slug: video-editing-on-linux
Authors: Romain Pellerin
Summary: How to edit videos on Linux

This is a reminder for myself. I thought it could be useful to other people too, that's why I put it online.

1. Record in 1080p, at 60 FPS, unless you want to go fancy and do 4k.
2. Download [Kdenlive](https://kdenlive.org/en/download/).
3. Using VLC, `Tools` > `Codec information`, check the framerate. All videos must have the same frame rate otherwise you might encounter issues.
4. When creating a new project in Kdenlive, make sure to use the very same frame rate and frame size. This can be changed later in `Project` > `Project Settings` but **it is not advised as it will shift clips randomly** (I experienced it). There, also enable `Proxy clips` for videos larger than 1000 pixels.

That's about it!