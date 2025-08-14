Title: My Ultimate Video Edition Tutorial
Date: 2025-08-14 14:20
Category: Video editing
Tags: video, editing, davinci resolve
Slug: my-ultimate-video-editing-tutorial
Authors: Romain Pellerin
Summary: Everything to edit awesome videos

# Camera setup

- **30 fps** (or 60 fps for slow-mo). Cause it's the most widely available format, and my Pixel phone still does not support 25fps.
- **HEVC/H265**
- **SDR (Rec709)**. Why not HDR? Support for HDR is still very limited. Most external monitors still do not render HDR properly, same for social media (Strava, Instagram)
- **4K (Ultra HD)**

# Story telling

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/kioBTrOEFUo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/vHfVI_4unYY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

# Software

Davinci Resolve.

## Settings

### Master settings

- **29.97** or **30 fps**, depending on what your device produced. Pixel phones actually produce 29.97fps.
- **4K (3840x2160)**. You can tick "Use vertical resolution" when producing for social media.

<figure class="center">
<img src="{static}/images/ultimate-video-tutorial/master-settings.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Master settings</figcaption>
</figure>

### Color management

["DaVinci YRGB Color Managed" with "Automatic color management" ticked is the easiest and best choice, unless you know what you're doing.](https://www.youtube.com/watch?v=c4AVwVdKTHc) Also, go with SDR and Rec.709, as HDR is not yet widely supported.

<figure class="center">
<img src="{static}/images/ultimate-video-tutorial/color-management.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Color management</figcaption>
</figure>

# How to sync clips on music beats

I like to sync the audio track with the video, so I try my best to change scenes on the beat.

For transitions, I recommend that the new clip starts 1 or 2 frames before the next music beat, because of [the persistence of vision](https://en.wikipedia.org/wiki/Persistence_of_vision). I read [on Reddit](https://www.reddit.com/r/kdenlive/comments/dzzcib/is_there_a_way_to_match_the_bpm_of_a_song_while/) that the human eye takes 1/10th of a second to process images, while sound is near instant. 1/10th of a second, with 30FPS, would mean 3 frames. 1 or 2 frames is usually enough though, in my experience.

<figure class="center">
<img src="{static}/images/kdenlive-transition-music-beat.png" alt="Screenshot of Kdenlive" />
<figcaption>Here the transition happens 1 frame before the next music beat</figcaption>
</figure>

To determine how long a clip should last, based on the music, I use this website: [Tap BPM - Online Beats Per Minute Calculator and Counter](http://www.beatsperminuteonline.com/). Once I know the "speed" of a song (how many beats per minute), I use the calculator below to determine clip lengths.

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
                const seconds = parseInt(integer, 10)
                const frames = Math.round(floating)
                tempResult = `${seconds} seconds and ${frames} frames (${(seconds * fps) + frames} frames)`
            }
            else {
                tempResult = `${tempResult} seconds and 0 frames (${tempResult * fps} frames)`
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

# Titles

I like to use the font "[PlayFair Display](https://fonts.google.com/specimen/Playfair+Display)" with a glowing effect, in two colors mostly: beige (#FFEBAF) and red (#FF2600).

<figure class="center">
<img src="{static}/images/ultimate-video-tutorial/titles-davinci.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Titles in Davinci</figcaption>
</figure>

<figure class="center">
<img src="{static}/images/ultimate-video-tutorial/title-glow1.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Glow settings</figcaption>
</figure>

<figure class="center">
<img src="{static}/images/ultimate-video-tutorial/title-glow2.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Glow settings</figcaption>
</figure>

# Color grading

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/Zq_MU02oYo8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

# Output

- FPS: keep the same setting as defined in the project settings
- **HEVC/H265**: for size reasons
- **MP4** as a container
- **SDR (Rec709)**
- **4K (Ultra HD)**
- **10MBps** for storing on external hard disk drives, **50MBps** for the file to upload to Youtube or social media.

# Example of a recent project of mine

<video controls>
  <source src="./videos/ultimate-video-tutorial.mp4" type="video/mp4">
</video>

<style>
video {
  display: block;
  margin: auto;
  width: 50%;
}
</style>
