Title: Tuto Davinci Resolve: Dynamic Short on Indoor Cycling
Date: 2026-03-02 23:20
Category: Video editing
Tags: davinci resolve, cycling
Slug: tuto-davinci-resolve-dynamic-short-on-indoor-cycling
Authors: Romain Pellerin
Summary: How to create a nice short video about indoor cycling in winter

<video controls>
  <source src="./videos/tuto-davinci-resolve-home-trainer.mp4" type="video/mp4">
</video>

# Vertical format

Tick "Use vertical resolution". Prefer 4K instead of 1080p.

# Color management

Based on your source material but also the destination platform (Youtube, Instagram), you may want to export in SDR (Rec.709), as HDR is not always supported.

# Glitch effect

[Full tutorial there](https://www.youtube.com/watch?v=GT2MPZJn9aI). [The sound used is findable on this site](https://blog.pond5.com/78871-free-after-effects-templates-digital-distortion-free-after-effects-template/).

<figure class="center">
<img class="zoomable big" src="{static}/images/tuto-davinci-resolve-short-indoor-cycling/glitch-composite.png" alt="Screenshot of Davinci Resolve" />
<figcaption><a href="https://www.mediafire.com/folder/h4p84chqtsqmq/Glitch_Overlays_QuinceMedia" href="Stock glitch videos">Glitch video</a> with composite mode: Substract</figcaption>
</figure>

<figure class="center">
<img class="zoomable big" src="{static}/images/tuto-davinci-resolve-short-indoor-cycling/adjustment-flicker.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Adjustment clip with Flicker effect</figcaption>
</figure>

<figure class="center">
<img class="zoomable big" src="{static}/images/tuto-davinci-resolve-short-indoor-cycling/adjustment-camera-shake.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Adjustment clip with Camera Shake effect</figcaption>
</figure>

<figure class="center">
<img class="zoomable big" src="{static}/images/tuto-davinci-resolve-short-indoor-cycling/adjustment-camera-shake2.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Adjustment clip with Camera Shake effect (2)</figcaption>
</figure>

<figure class="center">
<img class="zoomable big" src="{static}/images/tuto-davinci-resolve-short-indoor-cycling/adjustment-jpeg-damage.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Adjustment clip with JPEG Damage effect</figcaption>
</figure>

<figure class="center">
<img class="zoomable big" src="{static}/images/tuto-davinci-resolve-short-indoor-cycling/transition.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Transition between the two clips</figcaption>
</figure>

# Glowing white light in place of a person

<figure class="center">
<img class="zoomable big" src="{static}/images/tuto-davinci-resolve-short-indoor-cycling/freeze-frame.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Freeze a frame</figcaption>
</figure>

## Step 1: isolating the person

1. Switch to the Color Page.
1. Add a new Serial Node (Alt + S on Windows or Option + S on Mac) or use the default one

Now, on the paid version of Davinci Resolve:

1. Open the Magic Mask palette (the icon looks like a person with a mask).
1. Draw a quick stroke over the person you want to replace.
1. Click the Track Forward and Reverse button (play icon with arrows on both sides) to let Resolve track the person throughout the clip.

On the free version: use the Power Window tool (the pen tool icon) to manually draw and track a mask around the person frame-by-frame.

<figure class="center">
<img class="zoomable big" src="{static}/images/tuto-davinci-resolve-short-indoor-cycling/power-window.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Power Window</figcaption>
</figure>

Edit the settings under "Softness" if need be.

## Step 2: turning the person pure white

With the Magic Mask or Power Window node selected, go to the primaries color wheels. Crank the gain wheel all the way up to brighten the highlights.

<figure class="center">
<img class="zoomable big" src="{static}/images/tuto-davinci-resolve-short-indoor-cycling/pure-white.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Gain wheel</figcaption>
</figure>

Next, open the Curves palette. Grab the bottom-left point of the main curve (the shadows) and drag it all the way to the top-left corner. This forces the darkest parts of the image to become pure white.

<figure class="center">
<img class="zoomable big" src="{static}/images/tuto-davinci-resolve-short-indoor-cycling/pure-white2.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Shadows up</figcaption>
</figure>

## Step 3: adding the glow effect

Add another Serial Node after the white cutout node. Open the effects library (top right corner). Search for Glow (under Resolve FX Light) and drag it onto this new node.

<figure class="center">
<img class="zoomable big" src="{static}/images/tuto-davinci-resolve-short-indoor-cycling/glow.png" alt="Screenshot of Davinci Resolve" />
<figcaption>Glow effect</figcaption>
</figure>

Go to the settings panel for the glow effect and adjust the following to your liking:

- Shine Threshold: Lower this to ensure the entire white shape emits light.
- Spread / Glow Size: Increase this to make the light bleed out over the background.
- Glow Color: It should default to white, but you can tint it slightly blue or yellow here if you want the light to have a specific temperature.
- Composite Type: Ensure it's set to "Add" or "Screen" to make the glow blend naturally over the background.

That's it!

<style>
video {
  display: block;
  margin: auto;
  width: 50%;
}
</style>
