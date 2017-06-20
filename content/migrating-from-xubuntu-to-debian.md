Title: Migrating From Xubuntu To Debian
Date: 2016-05-21 02:00
Modified: 2016-07-03 13:13
Category: Linux 
Tags: linux, debian, xubuntu
Slug: migrating-from-xubuntu-to-debian
Authors: Romain Pellerin
Summary: How to install Debian 8 

Today marks a new beginning: **I'm switching to Debian 8**. A very bare installation of Debian actually.

Until now, I've been using Xubuntu for over 3 years as my main operating system, on both my laptop and desktop computer. However, yesterday, as I was installing Xubuntu 16.04 on a friend's laptop, I noticed a few bugs quite annoying (like the [disappearing mouse](https://bugs.launchpad.net/ubuntu/+bug/1573454)). In addition, I had been thinking about migrating to Debian for quite a long time (a friend of mine had already taken that step a couple of months ago). Xubuntu is shipped with a bunch of useless programs that I would never use. So time has come to have a lightweight bare distro, such as Debian 8. Moreover, my aging laptop would hugely benefit from that.

# Tutorial

Let's get straight to the point: **how to install Debian 8 on a whole encrypted disk?**

Why encryption? Although I don't have sensitive data to protect, I take my laptop everywhere I go. Consequently, I am vulnerable to theft. What is on my disk is not valuable but still, I prefer to keep my stuff private.

Before starting, make sure you have two USB sticks ready nearby, and another computer with a working Internet connection.

Here are the steps to get a bare installation of Debian 8:

1. Download a "*small installation image*" on [the official website](https://www.debian.org/distrib/).
2. Flash it on a USB stick:

        :::bash
        ls /dev/sd*
        # Plug the USB stick
        ls /dev/sd* # Detect which is yours
        sudo umount /dev/sdX1
        sudo dd if=debian-8.4.0-amd64-netinst.iso of=/dev/sdX bs=1M
        sudo sync; sync
        sudo umount /dev/sdX1

3. On the target PC, disable any HDD password or BIOS password. It might prevent you from encrypting the disk. I experienced it.
4. Boot on the USB flash drive. Select "*Install*" (first choice).
5. Follow the instructions. You can leave the domain name blank. If you get a message "No common CD-ROM drive was detected", press ALT+F2 and do the following:

        :::bash
        blkid # Identify a device with a partition of type iso9660
        mkdir /mnt/iso
        mount -t iso9660 /dev/sdb1 /mnt/iso

    Press ALT+F1 to return to the installation dialog. Continue the installation.

6. If at some point a message says that proprietary firmware files are needed, it's time to go back to the other computer and USB stick. Otherwise, skip this step.

    [![Picture]({filename}/images/debian_firmware.jpg)]({filename}/images/debian_firmware.jpg)

    1. Download the mentioned file(s) from the [Debian repository](https://packages.debian.org/jessie/).
    2. Put the `.deb` file(s) on the USB stick.
    3. Unplug both USB sticks: the one containing Debian on the target computer, and the one containing the firmware file(s).
    4. Plug the one containing firmwares files on your target computer, in the same USB port as the previous USB stick.
    5. Answer "*Yes*" to "*Load missing firmware from removable media?*".
    6. Once completed, unplug the USB stick and plug the one containing Debian in the same port. Then press ALT+F1 and do:
    
            :::bash
            blkid # Identify a device with a partition of type iso9660
            # Umount if need be
            mount -t iso9660 /dev/sdc1 /cdrom
            mount -t iso9660 /dev/sdc1 /mnt/iso # Might not be required

    10. Press ALT+F2 to return to the installation dialog. 

7. When reaching the partitioning step, choose "*Guided using LVM encrypted*". You should eventually obtain something like this:
    
    [![Picture]({filename}/images/debian_partitions.jpg)]({filename}/images/debian_partitions.jpg)

8. When reaching the "*Software Selection*", choose [only XFCE as a desktop manager](http://forums.debian.net/viewtopic.php?f=17&t=125037#p595087), in combinaison with the [print server](http://comments.gmane.org/gmane.linux.debian.user/455520) and utilities.
    
    [![Picture]({filename}/images/debian_selection.jpg)]({filename}/images/debian_selection.jpg)

9. Merely follow instructions until completion of the installation.

## Switching from stable to testing

If you need to be up-to-date, that's the right thing to do!

    :::bash
    su
    cp /etc/apt/sources.list{,.bak}
    sed -i -e 's/ \(stable\|jessie\)/ testing/ig' /etc/apt/sources.list
    apt-get update
    apt-get --download-only dist-upgrade
    apt-get dist-upgrade

[More information](http://unix.stackexchange.com/questions/90389/how-to-upgrade-debian-stable-wheezy-to-testing-jessie).

# Improve your privacy by using trustworthy DNS servers

I recommend [French Data Network's DNS servers](https://larlet.fr/david/stream/2015/10/12/): 80.67.169.12 and 80.67.169.40. Follow [this link](http://askubuntu.com/questions/627899/nameserver-127-0-1-1-in-resolv-conf-wont-go-away) to find out how to configure Ubuntu.

# Use free software

Namely Icecat or Iceweasel and Icedove as alternatives to Firefox and Thunderbird respectively.


Hope it was helpful.

# Further reading

- [Problem with AMD drivers](https://wiki.debian.org/fr/AtiHowTo). And don't forget about `arandr`!
