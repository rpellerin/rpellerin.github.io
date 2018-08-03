Title: Build a Security Camera with a Raspberry Pi
Date: 2018-08-01 01:30
Category: Computers
Tags: raspberry pi, security, camera
Slug: build-a-security-camera-with-a-raspberry-pi
Authors: Romain Pellerin
Summary: How to build a robust theftproof security camera with a Raspberry Pi and Raspbian

I have owned for a long time a [Heden IP security camera](http://www.heden.fr/55-18-Cameras-de-surveillance-Camera-Int-Motorisee-p-247-Camera-IP-Interieure---Wifi---V-5.5---Noir.html). I had been very content with it until the moment it stopped sending emails, for no reason. I was still working though, I could access a live stream from the Internet, but it just would send emails. I decided it was time to build something myself with a Raspberry Pi. The only thing that was cool but I won't miss about my old security camera was its ability to rotate and controlled remotely, so that I could have a pretty large view of my apartment. But I wasn't making any use of it since I was not actively watching the live stream. I was however using the email on alert feature.

# Requirements

Let's start off with listing the requirements for this project.

## Basic features

- Must send good resolution pictures (and videos if need be) on motion detection
- Must be easily concealable (no LEDs visible, even at night)
- Must be easily turned on
- Must be able to live stream to the Internet if I want to

## Advanced features (from most important to least)

- Must no fail because of the SD card
- Not easily hackable (penetrable) through my LAN
- Must send daily signs of life as a proof no one took it down on purpose or my apartment did not burn down
- Must free up space when it runs out of disk space
- Must notify me when being turned on and tell me how long it had been off
- Must be resiliant to power outage, and auto-restart. Must also handle cases when network is not available
- Must notify me when being purposedly shut down
- Must detect when no connectivity and be resiliant to it
- Must notify me when someone logs in to the Raspberry Pi, either remotely or physically with a keyboard connected to it
- Must be easily shut down when I get home (through a physical button ideally)

Now let's try to address these bullet points one by one. But first, the big picture.

# Setting up the Raspberry Pi

For this project, I used an old Rasberry Pi 1 model B revision 2, an Ethernet cable and the [camera module version 2.1](https://www.kubii.fr/cameras-accessoires/1653-module-camera-v2-8mp-kubii-640522710881.html). You should consider buying the Pi NoIR camera with infrared LEDs to enable night vision (daylight pictures however look red-ish and bad quality).

Setting it up was fairly straightforward, I simply followed [a tutorial I had already written]({filename}/raspberry-pi-the-ultimate-guide.md). However I did not set `max_usb_current=1` cause my power supply cannot output more than 1A. When running `raspi-config`, make sure to:

- Enable the camera
- Give the GPU at least 128MB (more is recommended, apparently)

You can stop reading the tutorial at the end of the section "Configuration". Make sure to install `ntpdate` otherwise you'll get wrong dates in some emails.

## Fine tuning

    :::bash
    sudo tune2fs -c -1 -i 0 /dev/mmcblk0p2 # no check when booting
    sudo tune2fs -O ^has_journal /dev/mmcblk0p2 # no journalling, must be done from a PC on mmcblk0p2 unmounted

In `/etc/fstab`:

    :::text
    /dev/mmcblk0p2 / ext4 defaults,noatime 0 0 (final zero means never run fsck
    tmpfs /tmp tmpfs defaults,noatime,size=34m 0 0
    tmpfs /var/log tmpfs defaults,noatime,size=30m 0 0

Also disable swaping to extend your SD card lifetime:

    :::bash
    sudo swapoff --all # Temporary
    sudo update-rc.d -f dphys-swapfile 
    sudo apt remove dphys-swapfile # Permanently
    sudo rm /var/swap

## Camera

Now has come the time to turn off the Raspberry Pi, unplug the power supply, ground yourself, and plug in the camera model in the CSI port (next to the HDMI one). The camera module is very vulnerable to static eletrictiy that's why we have to be that cautious.

Then turn it back on and make sure it is correctly detected:

    :::bash
    sudo vcgencmd get_camera

# Software for motion detection and video/image capture

Now, we've got two options.

1.  Build our own solution based on [Python scripts](https://picamera.readthedocs.io/). Tedious and far from perfect.
2.  Use an existing software program. Seems to be the best solution.

There are plenty of existing programs to do motion detection on a Raspberry Pi. The two most interesting ones I found are these two:

- [Motion](https://github.com/Motion-Project/motion/)
- [Raspberry PI-TIMOLO](https://github.com/pageauc/pi-timolo)

The first one seems to be quite a big project, with a large community. On the other hand, the second one is more of a personal side-project and seems to be lacking features Motion has. So I decided to give Motion a try.

Also note that Motion comes with a web frontend, [MotionEye](https://github.com/ccrisan/motioneye). Since our camera has no motor to rotate, I thought there was no need for a web interface with controls. If there ever was to be motion, I would get emails with pictures anyway. Therefore I did not install MotionEye.

# Motion

## Install

Although installation through `apt` is possible, you will most likely get an outdated version of Motion. I decided to build it from their Github repo. [Here is the official turorial I followed](https://motion-project.github.io/motion_build.html#BUILD_DEBIAN). Make sure to also install `ffmpeg` with `apt` (only useful to make videos out of pictures though, but you'll probaly want to try this feature out).

We want Motion to be able to send emails, so let's install Exim4 by reading the relevant section on [my tutorial here]({filename}/raspberry-pi-the-ultimate-guide.md). Or you might want to use a simpler solution with [`mpack`](https://www.bouvet.no/bouvet-deler/utbrudd/building-a-motion-activated-security-camera-with-the-raspberry-pi-zero).

Here is the configuration file I personally use (`motion -c motion-dist.conf`):

<div class="body_foreground body_background">
<style type="text/css">
    .ansi2html-content {
        display: inline;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .body_foreground {
        color: #AAAAAA;
        padding: 5px;
    }

    .body_background, .body_background pre {
        background-color: #000000; /* reset global style */
        border-radius: 0; /* reset global style */
    }

    .body_background pre {
        padding: 0; /* reset also */
    }

    .body_foreground>.bold,
    .bold>.body_foreground,
    body.body_foreground>pre>.bold {
        color: #FFFFFF;
        font-weight: normal;
    }

    .inv_foreground {
        color: #000000;
    }

    .inv_background {
        background-color: #AAAAAA;
    }

    .ansi1 {
        font-weight: bold;
    }

    .ansi31 {
        color: #aa0000;
    }

    .ansi32 {
        color: #00aa00;
    }

    .ansi36 {
        color: #00aaaa;
    }
</style>
<pre class="ansi2html-content">
<span class="ansi31">-flip_axis none</span>
<span class="ansi32">+</span><span class="ansi32">flip_axis horizontal</span>
 
<span class="ansi31">-width 320</span>
<span class="ansi32">+</span><span class="ansi32">width 1440</span>
 
<span class="ansi31">-height 240</span>
<span class="ansi32">+</span><span class="ansi32">height 1080</span>
 
<span class="ansi31">-framerate 2</span>
<span class="ansi32">+</span><span class="ansi32">framerate 25</span>
 
<span class="ansi31">-; mmalcam_name vc.ril.camera</span>
<span class="ansi32">+</span><span class="ansi32">mmalcam_name vc.ril.camera</span>
 
<span class="ansi31">-; mmalcam_control_params -hf</span>
<span class="ansi32">+</span><span class="ansi32">mmalcam_control_params -hf</span>
 
 # Since our resolution is quite big, we must avoid false alarms and thus have a big number here
<span class="ansi31">-threshold 1500</span>
<span class="ansi32">+</span><span class="ansi32">threshold 15000</span>
 
<span class="ansi31">-event_gap 60</span>
<span class="ansi32">+</span><span class="ansi32">event_gap 10</span>
 
<span class="ansi31">-max_movie_time 0</span>
<span class="ansi32">+</span><span class="ansi32">max_movie_time 300</span>
 
<span class="ansi31">-output_pictures off</span>
<span class="ansi32">+</span><span class="ansi32">output_pictures on</span>
 
<span class="ansi31">-quality 75</span>
<span class="ansi32">+</span><span class="ansi32">quality 80</span>
 
<span class="ansi31">-ffmpeg_output_movies on</span>
<span class="ansi32">+</span><span class="ansi32">ffmpeg_output_movies off</span>
 
<span class="ansi31">-locate_motion_mode off</span>
<span class="ansi32">+</span><span class="ansi32">locate_motion_mode on</span>
 
<span class="ansi31">-locate_motion_style box</span>
<span class="ansi32">+</span><span class="ansi32">locate_motion_style redbox</span>
 
<span class="ansi31">-text_scale 1</span>
<span class="ansi32">+</span><span class="ansi32">text_scale 2</span>
 
<span class="ansi31">-#target_dir /tmp/motion</span>
<span class="ansi32">+</span><span class="ansi32">target_dir /home/pi/pics_and_vids</span>
 
<span class="ansi32">+</span><span class="ansi32"># No need to change it since the Raspberry Pi is not powerful enough to stream at more than 1FPS while taking pictures</span>
 stream_maxrate 1
 
<span class="ansi31">-stream_localhost on</span>
<span class="ansi32">+</span><span class="ansi32">stream_localhost off</span>
 
<span class="ansi31">-; on_event_start value</span>
<span class="ansi32">+</span><span class="ansi32">on_event_start echo "There was a motion, %D pixels changed" | mail -s "Webcam Alert %v - Start" me@domain</span>
 
<span class="ansi31">-; on_event_end value</span>
<span class="ansi32">+</span><span class="ansi32">on_event_end echo "There is no more motion" | mail -s "Webcam Alert %v - End" me@domain</span>
 
<span class="ansi31">-; on_picture_save value</span>
<span class="ansi32">+</span><span class="ansi32">on_picture_save echo "%D pixels changed" | mail -A %f -s "Webcam Alert %v - Picture" me@domain</span>
 
<span class="ansi31">-; on_motion_detected value</span>
<span class="ansi32">+</span><span class="ansi32">; on_motion_detected</span>
 
<span class="ansi31">-; on_movie_end value</span>
<span class="ansi32">+</span><span class="ansi32">on_movie_end echo 'webcam alert' | mail -A %f -s "Webcam Alert - Video" me@domain</span>
 
<span class="ansi31">-; on_camera_lost value</span>
<span class="ansi32">+</span><span class="ansi32">on_camera_lost echo 'webcam was lost' | mail -s "Webcam LOST" me@domain</span>

<span class="ansi31">-text_right %Y-%m-%d\n%T-%q</span>
<span class="ansi32">+</span><span class="ansi32">text_right %Y-%m-%d\n%T</span>
</pre>
</div>

# Meeting the initial requirements

Now that we just installed Motion, let's address the above-mentioned requirements one by one.

## Basic features

- *Must send good resolution pictures (and videos if need be) on motion detection*

    > This is addressed in the configuration file above.

- *Must be easily concealable (no LEDs visible, even at night)*

    > Do your best to do this with duck tape, Blu Tack or something else.
    
- *Must be easily turned on and started*

    > I connected my Raspberry with an extension that has a switch. As to the auto start feature, here is how to do it:

First, copy the SystemD file and enable the service:

    :::bash
    sudo cp motion/motion.service /etc/systemd/system/
    sudo systemctl enable motion.service

Add these two lines below `[Service]`:

    :::text
    Restart=always
    RestartSec=3
    ExecStart=/usr/local/bin/motion -n -c /home/pi/motion-dist.conf

Replace the existing line `ExecStart` with the one above. `-n` is to force non-daemon mode.

Then run:

    :::bash
    sudo systemctl daemon-reload

- *Must be able to live stream to the Internet if I want to*

    > Addressed in the configuration file above. Make sure to open ports to the Internet in your router configuration.

## Advanced features (from most important to least)

- *Must no fail because of the SD card*

    > This one can be addressed by using an [external HDD](https://www.kubii.fr/carte-sd-et-stockage/1790-disque-dur-pidrive-foundation-edition-kubii-718037846736.html) for the root partition instead of a SD card.

- *Not easily hackable (penetrable) through my LAN or the Internet*

    > Quite simple. Disable Wifi when the security camera is on. Also, don't expose it to the Internet (don't allow ports to be reached through your router). This disables live streaming but enforce security.

- *Must send daily signs of life as a proof no one took it down on purpose or my apartment did not burn down*

    > For this one, we are going to create a cron that runs daily and send an email. See bullet point below.

- *Must free up space when it runs out of disk space*

    > A good solution would be to create a script that runs every 30mins thanks to cron, check for space left. If too little, it zips all the pictures and videos just in case you did not get them, email it to you and then delete the zip and the original files.

Create `/home/pi/alive-script.sh`:

    :::bash
    #!/bin/bash

    set -u

    DATE="$(date)"

    args=("$@")

    if [ $# -gt 0 ] && [ "${args[0]}" = "--daily" ]; then 
        # echo 'Daily alive script running...'
        echo -e "I am alive. - $DATE\n\n$(df -H /)\n\n$(systemctl status motion | cat)" | mail -s "Raspberry Pi is alive" me@domain
        # Gives it time to finish sending emails, just in case
        sleep 5
        # echo 'Daily report sent'
        exit 0
    fi

    FILENAME="$(date +%s).tar.gz"
    PICS_AND_VIDS="/home/pi/pics_and_vids"
    PICS_AND_VIDS_BASENAME="pics_and_vids"
    PICS_AND_VIDS_PARENT_DIR="/home/pi"

    delete_pics_vids () {
        rm $PICS_AND_VIDS/*.jpg -f
        rm $PICS_AND_VIDS/*.mkv -f
    }

    # Deletes previous backups, if any
    rm $PICS_AND_VIDS_PARENT_DIR/*.tar.gz -f
    # sync for df to return the correct values
    sync

    DISK_SPACE_THRESHOLD=6
    #DISK_SPACE_THRESHOLD=35
    DISK_SPACE_THRESHOLD_CANT_BACKUP=$((DISK_SPACE_THRESHOLD + 10))

    PERCENT_DISK_USAGE=$(df -H / | grep -vE '^Filesystem|tmpfs|cdrom' | awk '{ print $5 }' | cut -d'%' -f1)

    if [ $PERCENT_DISK_USAGE -le $DISK_SPACE_THRESHOLD ]; then
        # Less than $DISK_SPACE_THRESHOLD% of disk space used, doing nothing...
        exit 0
    fi

    if [ $PERCENT_DISK_USAGE -ge $DISK_SPACE_THRESHOLD_CANT_BACKUP ]; then
        echo "More than $DISK_SPACE_THRESHOLD_CANT_BACKUP% of disk space used, deleting pics and vids and notifying via email..."
        echo "Little disk space left ($PERCENT_DISK_USAGE% used). CANT BACKUP. - $DATE" | mail -s "Raspberry Pi - DISK USAGE ALERT" me@domain
        # Give time to finish sending email
        sleep 5
        delete_pics_vids
        exit 0
    fi

    # 2>/dev/null to suppress the output error "No such file or directory"
    if [ -n "$(ls -A $PICS_AND_VIDS 2>/dev/null)" ]; then
        # Contains files (or is a file)"
        cd $PICS_AND_VIDS_PARENT_DIR
        tar -cvzf $PICS_AND_VIDS_PARENT_DIR/$FILENAME $PICS_AND_VIDS_BASENAME
        echo "Pics and vids were deleted. Here is a backup of them. - $DATE" | mail -A $FILENAME -s "Raspberry Pi - Backup" me@domain
        delete_pics_vids
        # Give time to finish sending email
        sleep 30
        # Delete backup we just created
        rm $PICS_AND_VIDS_PARENT_DIR/$FILENAME -f
    else
        # Empty (or does not exist)
        echo "Little disk space left ($PERCENT_DISK_USAGE% used). No pics or vids found though. - $DATE" | mail -s "Raspberry Pi - DISK USAGE ALERT" me@domain
        # Give time to finish sending email
        sleep 5
    fi

Then run `crontab -e` as `pi` and:

    :::bash
    0    12 * * * /home/pi/alive-script.sh --daily
    */30 *  * * * /home/pi/alive-script.sh

- *Must notify me when being turned on and tell me how long it had been off*

    > Shoud be easy.
    
Add the following in /etc/rc.local, right above `exit 0`:

    :::bash
    echo "So you know... ($(date))" | mail -s "Rpi turned on" me@domain &
    sleep 2
    echo -e "So you know... ($(date))\n\n$(tail -n 500 /var/log/syslog)" | mail -s "Rpi turned on (with syslog)" me@domain &
    sleep 5
    exit 0

- *Must be resiliant to power outage, and auto-restart. Must also handle cases when network is not available*

    > Auto start was addressed a few lines above. However, how to wait for network to be up? [TODO](https://www.raspberrypi.org/forums/viewtopic.php?t=187225)

- *Must notify me when being purposedly shut down*

    > TODO

- *Must detect when no connectivity and be resiliant to it*

    > TODO

- *Must notify me when someone logs in to the Raspberry Pi, either remotely or physically with a keyboard connected to it*

    > TODO

- *Must be easily shut down when I get home (through a physical button ideally)*

    > TODO: For this one I have yet to find an easy and cheap solution. 


Hope this helps.

# Further reading

- [Smarten up your Pi Zero Web Camera with Image Analysis and Amazon Web Services (Part 1)](https://www.bouvet.no/bouvet-deler/utbrudd/smarten-up-your-pi-zero-web-camera-with-image-analysis-and-amazon-web-services-part-1)
