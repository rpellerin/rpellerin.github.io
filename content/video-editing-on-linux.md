Title: Video Editing on Linux
Date: 2018-05-13 17:30
Category: Linux
Tags: video, linux
Slug: video-editing-on-linux
Authors: Romain Pellerin
Summary: How to edit videos on Linux

This is a reminder for myself. I thought it could be useful to other people too, that's why I put it online.

1. Record in 1080p, at 25 FPS, unless you want to go fancy and do 50 FPS and/or 4k. Why 25 and not 24 or 30? Because 24 is not always 24, sometimes it's actually 23.976, and 30 is sometimes 29.97, depending on the camera you are using. You're never so sure. 30FPS on the GoPro is 29.97, but 30FPS on the Insta360 One X2 givess 30 when exported from the mobile app, 29.97 from the desktop app. While 25 is always 25, on any camera, any software.
2. Download [Kdenlive](https://kdenlive.org/en/download/).
3. Using VLC, `Tools` > `Codec information`, check the framerate. All videos must have the same frame rate otherwise you might encounter issues.
4. When creating a new project in Kdenlive, make sure to use the very same frame rate and frame size. This can be changed later in `Project` > `Project Settings` but **it is not advised as it will shift clips randomly** (I experienced it). There, also enable `Proxy clips` for videos larger than 1000 pixels.
5. In Kdenlive settings, under `Playback`, make sure GPU acceleration is disabled, cause it's buggy.
6. When rendering, set the quality to the maximum, the speed encoder to the second level to the leftmost one and use 4 threads. Encoder speed and threads are two different settings not related. Export in H264 or HEVC (smaller file size). In the end, Youtube will re-encode any uploaded video to further cut down its size, and the visual quality will be more or less the same. Exporting in VP9 results in a file slightly bigger than HEVC, with the same quality. AV1 is supposedly the shit, better quality and smaller. Yet, in 2021, Kdenlive shows it but I cannot select it, it's grayed out.

**Do not forget that Kdenlive does not use milliseconds but instead a number of frames.** That should be helpful when resizing clips. Say you have a song at 100 beats per minutes and you want 4-beat clips at 25 fps. Do: 4\*60/100 = 2.4. Then, do the math again for .4 to use a scale from 0 to 25 instead of 100: 0.4\*25=10 frames. Which gives you clips that last 00:00:04 seconds and 10 frames, Kdenlive-wise.

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
        const result = BEATS.concat(value).filter(function(beat) { return beat \> 0 }).map(function(beat) {
            let tempResult = (beat\*60)/value
            const regex = tempResult.toString().match(/^(\d+\.)(\d+)$/)
            if (regex) {
                const integer = regex[1]
                const floating = (parseFloat("0." + regex[2], 10)\*100*fps)/100
                tempResult = \`${parseInt(integer, 10)} seconds and ${Math.round(floating)} frames\`
            }
            else {
                tempResult = \`${tempResult} seconds and 0 frames\`
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

That's about it!
