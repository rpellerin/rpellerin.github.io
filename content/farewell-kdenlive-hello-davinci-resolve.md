Title: Farewell Kdenlive, Hello Davinci Resolve
Date: 2024-09-18 20:00
Category: Computers
Tags: kdenlive, davinci resolve, video
Slug: farewell-kdenlive-hello-davinci-resolve
Authors: Romain Pellerin
Summary: I'm switching from Kdenlive to Davinci Resolve, here's why and a few tips

I've been [a long time fan of Kdenlive]({filename}/video-editing-on-linux.md), but now has come a time for change. Kdenlive is great but it's got limitations. Transitions are pretty basic, for example. Also, 10-Bit and/or HDR is not (fully) supported. Overall, the program is not as polished as a professional-grade one. That's why I decided to change and find a better video editing application. And I've settled for Davinci Resolve, the free version (more than enough for my needs).

Before starting any new project, make sure that all cameras have similar settings: resolution, framerate, and more importantly, HDR or not! (pick Rec.2020 for HDR, and Rec.709 for SDR). [You cannot easily mix SDR and HDR footage in the same project.](https://www.reddit.com/r/VideoEditing/comments/jqqixz/hdr_and_sdr_mixed_into_same_project/)

Up until now, I've not been able to properly "downgrade" a HDR footage into a SDR project in Davinci Resolve. But I've been sort of successful with the opposite (importing SDR footage into a HDR project and using the "Color Space Transform" effect, from Rec.709 to Rec.2100 HLG). The output colors are different but close enough.

Below you'll find my settings.

# Project settings

- Resolution: 3840 x 2160 (4K)
- FPS: 25
- In `Color Management`:

      - `Color science`: `DaVinci YRGB Color Managed`
      - Untick `Automatic color management`
      - `Color processing mode`: `HDR DaVinci Wide Gamut Intermediate`
      - `Output color space`: `Rec.2100 HLG` (just like the videos shot natively with my Google Pixel)

# Export settings

2024 is also the year I'll be switching my default filming resolution from 1080p to 4k, since apparently [Youtube deteriorates less the quality with 4k videos](https://www.reddit.com/r/videography/comments/xxprwo/best_settings_to_upload_to_youtube_vmaf_analysis/) (the conclusion of the thread is that the best export settings are H265, 4k, 60mbit for 24-30fps or 120mbit for 60fps).

In Davinci Resolve, when selecting MP4 as the format, and H.265 as the code, you get two options below:

- `Use hardware acceleration if available`: that goes without saying, tick it
- `Network Optimization`: [this is the equivalent of `-movflags +faststart` for `ffmpeg`](https://forum.blackmagicdesign.com/viewtopic.php?f=21&t=190400). This does not change the quality of the file, nor its size, and makes playback over a network faster, so you might as well tick it.

Then, export in 4K (3840 x 2160). If the project resolution is lower, the image will be upscaled, which will degrade the quality.

Make sure the output FPS is the same as the project/timeline FPS, that is 25 FPS.

To avoid getting too big of a file, I pick a custom bitrate of 10000 Kb/s for the file I will keep backed up. But for the file uploaded to Youtube, [it is recommended to input a bigger number](https://youtu.be/oOGZ0PfDSxM?t=351). See [Youtube's official recommendations](https://support.google.com/youtube/answer/1722171#zippy=%2Cbitrate).

[Do not tick "Optimize for speed".](https://forum.blackmagicdesign.com/viewtopic.php?p=1080855#p1080855)

`Encoding Profile`: `Main10` (no need to pick 4:2:2 as Google Pixel videos are in 4:2:0).

Leave `Frame reordering` ticked, as well as the rest of the settings.

# Some acronyms

- _CST_: Color Space Transform
- _DWG_: DaVinci Wide Gamut
- _ACES_: Academy Color Encoding System (an alternative to DWG)
