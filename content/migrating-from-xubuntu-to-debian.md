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

1.  Download a "_small installation image_" on [the official website](https://www.debian.org/distrib/).
2.  Flash it on a USB stick:

        :::bash
        ls /dev/sd*
        # Plug the USB stick
        ls /dev/sd* # Detect which is yours
        sudo umount /dev/sdX1
        sudo dd if=debian-8.4.0-amd64-netinst.iso of=/dev/sdX bs=1M
        sudo sync; sync
        sudo umount /dev/sdX1

3.  On the target PC, disable any HDD password or BIOS password. It might prevent you from encrypting the disk. I experienced it.
4.  Boot on the USB flash drive. Select "_Install_", unless you prefer a graphical install. They both do the same thing.
5.  Follow the instructions. You can leave the domain name blank. If you get a message "No common CD-ROM drive was detected", press ALT+F2 and do the following:

        :::bash
        blkid # Identify a device with a partition of type iso9660
        mkdir /mnt/iso
        mount -t iso9660 /dev/sdb1 /mnt/iso

    Press ALT+F1 to return to the installation dialog. Continue the installation.

6.  If at some point a message says that proprietary firmware files are needed, it's time to go back to the other computer and USB stick. Otherwise, skip this step.

    [![Picture]({static}/images/debian_firmware.jpg)]({static}/images/debian_firmware.jpg)

    Note: this is doable with only one USB stick. But it is much harder.

    1.  Download the mentioned file(s) from the [Debian repository](https://packages.debian.org/jessie/). In my case, I needed these ones: [https://packages.debian.org/stretch/all/firmware-misc-nonfree/download](https://packages.debian.org/stretch/all/firmware-misc-nonfree/download) and [https://packages.debian.org/stretch/all/firmware-realtek/download](https://packages.debian.org/stretch/all/firmware-realtek/download).
    2.  Put the `.deb` file(s) on the other USB stick. If you decided to re-use the same USB stick, first umount it: ALT+F2, `cat /etc/mtab` then `sync && umount /dev/sdb1 && sync`. Plug it in the other computer and delete existing partitions with `fdisk` and create a new one of type `W95 FAT32`. Format it using `sudo mkfs.vfat /dev/sdc1`. Then put the files on the USB stick.
    3.  Plug the one containing firmwares files on your target computer, in the same USB port as the previous USB stick. You might need to hit ALT+F2, then check what is currently mounted (`cat /etc/mtab`) (make sure nothing on `/cdrom`) and mount the one you just plugged in: `mount -t vfat /dev/sdb1 /cdrom`. ALT+F1 to go back to the install screen.
    4.  Answer "_Yes_" to "_Load missing firmware from removable media?_".
    5.  Once completed, unplug the USB stick and plug the one containing Debian in the same port. You might need to re-write Debian if you re-used the same USB flash drive. Then press ALT+F1 and do:

            :::bash
            blkid # Identify a device with a partition of type iso9660
            # Umount if need be
            mount -t iso9660 /dev/sdc1 /cdrom
            mount -t iso9660 /dev/sdc1 /mnt/iso # Might not be required

    6.  Press ALT+F2 to return to the installation dialog.

7.  When reaching the partitioning step, choose "_Guided using LVM encrypted_". You should eventually obtain something like this:

    [![Picture]({static}/images/debian_partitions.jpg)]({static}/images/debian_partitions.jpg)

8.  When reaching the "_Software Selection_", choose [only XFCE as a desktop manager](http://forums.debian.net/viewtopic.php?f=17&t=125037#p595087), in combinaison with the [print server](http://comments.gmane.org/gmane.linux.debian.user/455520) and utilities. Do not use the Debian desktop environment. It is shitty as f\*ck.

    [![Picture]({static}/images/debian_selection.jpg)]({static}/images/debian_selection.jpg)

9.  Merely follow instructions until completion of the installation.

## Switching from stable to testing

If you need to be up-to-date, that's the right thing to do!

    :::bash
    su
    cp /etc/apt/sources.list{,.bak}
    sed -i -e 's/ \(stable\|stretch\)/ testing/ig' /etc/apt/sources.list
    apt update
    apt --download-only dist-upgrade
    apt dist-upgrade

[More information](http://unix.stackexchange.com/questions/90389/how-to-upgrade-debian-stable-wheezy-to-testing-jessie).

# Improve your privacy by using trustworthy DNS servers

I recommend [French Data Network's DNS servers](https://larlet.fr/david/stream/2015/10/12/): 80.67.169.12 and 80.67.169.40. Follow [this link](http://askubuntu.com/questions/627899/nameserver-127-0-1-1-in-resolv-conf-wont-go-away) to find out how to configure Ubuntu.

# Use free software

Namely Icecat or Iceweasel and Icedove as alternatives to Firefox and Thunderbird respectively.

Hope it was helpful.

# Further reading

- [Problem with AMD drivers](https://wiki.debian.org/fr/AtiHowTo). And don't forget about `arandr`!
