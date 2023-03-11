Title: Video Editing on Linux
Date: 2018-05-13 17:30
Modified: 2023-03-11 16:30
Category: Linux
Tags: video, linux
Slug: video-editing-on-linux
Authors: Romain Pellerin
Summary: How to edit videos on Linux with Kdenlive

When I go on holidays or do sports, I usually film with three different devices:

- My smartphone
- A GoPro
- An Insta360 One X2

Then, I like to make video montages. But because it usually does not happen more than twice a year, I regularly forget how to make a great movie. So this article acts as a reminder for myself. I thought it could be useful to other people too, that's why I put it online.

# Overall process

1. Record in 1080p, at 25FPS, unless you want to go fancy and do 100 FPS (for decent slow-mo) and/or 4k. Why 25 and not 24 or 30? Because 24 is not always 24, sometimes it's actually 23.976, and 30 is sometimes 29.97, depending on the camera you are using. You're never so sure. 30FPS on the GoPro is 29.97, but 30FPS on the Insta360 One X2 gives 30 when exported from the mobile app, 29.97 from the desktop app. While 25 is always 25, on any camera, any software.
1. Download [Kdenlive](https://kdenlive.org/en/download/) for the video montage.
1. Extract regular videos out of 360 videos using the desktop application ([Insta360 STUDIO](https://www.insta360.com/download/insta360-onex2)) from Insta360, not the mobile one, as you get better quality. Export with the following settings:

   - When doing slow-motion: disable "Motion blur"
   - I find the "Optical Flow stitching" better than "Dynamic Stitching", so I always keep this one on
   - "Chromatic Calibration" always on
   - 25FPS when exporting
   - 25Mbps when exporting
   - HEVC (H265) when exporting. Export in H264 only if you intend to add GPS overlays (see bullet point below).

1. You can overlay GPS data (from a Garmin device for instance) on top of a video to show nice stats such as the speed, the altitude, etc. To do so, use [Garmin VIRB Edit](https://www.garmin.com/en-US/p/573412). Note that, at the time of writing, this app only accepts H264 videos.
1. Using VLC, `Tools` > `Codec information`, check the framerate. All videos must have the same frame rate otherwise you might encounter issues in Kdenlive (Kdenlive will usually reencode your input video if the framerate is variable for instance).
1. When creating a new project in Kdenlive, make sure to use the very same frame rate and frame size as the input videos, so in my case 25FPS and 1080p. Pick BT.709 as the color range, or best BT.2020 if available. FPS, frame size, color range, all of that can be changed later in `Project` > `Project Settings` but **it is not advised as it will shift clips randomly for example when changing the FPS** (I experienced it).
1. In Kdenlive settings, under `Playback`, make sure GPU acceleration is disabled, cause it's buggy. Also enable `Proxy clips` for videos larger than 1000 pixels.
1. Remove the audio track from clips, where audio does not matter.
1. You can "lock" audio or video tracks, when you absolutely do not want to misclick and inadvertently move clips.
1. When rendering, export in HEVC (smaller file size than H264). In the end, Youtube will re-encode any uploaded video to further cut down its size, and the visual quality will be more or less the same. Exporting in VP9 results in a file slightly bigger than HEVC, with the same quality. AV1 is supposedly the shit, better quality and smaller. Yet, in 2021, Kdenlive shows it but I cannot select it, it's grayed out. So I recommend HEVC, with the second slowest encoder speed (`preset=slower`), and keep the default quality setting, don't tick "Custom Quality" (`crf=28`). Use 4 threads and parallel processing.

# Songs and beats per minute

I like to sync the audio track with the video, so I try my best to change scenes on the beat. Also, I very often correct the volume of the audio tracks (usually reducing the songs' volume and increasing the original sound from the videos), through the built-in "Volume (keyframable)" effect.

**Do not forget that Kdenlive does not use milliseconds but instead a number of frames.** That should be helpful when resizing clips. Say you have a song at 100 beats per minutes and you want 4-beat clips at 25 fps. Do: 4\*60/100 = 2.4. Then, do the math again for .4 to use a scale from 0 to 25 instead of 100: 0.4\*25=10 frames. Which gives you clips that last 00:00:02 seconds and 10 frames, Kdenlive-wise.

To find the BPM of a song, use any of the following links:

- [Tap BPM - Online Beats Per Minute Calculator and Counter](http://www.beatsperminuteonline.com/)
- [MP3 to BPM (Song Analyser)](https://getsongbpm.com/tools/audio)
- [Find the BPM for any song | Type a song, get a BPM | Every tempo | songbpm](https://songbpm.com/)

## Beats calculator

<input type="text" id="beats" placeholder="Beats per minute here"/>
<input type="text" id="fps" placeholder="Desired frames per second here"/>
<pre id="results"></pre>
<script>
    let BEATS = [1,2,3,4,6,8]
    const inputBeats = document.querySelector('input#beats')
    const inputFps = document.querySelector('input#fps')
    function inputChange() {
        const value = inputBeats.value
        const fps = inputFps.value
        if (!value || isNaN(value) || !fps || isNaN(fps)) return
        const pre = document.getElementById('results')
        pre.innerHTML = ""
        BEATS = [...new Array(+value)].map(function(_,i) { return i })
        const result = BEATS.concat(value).filter(function(beat) { return beat > 0 }).map(function(beat) {
            let tempResult = (beat*60)/value
            const regex = tempResult.toString().match(/^(\d+\.)(\d+)$/)
            if (regex) {
                const integer = regex[1]
                const floating = (parseFloat("0." + regex[2], 10)*100*fps)/100
                tempResult = `${parseInt(integer, 10)} seconds and ${Math.round(floating)} frames`
            }
            else {
                tempResult = `${tempResult} seconds and 0 frames`
            }
            pre.innerHTML += "- " + beat + " beats = " + tempResult + "\n"
        })
    }
    inputBeats.oninput=inputChange
    inputFps.oninput=inputChange
    if (inputBeats.value || inputFps.value) {
        inputChange()
    }
</script>

# Fancy effects in Kdenlive

## Fade-in/out

There's a built-in effect for that. Make sure to tick "fade to/from dark".

<video autoplay loop controls>
    <source src="./videos/kdenlive/fade-out.mp4" type="video/mp4">
</video>

## Split screen

<video autoplay loop controls>
    <source src="./videos/kdenlive/split-screen.mp4" type="video/mp4">
</video>

## Iris out

<video autoplay loop controls>
    <source src="./videos/kdenlive/fade-out.mp4" type="video/mp4">
</video>

## Rewind video (VHS style)

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/MnErqP9iIWU?rel=0" frameborder="0" allowfullscreen></iframe>

## Old film

Use the "Old film" effect.

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/qoly_IIyqyI?rel=0" frameborder="0" allowfullscreen></iframe>

That's about it!
