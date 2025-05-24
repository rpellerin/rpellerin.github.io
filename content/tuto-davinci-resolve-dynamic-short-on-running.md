Title: Tuto Davinci Resolve: Dynamic Short on Running
Date: 2025-05-24 23:20
Category: Computers
Tags: davinci resolve, running
Slug: tuto-davinci-resolve-dynamic-short-on-running
Authors: Romain Pellerin
Summary: How to create a nice short video about a running event using Davinci Resolve

<video controls>
  <source src="./videos/paris-marathon-2025.mp4" type="video/mp4">
</video>

# Vertical format

Tick "Use vertical resolution".

![Screenshot of Davinci Resolve]({static}/images/tuto-davinci-resolve-short-running/1.png)

# Color management

Based on the destination platform (Youtube, Instagram), you may want to export in SDR, as HDR is not always supported.

![Screenshot of Davinci Resolve]({static}/images/tuto-davinci-resolve-short-running/2.png)

# Create a compound video

Select all of your video elements in the Media Pool, and group them in a new timeline. Later, we will apply video effects on the entire compound video.

![Screenshot of Davinci Resolve]({static}/images/tuto-davinci-resolve-short-running/3.png)

# For each clip of the compound video

- Sync the duration of each clip with the background song. Ideally, clip transitions should occur one frame before the next music beat, not earlier. Videos on social media are watched on a phone, so the distance "phone to ears" is short, no need to compensate like you'd do for videos meant for TV, where two frames ahead of the beat is often better for transitions.
- No need to mute each individual clip, as the compound video will be muted in the timeline.
- For static images: apply zoom-in effects and changes in the position, to add dynamism. You'll find these in the "Video" tab. Use 2 keyframes (red dots in the screenshot): one on the first frame, one on the last
- For videos: zoom-in and position effects should be used too. Changing the position throughout the clip can help shift the focus from one element to another, from one person to something, etc.

![Screenshot of Davinci Resolve]({static}/images/tuto-davinci-resolve-short-running/4.png)

# Final cut in the main timeline

1. Add your compound video to the timeline, and mute its audio track.
1. Add your background song to the timeline.
1. In "Color" at the bottom of the screen, find the "RGB Mixer" and select "Monochrome".
1. Add effects on the compound video:

    1. "Camera Shake": play around vith the values but make it subtle, not too shaky
    1. "Film Damage": same, make it subtle. Disable the Scratch effect.

![Screenshot of Davinci Resolve]({static}/images/tuto-davinci-resolve-short-running/5.png)
![Screenshot of Davinci Resolve]({static}/images/tuto-davinci-resolve-short-running/6.png)
![Screenshot of Davinci Resolve]({static}/images/tuto-davinci-resolve-short-running/7.png)

That's it! Ready to export.

<style>
video {
  display: block;
  margin: auto;
  width: 50%;
}
</style>