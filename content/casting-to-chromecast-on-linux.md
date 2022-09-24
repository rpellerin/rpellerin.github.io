Title: Casting To Chromecast On Linux
Date: 2017-08-18 15:50
Category: Linux
Tags: linux, chromecast
Slug: casting-to-chromecast-on-linux
Authors: Romain Pellerin
Summary: How to cast media files to Chromecast from Linux

Several options exist when it comes to casting media content from Linux to a Chromecast.

# Casting the full desktop

You can use Google Chrome (audio is not supported).

<figure class="center">
<img alt="Casting from Chrome" src="{static}/images/chromecast-chrome.png" />
</figure>

Alternatively, you can use Firefox with [fx_cast](https://hensm.github.io/fx_cast/).

# Casting a browser tab

Same as above, use Chrome.

# Casting a file

Two options:

- Either VLC 3.0 (but it does not support subtitles yet)
- Or `castnow`

With VLC, open it and go to Preferences. Open the tab 'Video' and set Output to 'X11 video output (XCB)'. Then, try to find your Chromecast in the menu Video > Renderer > Scan. If unsuccessful, close it and run vlc from the command line:

```bash
 vlc --sout="#chromecast{ip=192.168.1.X}" myfile.mp4
```

Make sure you replace the IP with the one allocated to the Chromecast.

Another option is to use `castnow`:

```bash
npm install -g castnow
castnow --address '192.168.1.X' myfile.mp4 --subtitles mysubs.srt
```

Enjoy!
