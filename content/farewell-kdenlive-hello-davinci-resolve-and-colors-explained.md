Title: Farewell Kdenlive, Hello DaVinci Resolve. And colors explained.
Date: 2024-09-18 20:00
Category: Computers
Tags: kdenlive, davinci resolve, video
Slug: farewell-kdenlive-hello-davinci-resolve-and-colors-explained
Authors: Romain Pellerin
Summary: I'm switching from Kdenlive to DaVinci Resolve, here's why and a few tips

I've been [a long time fan of Kdenlive]({filename}/video-editing-on-linux.md), but now has come a time for change. Kdenlive is great but it's got limitations. Transitions are pretty basic, for example. Also, 10-Bit and/or HDR is not (fully) supported. Overall, the program is not as polished as a professional-grade one. That's why I decided to change and find a better video editing application. And I've settled for DaVinci Resolve, the free version (more than enough for my needs).

Before starting any new project, make sure that all cameras have similar settings: resolution, framerate, and more importantly, HDR or not! (pick Rec.2020 for HDR, and Rec.709 for SDR). [You cannot easily mix SDR and HDR footage in the same project.](https://www.reddit.com/r/VideoEditing/comments/jqqixz/hdr_and_sdr_mixed_into_same_project/)

Up until now, I've not been able to properly "downgrade" a HDR footage into a SDR project in DaVinci Resolve. But I've been sort of successful with the opposite (importing SDR footage into a HDR project and using the "Color Space Transform" effect, from Rec.709 to Rec.2100 HLG). The output colors are different but close enough.

Below you'll find my settings.

# General settings

When working with non mixed footage, that is only HDR or only SDR, I would recommend turning on [the option `Use Mac Display Color Profile for viewers`](https://youtu.be/RBctM2c4GQI?t=369) in the settings of DaVinci.

# Project settings

- Resolution: 3840 x 2160 (4K)
- FPS: 30 (or 29.97, depending on the camera)
- In `Color Management`:

      - `Color science`: [`DaVinci YRGB Color Managed`](https://youtu.be/CTAzjAReZvs?t=638). I'm not sure what the difference is with non color managed, but I think I understood that when it's not color managed, [DaVinci assumes nothing and does not perform automatic conversation from RAW footage to timeline color space](https://youtu.be/CTAzjAReZvs?t=182). But for my use cases, Color Managed is perfect and just works.
      - Untick `Automatic color management`
      - `Color processing mode`: `HDR DaVinci Wide Gamut Intermediate`
      - `Output color space`: `Rec.2100 HLG` (just like the videos shot natively with my Google Pixel)

When dealing with non-HDR footage, everything should be Rec.709. [Gamma should be 2.4, even for web deliverables](https://youtu.be/_nhlHD4wiDU?t=256). [Don't bother with Rec.709-A (Mac specific).](https://youtu.be/1QlnhlO6Gu8?t=1388) For the color processing mode, keep "DaVinci Wide Gamut Intermediate".

A general rule of thumb is "2.2 is aimed to people looking at the screen on a bright lighted room, 2.4 is for dim light room, 2.6 for pitch dark room like movie theater". ([_source_](https://www.reddit.com/r/colorists/comments/176b1fb/comment/k4l1imm/))

More on color management and gamma:

- [PRO Explains where you may be going wrong in just 10 minutes!](https://www.youtube.com/watch?v=8geDbNpH5cI)
- [Quicktime Gamma Shift Bug – What Is It and How to Combat It](https://www.cined.com/quicktime-gamma-shift-bug-what-is-it-and-how-to-combat-it/): by default, what you see in DaVinci won't be what you see in QuickTime. Use VLC. Or change some settings, but read the article first to know which ones.
- [Quicktime Color Management: why so many ISSUES?!](https://www.youtube.com/watch?v=1QlnhlO6Gu8): another video on the Quicktime Gamma bug
- [Can someone explain what Rec 709 as a gamma tag means?](https://www.reddit.com/r/colorists/comments/1c8c25i/can_someone_explain_what_rec_709_as_a_gamma_tag/)

# Export settings

2024 is also the year I'll be switching my default filming resolution from 1080p to 4k, since apparently [Youtube deteriorates less the quality with 4k videos](https://www.reddit.com/r/videography/comments/xxprwo/best_settings_to_upload_to_youtube_vmaf_analysis/) (the conclusion of the thread is that the best export settings are H265, 4k, 60mbit for 24-30fps or 120mbit for 60fps).

In DaVinci Resolve, when selecting MP4 as the format, and H.265 as the code, you get two options below:

- `Use hardware acceleration if available`: that goes without saying, tick it
- `Network Optimization`: [this is the equivalent of `-movflags +faststart` for `ffmpeg`](https://forum.blackmagicdesign.com/viewtopic.php?f=21&t=190400). This does not change the quality of the file, nor its size, and makes playback over a network faster, so you might as well tick it.

Then, export in 4K (3840 x 2160). If the project resolution is lower, the image will be upscaled, which will degrade the quality.

Make sure the output FPS is the same as the project/timeline FPS, that is 30 or 29.97 FPS.

To avoid getting too big of a file, I pick a custom bitrate of 25000 Kb/s for the file I will keep backed up. But for the file uploaded to Youtube, [it is recommended to input a bigger number](https://youtu.be/oOGZ0PfDSxM?t=351). See [Youtube's official recommendations](https://support.google.com/youtube/answer/1722171#zippy=%2Cbitrate). Usually, 50000 Kbps for a 4K video is good enough.

[Do not tick "Optimize for speed".](https://forum.blackmagicdesign.com/viewtopic.php?p=1080855#p1080855)

`Encoding Profile`: `Main10` (no need to pick 4:2:2 as Google Pixel videos are in 4:2:0).

Leave `Frame reordering` ticked, as well as the rest of the settings.

<br />
<br />

Now let's study colors...

# Some acronyms and vocabulary

- _CST_: Color Space Transform
- _DWG_: DaVinci Wide Gamut
- _RCM_: Resolve Color Management. A system designed to provide consistent color accuracy across different devices and formats. [This is a synonym for DaVinci Wide Gamut.](https://youtu.be/_EJ7lA0bSzo?t=248)
- _ACES_: Academy Color Encoding System (an alternative to DWG). [It is an industry standard color space, that works on multiple applications, unlike DWG that is DaVinci-exclusive.](https://youtu.be/AAeZKZ5feGA?t=428)
- _Nit_: a unit of luminance, officially name "Candela per square metre". In other words, a unit of measurement used to quantify the brightness of electronic displays. Other units that measure other aspects of light are Lumen and Lux.
- _EOTF_: Electro-optical Transfer
- _LUT_: Look-Up Table
- _Display-referred color grading/workflow_: [grading manually based on what you observe on screen, without any knowledge about the input color space](https://youtu.be/CTAzjAReZvs?t=26). [The opposite is known as _scene-referred workflow_ (or _color managed workflow_).](https://youtu.be/_EJ7lA0bSzo?t=1180)

# 8-bit vs 10-bit

![Banding in a 8-bit image](https://www.everlearn.nl/wp-content/uploads/2016/11/color-banding.png?_gl=1*1802gmv*_up*MQ..*_ga*NjI1MjM4NDQ0LjE3MjY2NzU4MzI.*_ga_ZR0WD7KF8X*MTcyNjY3NTgzMS4xLjAuMTcyNjY3NTgzMS4wLjAuMA..)

[Source](https://www.reddit.com/r/4kTV/comments/tm3doe/hdr_vs_sdr_vs_10_bit_vs_8_bit/):

> First of all, 10 bit HDR is not one thing, they are two things. That's why sometimes you see 10 bit SDR as well. One refers to the color range (8-bit vs 10 bit), and the other refers to the dynamic range (SDR vs HDR).

> 8 bits or 10 bits? Most of our lives, we've been watching the world in 8-bit color. When you break down what 8-bit color is, it refers to the number of gradations in each of the primary colors. When you hear 8-bit, that means there are 256 discrete possible gradations. That is 256 levels of red, 256 levels of green, and 256 levels of blue, since red, green and blue are the basic colors of each pixel on your TV set. When your TV combines these three colors in all their 256 possible gradations you end up with 256 x 256 x 256 = 16,777,216 (16.7 million) colors! That should be sufficient for most video, right? Well, not when you're doing professional photography or filming.

> There are times when you might have seen an effect called banding on your TV set. These are areas where the gradients are not as smooth in transitioning between subtle color differences. Instead, they look like bands of similar looking colors. This may be apparent in some blue sky scenes when looking at the horizon, or when there is a slow fade to black transition between scenes. Of course, this depends on how your TV processes the colors, then type of input you use, the player you're using to decode the video, or how the content you're watching is encoded.

> So, here's where 10-bit color comes to the rescue. With 10 bit color, you get 1024 gradations for each of the primary colors, resulting in a whooping 1,073,741,824 colors. That's over 1 billion colors! That oughta get rid of those bands and give you smoother gradients.

---

Some monitor or TV are "10-bit like" but not truly 10-bit, they are in fact ["8 bit + FRC"](https://www.benq.com/en-us/knowledge-center/knowledge/10-bit-vs-8-bit-frc-monitor-color-difference.html).

# SDR vs HDR

[Source](https://www.reddit.com/r/4kTV/comments/tm3doe/hdr_vs_sdr_vs_10_bit_vs_8_bit/):

> HDR or SDR? Most of our lives, we've been watching the world in SDR, or Standard Dynamic Range. Now, with newer TVs, we get this feature called HDR, or High Dynamic Range. This refers to how bright or how dark your pixels on your TV are capable of representing luminosity in the video. Instead of just adding artificial contrast to the image, HDR actually controls the individual luminosity for each pixel. This effect looks even nicer on OLED TVs which causes the image to look more realistic. Light bulbs in the background of a dark scene appear as if they are really there and the difference between dark and bright spots is less likely to be washed out.

> One of the limitations of older TV sets all this time, was the brightness bleeding effect of bright objects next to dark ones on the same scene. If your TV supports HDR, you can have a bright white object shining at maximum brightness, and it will not affect how a dark object in the next pixel looks like. HDR, therefore, preserves details in scenes where the contrast ratio of the monitor could otherwise be a hindrance.

> For those unfamiliar with photography, dynamic range is measured in stops, comparable to the aperture of a camera. Pictures on a typical SDR display would have a dynamic range of about 6 stops. HDR information, but on the other side, has the potential to almost triple this dynamic range, with a total of 17.6 stops.

---

It is commonly admitted that "[HDR is a combination of three things](https://www.reddit.com/r/linuxquestions/comments/143p9mz/comment/jndhhje/)" (although [technically HDR has nothing to do with 8 or 10 bits](https://www.reddit.com/r/4kTV/comments/tm3doe/comment/i1zjkln/)):

- More than 8 (usually 10) bits per subpixel
- Displays with a higher than usual peak brightness and contrast ratio (usually oled, or an lcd with full array local dimming)
- All the required metadata to make proper use of the above

---

[Source](https://www.reddit.com/r/gopro/comments/xe0wfx/comment/ioe0u1z/):

> HDR refers to the dynamic range. How much the camera can capture from complete white to complete dark. It's also intertwined with the gamuts of the camera. You need a wider gamut and 10+ bit color depth to get a somewhat pleasing HDR effect to look good.

# HDR10 vs Dolby Vision vs HDR10+ (vs HLG)

[Source](https://www.reddit.com/r/4kTV/comments/tm3doe/hdr_vs_sdr_vs_10_bit_vs_8_bit/):

> There are two popular HDR standards, Dolby Vision and HDR10. Since HDR10 is open source, it has been much more widely adopted than Dolby Vision. Both HDR formats use metadata in the video stream to determine how bright pixels should light up in the display. With HDR10, a fixed metadata range is declared at the start of the stream, and it remains for the entire length of the video, whereas Dolby Vision is capable of dynamic metadata from one scene to the next. There is an upgrade to HDR10 called HDR10+ which adds dynamic metadata just like Dolby Vision. Overall, you can think of HDR as the ability of the video stream to adjust your contrast and brightness levels on your TV set for each pixel separately, as opposed to your brightness and contrast menus which affect the entire picture at once.

---

[Competing formats to HDR10 are Dolby Vision and HDR10+ (which do provide dynamic metadata, allowing to preserve the creative intents on each display and on a scene by scene or frame by frame basis), and also HLG (which provides some degree of backward compatibility with SDR).](https://en.wikipedia.org/wiki/HDR10)

---

[HDR10+ and Dolby Vision do not use the same dynamic metadata.](https://en.wikipedia.org/wiki/HDR10%2B)

# Transfer functions: Gamma 2.4 vs HDR PG vs HDR HLG

PQ is for "Perceptual Quantize", the standard is known as ST.2084. HLG is for "Hybrid Log-Gamma".

[Both were introduced with Rec2100](https://en.wikipedia.org/wiki/Rec._2100#Transfer_functions) (see next section). Prior to Rec2100, Rec709 and Rec2020 were using [Gamma 2.4 (known as ITU-R BT.1886)](https://en.wikipedia.org/wiki/ITU-R_BT.1886).

As written in the section above, HLG is opposed to HDR10, Dolby Vision and HDR10+.

[This page](https://lightillusion.com/what_is_hdr.html) says:

> PQ HDR defines HDR10, HDR10+, and Dolby Vision, as all use the same target colour space - Rec2020 Gamut, with the same PQ EOTF. Consequently, calibration for all is basically identical. HLG based HDR is different.

The rest of the page is extremely detailed and full of information.

Some things to remember:

- PQ gives absolute values for light output
- HLG gives relative values

> One of the often overlooked potential issues with PQ based HDR for home viewing is that because the standard is absolute there is no way to increase the display's light output to overcome surrounding room light levels - the peak brightness cannot be increased, and neither can the fixed gamma (EOTF) curve.

> As mentioned above, with PQ based HDR the Average Picture Level (APL) will approximately match that of regular SDR (standard dynamic range) imagery. The result is that in less than ideal viewing environments, where the surrounding room brightness level is relatively high, the bulk of the PQ HDR image will appear very dark, with shadow detail potentially becoming very difficult to see. This is still true with a diffuse white target of 200 nits, rather than the original 100 nits diffuse white.

> But, having said that, HLG based HDR has its own issues if the peak luma of the display is below approx. 1000 nits, as the average picture level of the HDR image will appear dimmer than the equivalent SDR image.

> HDR10 uses Static metadata, while Dolby Vision and HDR10+ use Dynamic.

> And non-PQ based HDR, such as HLG, has no need for metadata. One of the major differences in using a relative based HDR standard, rather than absolute.

I've read on other websites that Dolby Vision also works with HLG, not just PQ.

# Rec709 vs Rec2020 vs Rec2100

Those are color gamuts (a set of displayable colors).

Rec709 is generally for SDR.

Rec2020 and Rec2100 are commonly used for HDR. Rec2020 can also be used for SDR. They are both refered to as Wide Color Gamuts (WCG).

Rec2020 (or Rec.2020) is an abbreviation for "_ITU-R Recommendation BT.2020_".

[Rec. 2100 uses the same color primaries as Rec. 2020 which is a Wide Color Gamut](https://en.wikipedia.org/wiki/Rec._2100#System_colorimetry) (meaning [a color gamut wider than that of BT.709](https://en.wikipedia.org/wiki/Gamut#Wide_color_gamut)).

[Rec709 is recommendations for HD. Rec2020 for UHD (Ultra HD). Rec2100 for HDR.](https://youtu.be/IXYKhZdGF6s?t=109)

# 4:2:2 vs 4:2:0

[Chroma Subsampling, on Wikipedia.](https://en.wikipedia.org/wiki/Chroma_subsampling)

[Short explanation on Reddit here.](https://www.reddit.com/r/4kTV/comments/tm3doe/comment/i21n8df/)

# Linux support for 10-bit and/or HDR

[As of 2024, HDR is not supported on Linux. 10-bit is partially supported but not enabled by default.](https://www.reddit.com/r/linux_gaming/comments/mk2q0j/buying_a_monitor_for_hdr10bit_pointless_on_linux/) [Here is how to turn on 10-bit on Linux.](https://linuxreviews.org/HOWTO_enable_10-bit_color_on_Linux) Of course the GPU needs to support it.

On Linux, playing a HDR video in VLC will show a washed-out video, [but using MPV the colors will be "translated" into SDR and the video will look very nice](https://www.reddit.com/r/linuxquestions/comments/143p9mz/comment/jnbxar8/).

# Monitors and TV support for HDR

[Unless a monitor is labeled HDR1000, it is not true HDR.](https://www.reddit.com/r/linux_gaming/comments/mk2q0j/comment/gte1gj1/) [This other Reddit thread more or less says the same.](https://www.reddit.com/r/Monitors/comments/12cl67z/how_to_know_if_a_monitor_has_real_hdr/)
