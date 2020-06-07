Title: Raspberry Pi: The Ultimate Guide
Date: 2016-07-10 05:11
Modified: 2018-12-15 16:00
Category: Linux
Tags: raspberry pi, linux, talk
Slug: raspberry-pi-the-ultimate-guide
Authors: Romain Pellerin
Summary: A complete tutorial about how to create a home server using a Raspberry Pi

*[Here is an old document I created a while ago. I keep it here for legacy purposes.]({filename}/extra/raspberry-pi-as-a-home-server-PDF.zip)*

I'll keep this article as short as possible (no explanation where things are self-explanatory or obvious), mainly due to the fact that the article will be quite lengthy. I'll give instructions about how to setup a Raspberry Pi as well as how to install stuff. This blog post might change in a near future.

# Goal

I decided to built a cheap home server to host websites, have a VPN server hosted in France, have a Nextcloud instance, and so forth. A Raspberry Pi, which costs less than $5 a year (electricity consumption) was the perfect solution. As I'm not that often in France, I needed to be able to operate it remotely. I also wanted plenty of storage, a SD card would not be sufficient. I had to plug a hard disk drive. Furthermore, I wanted it to be encrypted, just in case a malicious person tries to read my hard disk drive.

To sum up, few requirements, but big advantages. Let's get started!

# What I used for this tutorial

- [A Raspberry Pi v3 model B](https://www.raspberrypi.org/products/raspberry-pi-3-model-b/)
- A hard disk drive ([WD's PiDrive](http://wdlabs.wd.com/products/wd-pidrive-314gb/))
- Cables ([WD's PiDrive Cable Kit](http://wdlabs.wd.com/products/raspberry-pi-accessories/) and an Ethernet cable)
- A micro SD card ([Samsung's microSDHC 16GB EVO Memory Card with Adapter](http://www.samsung.com/us/computer/memory-storage/MB-MP16DA/AM))
- A TV with a HDMI cable
- A laptop  with a card reader running GNU/Linux (Xubuntu to be precise)
- A wired keyboard
- A regular DSL Internet connection

# Setting up the Raspberry Pi

## The OS

1. Download Raspbian Lite from [the official website](https://www.raspberrypi.org/downloads/raspbian/). It might be a good idea to verify the hash (SHA1).
2. Extract the file .img from the zip.
3. Run one of the following commands:
    
        :::bash
        df
        lsblk
        blkid
        fdisk -l

4. Plug in the SD card (on a regular computer, not the Raspberry Pi)
5. Redo step 3. in order to identify the SD card. `/dev/mmcblk0` or `/dev/sdb` for instance.  `/dev/mmcblk0pX` or `/dev/sdbX` would be a partition on the device, with X an integer.
6. Unmount **all** the partitions and copy Raspbian on the whole SD card:

        :::bash
        umount /dev/mmcblk0p1
        sudo dd bs=4M if=2014-09-09-wheezy-raspbian.img of=/dev/mmcblk0 status=progress conv=fsync
        sudo sync && sync

    `bs=1M` can be used in case of error, it's slower but safer. There won't be any feedback during `dd` so wait till it finishes (might be long).

7. You're done. For more information, see the [official website](http://www.raspberrypi.org/documentation/installation/installing-images/linux.md). Now [enable SSH](https://www.raspberrypi.org/blog/a-security-update-for-raspbian-pixel/) by creating an empty file named `ssh` in `/boot`. Then, unmount the SD card and eject it. 

## First boot

1. Insert the micro SD card in the Raspberry Pi. Plug in an Ethernet wire or make sure you have a Wifi access point available on which you can connect, Plug in the HDMI cable (connected to a screen), a keyboard and eventually the power cable.
2. Log in (user name is *pi* and password is *raspberry*). You can do it using a keyboard or over SSH if you Raspberry is connected. Then set the locales and the right keyboard layout:

        :::bash
        sudo su
        dpkg-reconfigure locales # Select with space bar, at least en_US.UTF-8 and fr_FR.UTF-8 plus any other you need
        dpkg-reconfigure keyboard-configuration # A keyboard must be plugged in
        dpkg-reconfigure tzdata

    Regarding the keyboard layout, there are [other alternatives](http://raspberrypi.stackexchange.com/questions/10060/raspbian-keyboard-layout/10103#10103). Then reboot (`sudo reboot`).

## Configuration

1. Should you need to connect over Wifi, here's how to do it:

        :::bash
        sudo iwlist wlan0 scan
        sudo nano /etc/wpa_supplicant/wpa_supplicant.conf

    Go to the bottom of the file and add the following:

        :::text
        network={
            ssid="The_ESSID_from_earlier"
            psk="Your_wifi_password"
        }
       
    Otherwise, disable Wifi (and Bluetooth) as it draws power. Create `/etc/modprobe.d/disable_rpi3_wifi_bt.conf` and add the following:
    
        :::text
        ##wifi
        blacklist brcmfmac
        blacklist brcmutil
        ##bt
        blacklist btbcm
        blacklist hci_uart

    Save and reboot. [More information](https://www.raspberrypi.org/documentation/configuration/wireless/wireless-cli.md). At next reboot, make sure you're either connected or that Wifi is disabled by typing `ip a`.

2. Double the current limit if your intend to plug USB devices. In `/boot/config.txt`, add the following:

        :::text
        max_usb_current=1

    [Some people say this has no effect on Raspberry Pi 3](https://www.raspberrypi.org/forums/viewtopic.php?p=930695#p930695).  
    You may finish editing this file by fine-tuning its parameters.

3. Update you Pi:

        :::bash
        sudo apt update
        sudo apt upgrade
        # sudo apt install rpi-update # if this packet is missing
        # sudo reboot
        # sudo rpi-update # gets the latest firmware, normal users should not have to do it
        # More info, read: https://github.com/Hexxeh/rpi-update#notes
        # sudo reboot
        # sudo apt update
        # sudo apt upgrade

4. For security concerns, let's create a new user. The *pi* user will be deleted later.

        :::bash
        sudo su
        passwd # Add a password to the root account
        adduser pipi
        usermod -a -G video pipi # Otherwise omxplayer won't work with this user
        echo 'export HISTSIZE=100000' >> /root/.bashrc
        echo 'export HISTFILESIZE=100000' >> /root/.bashrc
        exit
        # Stay connected as 'pi' for now

5. Config the Pi:

        :::bash
        sudo raspi-config

    You should not expand the filesystem, neither change the user password cause we'll delete the *pi* user anyway. However, you should change the boot options (select choice B2) and the internationalisation options to English (UTF-8) (same for the environment locale, do not use C.UTF-8). If you want to change the timezone and keyboard layout, plug in a keyboard on the Pi and do it from that keyboard. Change overscan if you see black bars. Change the hostname if you want. Finally, adjust memory split (128MB is sufficient for 1080p videos). Then, reboot. And log in back using the *pi* user.

5. You should probably remove the ability to run "root" commands without typing pi’s password.

        :::bash
        sudo visudo # it will safely edit /etc/sudoers

    Here, remove "NOPASSWD: ". If there is no such a line, check out the file `/etc/sudoers.d/010_pi-nopasswd` and delete it.

6. From another computer (not your Pi), do this:

        :::bash
        ssh-keygen # Use default choices
        ssh-copy-id -i $HOME/.ssh/id_rsa.pub pipi@<raspberry-pi-IP>

7. Get back to your Pi, still logged in as *pi* and do:

        :::bash
        su
        apt install rsync
        # rsync is just better than cp, we'll need it later

8. Now, it's time to delete the *pi* user. Log in as your new user (in my case it's *pipi*) and then:

        :::bash
        su # Type the root password here
        deluser --remove-home pi 
        visudo # Make sure there's no reference to pi user

## Moving `/root` onto an external hard disk drive, not encrypted, with additional encrypted DATA partition

**NOTE THAT RECENT RASPBERRY PIS CAN BOOT DIRECTLY FROM A USB MASS STORAGE DEVICE, WITH NO SD INSERTED**: [tutorial here](https://www.raspberrypi.org/documentation/hardware/raspberrypi/bootmodes/msd.md).

*Right after this section you will find another one about how to do a similar operation using an encrypted hard disk.*

As root,

    :::bash
    lsblk # Identify the hard disk drive connected to the Pi
    fdisk /dev/sda

Do the following sequence of keystrokes:

    :::text
    d # Delete all the existing partitions if many
    n # Create a new one
    p # Primary
    1
    <Enter> # Hit enter key
    +100G # 100GB, or something else
    n # Create a second partition, swap
    p
    2
    <Enter>
    +2G
    t # Change the type...
    2 # Of the second partion
    82 # Make it a swap partition
    n # Create the data partition
    p
    3
    <Enter>
    <Enter>
    p # Make sure everything is OK

Then:

    :::bash
    mkfs.ext4 /dev/sda1 -L ROOT -m 5
    mkswap /dev/sda2
    mkfs.ext4 /dev/sda3 -L DATA -m 5
    apt install cryptsetup
    cryptsetup --verify-passphrase -c aes-xts-plain64 -s 512 -h sha256 luksFormat /dev/sda3
    cryptsetup -v luksOpen /dev/sda3 hddcrypt
    mkfs.ext4 /dev/mapper/hddcrypt -L DATA -m 1
    mkdir /mnt/data_partition
    mount /dev/mapper/hddcrypt /mnt/data_partition/

    cat /boot/cmdline.txt # See if you can find root=/dev/mmcblk0p2. If yes then...
    sed -e "s|root=/dev/mmcblk0p2|root=/dev/sda1|" -i /boot/cmdline.txt
    # Otherwise, manually replace root=PARTUUID=123 with the right PARTUUID found in `blkid`
    # Add 'bootwait' at the end of the line, in the same file
    # The 'rootwait' option forces the kernel to wait until the root device becomes ready

Edit `/etc/fstab` that way:

    :::text
    proc            /proc           proc    defaults          0       0
    /dev/mmcblk0p1  /boot           vfat    defaults          0       2
    /dev/sda1       /               ext4    defaults,noatime  0       1
    /dev/sda2       none            swap    sw                0       0

Then:

    :::bash
    mkdir /tmp/rootsd && mount /dev/mmcblk0p2 /tmp/rootsd
    mkdir /tmp/roothdd && mount /dev/sda1 /tmp/roothdd
    rsync -a /tmp/rootsd/ /tmp/roothdd/
    sync
    e2fsck -f /dev/sda1 # You might need to umount it first
    reboot
    update-rc.d -f dphys-swapfile remove
    apt remove dphys-swapfile
    cat /proc/swaps
    swapoff /var/swap # Might fail if it was not listed in the previous command
    sudo rm -f /var/swap
    reboot
    su
    /sbin/swapon -s # Make sure only your swap partition is in use
    dd if=/dev/urandom of=/dev/mmcblk0p2 bs=10M # Delete unused old root partition
    fdisk /dev/mmcblk0 # Delete second partition

## Moving `/root` onto an external hard disk drive, encrypted

I would like to thank a few websites which helped me a lot to write this article:

* [Using an Encrypted Root Partition with Raspbian](http://paxswill.com/blog/2013/11/04/encrypted-raspberry-pi/)
* [Chiffrement d'un Raspberry Pi avec Raspbian et Luks/Cryptsetup](http://chezmanu.eu/RPI-Chiffrement.php)
* [Raspberry Pi Disk Encryption](http://docs.kali.org/kali-dojo/04-raspberry-pi-with-luks-disk-encryption)

Stay logged in as *root*.

    :::bash
    echo "initramfs initramfs.gz 0x00f00000" >> /boot/config.txt
    cat /boot/config.txt

    cat /boot/cmdline.txt
    sed -e "s|root=/dev/mmcblk0p2|root=/dev/mapper/hddcrypt cryptdevice=/dev/sda1:hddcrypt|" -i /boot/cmdline.txt
    cat /boot/cmdline.txt # Check it has been effectively changed
    
    sed -e "s|/dev/mmcblk0p2|/dev/mapper/hddcrypt|" -i /etc/fstab
    cat /etc/fstab # Make sure it's all right
    echo -e "hddcrypt\t/dev/sda1\tnone\tluks" >> /etc/crypttab
    cat /etc/crypttab

    lsblk # Identify the hard disk drive connected to the Pi
    fdisk /dev/sda # Delete any existing partition, create new one with default choices
    apt install cryptsetup
    cryptsetup --verify-passphrase -c aes-xts-plain64 -s 512 -h sha256 luksFormat /dev/sda1
    # 512-bit AES encryption with 256-bit SHA hashing algorithm
    cryptsetup -v luksOpen /dev/sda1 hddcrypt
    mkfs.ext4 /dev/mapper/hddcrypt -L ROOT_HDD -m 1

    # Let's mount in two directories the unencrypted root partition from the SD card
    # and the new encrypted root partition from the HDD
    mkdir /tmp/rootplain && mount /dev/mmcblk0p2 /tmp/rootplain/
    mkdir /tmp/rootcrypt && mount /dev/mapper/hddcrypt /tmp/rootcrypt/
    rsync -a /tmp/rootplain/ /tmp/rootcrypt/ # Copy from plain to encrypted
    sync

    mkinitramfs -o /boot/initramfs.gz $(uname -r)
    reboot

    # Delete old /root
    su 
    dd if=/dev/urandom of=/dev/mmcblk0p2 bs=10M status=progress conv=fsync
    fdisk /dev/mmcblk0 # Delete second partition

### Enable remote unlocking

References:

- [Remote unlocking LUKS encrypted LVM using Dropbear SSH in Ubuntu Server 14.04.1 (with Static IP)](https://stinkyparkia.wordpress.com/2014/10/14/remote-unlocking-luks-encrypted-lvm-using-dropbear-ssh-in-ubuntu-server-14-04-1-with-static-ipst/)
- [Remotely Unlocking Encrypted Servers with Dropbear (on Ubuntu)](https://chicagolug.org/news/2015-10-09-remotely-unlock-encrypted-server-with-dropbear.html)

Let's get started! On your Pi:

    :::bash
    su
    apt install dropbear
    mkinitramfs -o /boot/initramfs.gz $(uname -r) # Triggers SSH key generation
    
    # Next command is really important, it gives the Pi enough time to get an IP address
    sed "s/configure_networking\ \&/echo \"Waiting 5secs...\"\nsleep\ 5\nconfigure_networking\/" -i /usr/share/initramfs-tools/scripts/init-premount/dropbear
    cat /usr/share/initramfs-tools/scripts/init-premount/dropbear

    cd /etc/dropbear/
    rm /etc/initramfs-tools/etc/dropbear/dropbear_dss_host_key
    rm /etc/initramfs-tools/etc/dropbear/dropbear_rsa_host_key
    # Next command improves security. Default length is 1024
    dropbearkey -t rsa -s 4096 -f /etc/initramfs-tools/etc/dropbear/dropbear_rsa_host_key


Now, add the following at the beginning of the first line of the file `/etc/initramfs-tools/root/.ssh/authorized_keys`:

    :::text
    command="/scripts/local-top/cryptroot && kill -9 `ps | grep -m 1 'cryptroot' | cut -d ' ' -f 3`"

To forbid authentication using passwords and to change the listening port used by Dropbear, create the file `/etc/initramfs-tools/conf.d/dropbear` and add:

    :::text
    export PKGOPTION_dropbear_OPTION="-s -p 1234"

And do:

    :::bash
    mkinitramfs -o /boot/initramfs.gz $(uname -r) # Might be useless
    update-initramfs -u

From now, you have 3 possibilities to access your Raspberry Pi remotely:

1. Using a public key
2. Using the Raspberry's private key
3. Using an account's password

The two first possibilities are quite safe, compared to the third one. It's well known that authentication using passwords over SSH is not that safe.  
Using your PC's SSH public key is a good solution but not reliable in the long term. Imagine you were to lose access to your personal computer, you would end up locked out of your Raspberry with no means to access it. On the other hand, using your Raspberry Pi's SSH private as an identity is far more convenient in that you can store that file on many devices. Should you lose you computer, you could still access your Raspberry from another computer, given that you still have access to that private key. I know it's not the safest solution but that's the one I chose to go with.

So here are how to do it with those two first choices, but I recommend you to go with the second one.

#### Choice 1

    :::bash
    echo $(cat /home/pipi/.ssh/authorized_keys) >> /etc/initramfs-tools/root/.ssh/authorized_keys # Will grant us direct SSH access
    cat /etc/initramfs-tools/root/.ssh/authorized_keys # Make sure it's all right

#### Choice 2

Save the content of `/etc/initramfs-tools/root/.ssh/id_rsa` on your computer. **Make sure to copy the entire file, including first and last lines.** And do that on the newly created file, on your computer:

    :::bash
    chmod 0600 id_rsa_rpi

Now, you can connect to your Pi like this:

    :::bash
    ssh -i id_rsa_rpi root@192.168.1.XX -v -p 1234

<br />

Finally, last step:

    :::bash
    update-rc.d dropbear disable # Only openssh will be used after partition is decrypted
    mkinitramfs -o /boot/initramfs.gz $(uname -r) # Might be useless
    update-initramfs -u
    reboot

For more information in this whole thing, read the official documentation provided by cryptsetup:

    :::bash
    zcat /usr/share/doc/cryptsetup/README.remote.gz

Should you need to upgrade your firmware, make sure to update the right initramfs before rebooting. Read the end of [this article](http://chezmanu.eu/RPI-Chiffrement.php) to find out how to do it.

Instead of unlocking your hard disk drive over SSH, if you prefer to use a keyfile, [check out this website](http://longsteve.com/wiki/index.php?title=USB_Hard_Drive_Encryption_on_a_Raspberry_Pi).

If you are brave enough, have a look at the section below called *Hardening security*. You might want to improve Dropbear settings.

You might also want to read about [Mozilla's recommandation in terms of SSH security](https://wiki.mozilla.org/Security/Guidelines/OpenSSH).

# Tips

## Screen blank time

To avoid screen blanking after a while in the console (tty), change `BLANK_TIME` to 0 in /etc/kbd/config.

## Auto login

As previously seen, the tool `rasp-config` allows you to configure auto login. But you can also do this with the command line. In */etc/inittab*, replace

    :::text
    1:2345:respawn:/sbin/getty 115200 tty1

with

    :::text
    1:2345:respawn:/bin/login -f pi tty1 </dev/tty1 >/dev/tty1 2>&1

## Stopping Raspberry-Pi from auto changing source on TV

    :::bash
    sudo echo "hdmi_ignore_cec_init=1" >> /boot/config.txt

## Auto-mount an external hard disk drive

    :::bash
    mkdir /home/pi/my_wonderful_hdd # Where the HDD will be mounted
    sudo fdisk -l # Note the location of the HDD (something like /dev/sda1)
    sudo mount -t auto /dev/sda1 /home/pi/my_wonderful_hdd # Mount it now
    sudo echo "/dev/sda1 /home/pi/my_wonderful_hdd auto noatime 0 0" >> /etc/fstab # Auto mount at boot

## Change hostname

Use the tool `rasp-config` or edit both */etc/hostname* and */etc/hosts*.

## Disable SWAP permanently

Swapping is bad for your SD card lifespan. You should disable it permanently. You might however want to keep swap on a partition on a hard disk drive (see above). To disable permatently on SD card and disk, run:

    :::bash
    sudo swapoff --all # Temporary, disables also dedicated partitions (like /dev/sda2)
    sudo update-rc.d dphys-swapfile remove
    sudo apt remove dphys-swapfile # Permanently
    sudo rm /var/swap

# DynHost

1. `apt install lynx` (as root)
2. Download [https://github.com/rpellerin/dotfiles](https://github.com/rpellerin/dotfiles).
3. Copy in your user's home folder the directory *scripts/DynHost*.
4. Edit the lines `HOST`, `LOGIN`, `PASSWORD` and `PATH_APP` in the file *dynhost*.
5. Add a cronjob (`crontab -e`), on your Pi, with the same user (not root!):

        :::bash
        */1 * * * * /home/pi/DynHost/dynhost

    All good!

# Send email automatically on startup with a Python script (DEPRECATED: not recommended, instead use `sendmail`)

1. Download [https://github.com/rpellerin/python-mailer](https://github.com/rpellerin/python-mailer). The instructions given right below are the same as those you'll find at this URL.
2. Install Python 3 (see instructions in [https://github.com/rpellerin/dotfiles/blob/master/README.md](https://github.com/rpellerin/dotfiles/blob/master/README.md) for the latest version or simply do `apt install python3` as root).
3. Do, as a normal user (not root!):

        :::bash
        mv /path-to-python-mailer/scripts/script-pc-turned-on.py /path-to-python-mailer/script-pc-turned-on.py
        echo '#!/bin/sh' > $HOME/sendEmailOnStartup.sh
        echo "python3 /path-to-python-mailer/script-pc-turned-on.py" >> $HOME/sendEmailOnStartup.sh

3. Edit, in *script-pc-turned-on.py*, the path to the file containing recipients.
4. Create that file and add email addresses in the following format:

        :::text
        John Doe,john@doe.com

5. Edit the file *config.py*.
6. Do:

        :::bash
        chmod +x $HOME/sendEmailOnStartup.sh

7. Try to execute that file, as a normal user (not root!), to make sure everything is all right:

        :::bash
        ./sendEmailOnStartup.sh

8. If OK, add the following in */etc/rc.local*, right above `exit 0`:

        :::text
        su - pi -c /home/pi/sendEmailOnStartup.sh &
        sleep 5
        exit 0

    Don't forget to change the path if need be. You user might not be *pi*. Reboot to make sure it worked.

# Send email automatically on startup with `sendmail`

Add the following in `crontab -e` as root:

    :::bash
    @reboot /bin/sleep 30; /usr/sbin/exim -qff; echo "So you know... ($(date))" | mail -s "Rpi turned on" me@domain

Now, read the section right below.

# Configure a local SMTP email server

Before doing this. make sure you did set the hostname of your Raspberry Pi through `raspi-config`. Note: to successfully send email, the domain you set must exist as a valid DNS entry. Otherwise some email servers will reject your emails. If your Raspberry won't answer to no domain, let as is but make sure while setting up exim4 to hide local mail name with **an existing domain**.

Make sure to disable `/var/log` from being in RAM since Exim4 needs `/var/log/exim4/mainlog` to exist, even after a reboot. 

This is pretty convient as some programs still prefer to send emails, such as `cron`.  
Normally, Exim4 comes pre-installed with Debian. If not, do `apt install exim4`. Then:

    :::bash
    su
    dpkg-reconfigure exim4-config
    # "mail sent by smarthost; no local mail"
    # System mail name: keep default; must be a valid FQDN though (ending with .com for example). Leaving blank is the same as reusing the same hostname you set for the Raspberry but it is sometimes buggy. Better to explicitely write your hostname.
    # IP-addresses to listen on: keep default, we don't want to receive external emails
    # Other destinations: leave blank
    # Machines to relay mail for: leave blank
    # IP address or host name of the outgoing smarthost: your SMTP server with port, like ssl0.ovh.net::465
    # Hide local mail name: no, or yes if you let 'System mail name' blank. If you set a domain, it MUST EXIST otherwise some server will reject your emails.
    # Keep number of DNS-queries minimal: no
    # Delivery method for local mail: mbox
    # Split configuration into small files: no
    # Root and postmaster mail recipient: write one of your email addresses or leave blank

Now `update-exim4.conf`.

Then, `cat /etc/mailname` and make sure the system mail name you just specified is correctly reported here.

In */etc/exim4/passwd.client*, add you credentials, like:

    :::text
    ssl0.ovh.net:me@mydomain.com:password

If your SMTP server uses port 465 with SSL, you'll need to edit */etc/exim4/exim4.conf.template*. Add the following line, after `driver = smtp`, under `remote_smtp_smarthost`:

    :::text
    protocol = smtps

[More information](http://www.gossamer-threads.com/lists/exim/users/96817).

Now add these lines in */etc/aliases* (changes the lines according to your needs):

    :::text
    root: user1
    user1: address1@domain
    user2: address2@domain

Any email intended for user1 will be sent to the corresponding email address. **Do not add addresses using the same domain you chose while configuring exim4 as the emails won't be sent out.**  
You can also edit */etc/email-addresses*: this file contains addresses used for *from*, *reply-to* and *sender addresses* fields.

Then:

    :::bash
    newaliases # To apply changes brought to aliases
    update-exim4.conf
    # /etc/init.d/exim4 restart
    systemctl restart exim4
    echo "This is a test." | mail -s "test message" anotherme@somewhere.com # Try sending an email
    echo "This is a test." | mail -s "test message for root" root # Try sending an email
    runq; exim -qff # Flush all email queues

[More information](http://debian-facile.org/doc:reseau:exim4:redirection-mails-locaux).

# Send emails automatically on shell login

Edit your user and root's `.bashrc` and add at the end of the file:

    :::bash
    echo "So you know... ($(date))" | mail -s "Root shell login" me@domain

# Installing Nextcloud 15.0.0

Official tutorial: [https://docs.nextcloud.com/server/15/admin_manual/installation/source_installation.html](https://docs.nextcloud.com/server/15/admin_manual/installation/source_installation.html).

    :::bash
    su
    apt install apache2
    apt install mariadb-server
    apt install libapache2-mod-php7.0 php7.0
    apt install php7.0-gd php7.0-json php7.0-mysql php7.0-curl php7.0-mbstring
    apt install php7.0-intl php7.0-mcrypt php-imagick php7.0-xml php7.0-zip
    systemctl restart apache2

    cd /var/www
    mkdir nextcloud
    wget https://download.nextcloud.com/server/releases/nextcloud-15.0.0.zip
    sha256sum -c <(wget -q https://download.nextcloud.com/server/releases/nextcloud-15.0.0.zip.sha256 -O -) < nextcloud-15.0.0.zip
    unzip nextcloud-15.0.0.zip
    chown -R www-data:www-data /var/www/nextcloud/

    mysql -u root -p # No password is required, just hit enter
    CREATE USER 'nextclouduser'@'localhost' IDENTIFIED BY 'Password';
    create database nextcloud;
    GRANT ALL ON nextcloud.* TO 'nextclouduser'@'localhost';
    flush privileges;
    exit;

    mysql -u nextclouduser -p # Make sure it worked
    mysql_secure_installation

    a2enmod ssl
    a2ensite default-ssl # Enable HTTPS website

    apachectl -M # Check modules enabled
    # Enable the following if not already done
    a2enmod rewrite
    a2enmod headers
    a2enmod env
    a2enmod dir
    a2enmod mime
    systemctl restart apache2
    systemctl disable apache2 # No auto start on boot

Now edit `/etc/apache2/sites-enabled/000-default.conf`. It must contain the following (note that the redirection to https can be automatically added by Let's Encrypt, see farther below):

    :::text
    Alias /nextcloud "/var/www/nextcloud/"
    <Directory "/var/www/nextcloud">
    Options +FollowSymLinks
    AllowOverride All

    <IfModule mod_dav.c>
            Dav off
    </IfModule>

    SetEnv HOME /var/www/nextcloud
    SetEnv HTTP_HOME /var/www/nextcloud
    </Directory>

    <Directory "/mnt/data_partition/">
    # just in case if .htaccess gets disabled
        Require all denied
    </Directory>
    
    <VirtualHost *:80>
            ServerName cloud.romainpellerin.eu
            ServerAdmin romain@romainpellerin.eu
            DocumentRoot /var/www/nextcloud
            
            RewriteEngine on
            RewriteCond %{HTTPS} off
            RewriteRule ^(.*)$ https://%{HTTP_HOST}$1 [END,QSA,R=permanent]
    
            ErrorLog ${APACHE_LOG_DIR}/error.log
            CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>

Bring the changes between `<VirtualHost>` tags to */etc/apache2/sites-enables/default-ssl.conf*, except for the instruction `Redirect`. Additionally, add instructions taken from [Mozilla SSL Configuration Generator](https://mozilla.github.io/server-side-tls/ssl-config-generator/?server=apache-2.4.25&openssl=1.1.0j&hsts=yes&profile=modern):

    :::text
    <VirtualHost *:443>
        ...
        SSLEngine on

        # HSTS (mod_headers is required) (15768000 seconds = 6 months)
        Header always set Strict-Transport-Security "max-age=15768000"
        ...
    </VirtualHost>

    # modern configuration, tweak to your needs
    SSLProtocol             all -SSLv3 -TLSv1 -TLSv1.1
    SSLCipherSuite          ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA256
    SSLHonorCipherOrder     on
    SSLCompression          off
    SSLSessionTickets       off

    # OCSP Stapling, only in httpd 2.3.3 and later
    SSLUseStapling          on
    SSLStaplingResponderTimeout 5
    SSLStaplingReturnResponderErrors off
    SSLStaplingCache        shmcb:/var/run/ocsp(128000)

To enable HTTP2, do `a2enmod http2` and edit `default-ssl.conf` again:
    
    :::text
    <VirtualHost *:443>
        ...
        ProtocolsHonorOrder On
        Protocols h2 h2c http/1.1
        H2Direct on
        ...

[Note that Apache prefork is not compatible with HTTP2](https://http2.pro/doc/Apache). You'll have to [use fpm](https://blog.feutl.com/nextcloud-http2/). See [Nextcloud's instructions also](https://docs.nextcloud.com/server/15/admin_manual/installation/source_installation.html#php-ini-configuration-notes).

Let us now improve a bit Apache's security. Edit */etc/apache2/conf-enabled/security.conf* like this:

    :::text
    ServerSignature Off
    Header set X-Frame-Options: "sameorigin" # Require mod_headers
    ServerTokens Prod
    
Now, visit http://raspberry-pi-IP/nextcloud once. This will create `/var/www/nextcloud/config/config.php`. Edit this file like this:

    :::bash
    'overwrite.cli.url' => 'https://example.org/',
    'htaccess.RewriteBase' => '/',

Now edit `/etc/apache2/sites-enable/000-default.conf` like this:

    :::bash
    Alias / "/var/www/nextcloud/"

And restart `systemctl restart apache2`. You should now be able to visit http://raspberry-pi-IP/.

Now get a browser-trusted certificate from [Let's Encrypt](https://letsencrypt.org/):

    :::bash
    su
    apt install dirmngr
    apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 7638D0442B90D010
    apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 8B48AD6246925553
    echo "deb http://ftp.debian.org/debian stretch-backports main" > /etc/apt/sources.list.d/backports.list
    apt update
    apt install python-certbot-apache -t stretch-backports
    # Make sure your website is accessible from the Internet.
    # Set up NAT/PAT rules in your router. Then:
    certbot --apache
    certbot renew --dry-run # Try renewal
    # If successful, create a cronjob to be run twice a day
    32 4/12 * * * certbot renew --quiet


Ultimately, verify everything is all right using [SSL LABS's SSL server test](https://www.ssllabs.com/ssltest/).

You may now restart Apache2.

Then:

    :::bash
    mkdir /mnt/data_partition/nextcloud_data
    chown -R www-data:www-data /mnt/data_partition/nextcloud_data/
    chmod 750 /mnt/data_partition/nextcloud_data

Now you can start setting up Nextcloud at https://raspberry-pi-IP/.

## Improve PHP's performance

    :::bash
    su
    sed "s/;opcache.enable=0/opcache.enable=1/" \
        -i /etc/php/7.0/apache2/php.ini
    sed "s/;opcache.enable_cli=0/opcache.enable_cli=1/" \
        -i /etc/php/7.0/apache2/php.ini
    sed "s/;opcache.revalidate_freq=2/opcache.revalidate_freq=240/" \
        -i /etc/php/7.0/apache2/php.ini
    sed "s/memory_limit = 128M/memory_limit = 512M/" \
        -i /etc/php/7.0/apache2/php.ini
    sed "s/upload_max_filesize = 2M/upload_max_filesize = 16G/" \
        -i /etc/php/7.0/apache2/php.ini
    sed "s/post_max_size = 8M/post_max_size = 16G/" \
        -i /etc/php/7.0/apache2/php.ini
    sed "s/output_buffering = 4096/output_buffering = 0/" \
        -i /etc/php/7.0/apache2/php.ini
    sed "s/max_input_time = 60/max_input_time = 3600/" \
        -i /etc/php/7.0/apache2/php.ini
    sed "s/max_execution_time = 30/max_execution_time = 3600/" \
        -i /etc/php/7.0/apache2/php.ini

    # /etc/php/7.0/cli/php.ini is used by Nextcloud's CRON jobs
    sed "s/;opcache.enable=0/opcache.enable=1/" \
        -i /etc/php/7.0/cli/php.ini
    sed "s/;opcache.enable_cli=0/opcache.enable_cli=1/" \
        -i /etc/php/7.0/cli/php.ini
    sed "s/;opcache.revalidate_freq=2/opcache.revalidate_freq=240/" \
        -i /etc/php/7.0/cli/php.ini
    sed "s/output_buffering = 4096/output_buffering = 0/" \
        -i /etc/php/7.0/cli/php.ini
    sed "s/max_input_time = 60/max_input_time = 3600/" \
        -i /etc/php/7.0/cli/php.ini
    sed "s/max_execution_time = 30/max_execution_time = 3600/" \
        -i /etc/php/7.0/cli/php.ini

To significantly improve performance overall, you'll also need data caching: **APCu**.

    :::bash
    su
    apt install php7.0-apcu
    phpenmod apcu
    #echo "apc.enabled=1" >> /etc/php/7.0/cli/conf.d/20-apcu.ini # Normally it's useless, check Nextcloud error logs in the admin page to make sure it's working
    service apache2 restart

Now, add the following in */var/www/nextcloud/config/config.php*:

    :::text
    'memcache.local' => '\OC\Memcache\APCu',

Restart Apache. Running `php -i` will say *opcache.enable => On* and *Opcode Caching => Disabled*. That's normal as we didn't enable opcaching for CLI, (*/etc/php/7.0/cli/php.ini*), only for Apache2. However, if you create a webpage containing `<?php phpinfo();`, when accessing this page you'll see that's it's *Up and Running*. Make sure as well that APCu is enabled.

## Improve Nextcloud's settings

Add the following in */var/www/nextcloud/config/config/php*:

    :::text
    'logtimezone' => 'Europe/Paris',

In Nextcloud, enable the server-side encryption in the admin settings, and enable the app called *Encryption* in the web interface, while logged in as an admin. You'll need to log out and in to actually enable encryption for good.

Also, do:

    :::bash
    su
    cd /var/www/nextcould
    sudo -u www-data php occ maintenance:update:htaccess

It will allow Nextcloud to change these settings directly from the Admin webpage. It will also update some settings.

In the admin page (`URL/settings/admin`), increase the maximum upload size to 1.9 GB (it's a limitatiom from 32-bit PHP - Rasbian is a 32 bit OS).

In `URL/settings/admin/overview` you might get some warnings about PHP not being properly configured. Edit `/etc/php/7.0/apache2/php.ini` as it asks.

Install and enable the app "Two Factor TOTP Provider" in `URL/settings/apps`. Then, go to `URL/settings/user/security` and enable TOTP.

# OpenVPN 2.4.0

    :::bash
    su
    apt update && apt install openvpn

Read the [official documentation](https://openvpn.net/community-resources/how-to/) ([here, short tutorial for easy-rsa3](https://community.openvpn.net/openvpn/wiki/EasyRSA3-OpenVPN-Howto)). Download [easy-rsa](https://github.com/OpenVPN/easy-rsa) and checkout latest release.

    :::bash
    su
    cd /etc/openvpn/
    git clone https://github.com/OpenVPN/easy-rsa.git
    cd easy-rsa
    git checkout v3.0.5
    cd easyrsa3
    cp vars.example vars
    echo "set_var EASYRSA_KEY_SIZE       2048" >> vars # No need to source this file
    # Edit also KEY_COUNTRY, KEY_PROVINCE, KEY_CITY, KEY_ORG, and KEY_EMAIL

From now on, I highly recommend you read */etc/openvpn/easy-rsa/doc/EasyRSA-Readme.md* and [https://github.com/OpenVPN/easy-rsa/blob/master/README.quickstart.md](https://github.com/OpenVPN/easy-rsa/blob/master/README.quickstart.md) in order to continue setting up OpenVPN. As explained, you need to create a PKI to get three distinct things: your CA, a certificate and private key for the server and another couple of this kind for clients. Normally you should generate the pair for clients on your personnal computer, however it's not necessary in our case (who cares about security anyway?).

Then:

    :::bash
    ./easyrsa init-pki
    ./easyrsa build-ca # Add a strong password which will be used to sign other certificates
    ./easyrsa gen-req server nopass # Do not add a password for the server; use 'server' as CN
    ./easyrsa sign-req server server
    ./easyrsa gen-req client # Use 'client' as CN; add a passphrase that you will need later when you try to connect to your VPN server
    ./easyrsa sign-req client client

Once the certificates and private keys are generated for the server and a client at least, do the following on your Pi and then go back to reading OpenVPN's documentation:

    :::bash
    ./easyrsa gen-dh
    cp /usr/share/doc/openvpn/examples/sample-config-files/server.conf.gz /etc/openvpn/
    cp /usr/share/doc/openvpn/examples/sample-config-files/client.conf /etc/openvpn/
    cd /etc/openvpn
    openvpn --genkey --secret ta.key
    gunzip server.conf.gz
    vim server.conf

You should edit *server.conf* like this:

    :::text
    port 1194
    proto udp
    dev tun
    ca /etc/openvpn/easy-rsa/easyrsa3/pki/ca.crt
    cert /etc/openvpn/easy-rsa/easyrsa3/pki/issued/server.crt
    key /etc/openvpn/easy-rsa/easyrsa3/pki/private/server.key
    dh /etc/openvpn/easy-rsa/easyrsa3/pki/dh.pem
    push "route 192.168.1.0 255.255.255.0"
    push "redirect-gateway def1 bypass-dhcp"
    push "dhcp-option DNS 208.67.222.222"
    push "dhcp-option DNS 208.67.220.220"
    tls-auth /etc/openvpn/ta.key 0
    cipher AES-256-CBC
    max-clients 2
    comp-lzo
    user nobody
    group nogroup
    log-append  openvpn.log
    verb 6
    mute 20
    script-security 3
    client-connect "/etc/openvpn/notifyconnect.sh"

Keep the rest as-is. Now create `/etc/openvpn/notifyconnect.sh`:

    :::bash
    #!/bin/bash
    echo "On `date`" | mail -s "OpenVPN client connection" root@localhost 2>/dev/null
    sleep 1
    # We can't run `runq`  or `exim -qff` since this script is called by user `nobody`.
    # Also make sure this script returns 0 otherwise the VPN connection will fail.

Then:

    :::bash
    chmod +x /etc/openvpn/notifyconnect.sh

Then add `CAP_SYS_RESOURCE` to `CapabilityBoundingSet` in `/lib/systemd/system/openvpn@.service`. Check OpenVPN logs, there might be a permission issue on `/var/log/exim4`.

Now, edit *client.conf*:

    :::text
    remote <your DynHost domain> 1194
    user nobody
    group nogroup
    mute-replay-warnings
    # Normally next lines are uncommented but we won't need them
    #ca /etc/openvpn/easy-rsa/easyrsa3/pki/ca.crt
    #cert /etc/openvpn/easy-rsa/easyrsa3/pki/issued/client.crt
    #key /etc/openvpn/easy-rsa/easyrsa3/pki/private/client.key
    #tls-auth /etc/openvpn/ta.key 1
    #ns-cert-type server # OpenVPN 2.0 and below
    remote-cert-tls server # OpenVPN 2.1 and above
    cipher AES-256-CBC
    comp-lzo
    mute 20

Ultimately do:

    :::bash
    cat /etc/openvpn/client.conf > client.ovpn
    echo "key-direction 1" >> client.ovpn
    echo "script-security 2" >> client.ovpn
    echo "up /etc/openvpn/update-resolv-conf" >> client.ovpn
    echo "down /etc/openvpn/update-resolv-conf" >> client.ovpn
    echo "<ca>" >> client.ovpn
    cat /etc/openvpn/easy-rsa/easyrsa3/pki/ca.crt >> client.ovpn
    echo "</ca>" >> client.ovpn
    echo "<cert>" >> client.ovpn
    cat /etc/openvpn/easy-rsa/easyrsa3/pki/issued/client.crt | sed -ne '/-BEGIN CERTIFICATE-/,/-END CERTIFICATE-/p' >> client.ovpn
    echo "</cert>" >> client.ovpn
    echo "<key>" >> client.ovpn
    cat /etc/openvpn/easy-rsa/easyrsa3/pki/private/client.key >> client.ovpn
    echo "</key>" >> client.ovpn
    echo "<tls-auth>" >> client.ovpn
    cat /etc/openvpn/ta.key >> client.ovpn
    echo "</tls-auth>" >> client.ovpn
    
Copy that *client.ovpn* file on your client.

On your Pi, uncomment the following line in */etc/sysctl.conf*:

    :::text
    net.ipv4.ip_forward=1

Apply changes:

    :::bash
    su
    sysctl -p

There's a [known bug](http://serverfault.com/questions/355520/after-reboot-debian-box-ignore-sysctl-conf-values) which may prevent these values to be read on boot (check that it worked about rebooting with `sysctl -a | grep ip_forward`). Add the following above `exit 0` in */etc/rc.local*:

    :::text
    sysctl -p /etc/sysctl.conf

Don't forget to set up a port forward rule to forward UDP port 1194 from your gateway/router to the machine running the OpenVPN server. In addition, allow incomings UDP connections on port 1194 and these rules:

    :::bash
    iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
    iptables -A FORWARD -s 10.8.0.0/24 -j ACCEPT
    iptables -A FORWARD -m state --state RELATED,ESTABLISHED -j ACCEPT

Finally, do the following on your server:

    :::bash
    cd /etc/openvpn
    su
    mv client.conf client.conf.old
    shred -u client.ovpn
    openvpn --config server.conf &
    tail -f openvpn.log

At next boot, the server will run automatically. We needed to rename the *client.ovpn* because the initscript will scan this directory for *.conf* files and start up a separate OpenVPN deamon for each file found. It is recommended to delete client's files:

    :::bash
    shred -u client.conf.old
    shred -u /etc/openvpn/easy-rsa/easyrsa3/pki/private/client.key
    shred -u /etc/openvpn/easy-rsa/easyrsa3/pki/issued/client.crt

Now your client:

    :::bash
    sudo apt update && sudo apt install resolvconf
    sudo openvpn --config client.ovpn

You might want to make your VPN server available on several ports. If so, open the ports you wish to use on your router and add the corresponding iptables rules, for instance:

    :::bash
    iptables -t nat -A PREROUTING -i eth0 -p udp --dport 53 -j REDIRECT --to-port 1194

# Hardening security

    :::bash
    su
    wget --no-check-certificate https://raw.githubusercontent.com/rpellerin/dotfiles/master/scripts/firewall.sh
    chmod 700 firewall.sh
    chown root:root firewall.sh
    mv firewall.sh /etc/init.d
    update-rc.d firewall.sh defaults

In the firewall, you might want to comment the line `NETWORK_MGMT=` in order to be able to log in using SSH from any LAN, including your VPN.

[Another helpful website about how to prevent SSH bruteforce attacks](http://mysecureshell.sourceforge.net/fr/securessh.html#question3). However, we'll use fail2ban, see below.

Consider allowing SSH connections using public keys only. On a client do:

    :::
    ssh-keygen -t ed25519 -o -a 100 # Accept default directory and no passphrase
    ssh-keygen -t rsa -b 4096 -o -a 100 
    ssh-copy-id -i ~/.ssh/id_rsa.pub pi@raspberrypi-IP

## Hardening SSH configuration

Edit */etc/ssh/sshd_config*:

    :::text
    Port <something you like>
    #HostKey /etc/ssh/ssh_host_dsa_key # Comment because too old
    #HostKey /etc/ssh/ssh_host_ecdsa_key # Same reason
    UsePrivilegeSeparation sandbox
    LoginGraceTime 10s
    PermitRootLogin no
    StrictModes yes
    PubkeyAuthentication yes
    IgnoreRhosts yes
    PermitEmptyPasswords no
    ChallengeResponseAuthentication no
    PasswordAuthentication no # Or yes, depending on your needs
    Banner /etc/issue.net # Message displayed at login
    UsePAM no

    # Custom settings
    AllowUsers pi # ONLY pi will be allowed to log in

    Ciphers chacha20-poly1305@openssh.com,aes256-gcm@openssh.com,aes128-gcm@openssh.com,aes256-ctr,aes192-ctr,aes128-ctr
    MACs hmac-sha2-512,hmac-sha2-256,hmac-ripemd160
    KexAlgorithms curve25519-sha256@libssh.org,diffie-hellman-group-exchange-sha256

The last three lines are taken from [this very helpful website](https://stribika.github.io/2015/01/04/secure-secure-shell.html). You should also read [this one](http://kacper.blog.redpill-linpro.com/archives/702) after having read the first one (the order is important).

## Sending an email on every SSH connection

Taken from [this thread](http://askubuntu.com/questions/179889/how-do-i-set-up-an-email-alert-when-a-ssh-login-is-successful#answer-448602). As root, create `/etc/ssh/login-notify.sh`:

    :::text
    #!/bin/sh 
     
    if [ "$PAM_TYPE" != "close_session" ]; then 
        host="`hostname`" 
        subject="SSH Login: $PAM_USER from $PAM_RHOST on $host" 
        message="`date`"
        echo "$message" | mail -s "$subject" root@mydomain
    fi

Note though that you will need to change this line in `/etc/ssh/sshd_config`:

    :::text
    UsePAM yes

Then:

    :::bash
    chmod +x /etc/ssh/login-notify.sh

And add the following line at the end of the file `/etc/pam.d/sshd`:

    :::text
    session optional pam_exec.so seteuid /etc/ssh/login-notify.sh

Now restart sshd by doing `service sshd restart`.

## Fail2Ban

[Official documentation](http://www.fail2ban.org/wiki/index.php/MANUAL_0_8).

    :::bash
    su
    apt install fail2ban
    fail2ban-client -x start # To force the server to startup. However, a reboot is preferable.

Make sure the file */etc/init.d/fail2ban* exists. Now edit */etc/fail2ban/jail.local* (make a copy of */etc/fail2ban/jail.conf*):

    :::text
    [DEFAULT]
    # Whatever fits you
    bantime = 86400
    findtime = 3600
    maxretry = 3
    ntime  = 8700
    action = %(action_mwl)s

    # Enable sshd (enabled = true) and sshd-dos, change 'port' and the 'maxretry' value if need be
    # Enable apache, apache-*
    # Add the following new entry
    [http-dos]
    enabled = true
    port = http,https
    filter = http-dos
    logpath = /var/log/apache*/*access.log
    maxretry = 360
    findtime = 120
    bantime = 600

    [ban-countries]
    enabled = true
    port = http,https
    filter = http-dos
    logpath = /var/log/apache*/*access.log
    maxretry = 1
    findtime = 120
    bantime = 6000
    banaction = ban-countries
    action = %(action_)s
    # action_ won't send email when banning someone (cause would send a message for every new request) nor when starting/stopping

Now create */etc/fail2ban/filter.d/http-dos.conf*:

    :::text
    [Definition]
    
    # Option: failregex
    # Note: This regex will match any GET or POST entry in your logs, so basically
    # all valid and not valid entries are a match.
    # You should set up in the jail.conf file, the maxretry and findtime carefully
    # in order to avoid false positives.
    
    failregex = ^<HOST> -.*\"(GET|POST).*
    
    # Option: ignoreregex
    # Notes.: regex to ignore. If this regex matches, the line is ignored.
    # Values: TEXT
    #
    
    ignoreregex =    

Now create */etc/fail2ban/action.d/ban-countries.conf*:

    :::text
    # Copied from iptables-allports.conf
    [Definition]

    actionstart = <iptables> -N f2b-<name>
                  <iptables> -A f2b-<name> -j <returntype>
                  <iptables> -I <chain> -p <protocol> -j f2b-<name>

    actionstop = <iptables> -D <chain> -p <protocol> -j f2b-<name>
                 <iptables> -F f2b-<name>
                 <iptables> -X f2b-<name>

    actioncheck = <iptables> -n -L <chain> | grep -q 'f2b-<name>[ \t]'

    actionban = IP=<ip> &&
                COUNTRY=$(geoiplookup $IP | egrep "<country_list>") && [ "$COUNTRY" ] &&
                <iptables> -I f2b-<name> 1 -s <ip> -j <blocktype> || true

    actionunban = true 
     
    [Init] 
     
    country_list = CN|China

Now install missing packages, reload the service maually to make sure there is no error:

    :::bash
    su
    apt install geoip-bin geoip-database
    systemctl stop fail2ban
    fail2ban-client -x start
    cat /var/log/fail2ban.log
    # Check for errors
    fail2ban-client -x stop
    systemctl start fail2ban

# Troubleshooting

Should you have a [beeping hard disk drive](https://www.youtube.com/watch?v=IGtzaIlMgWA), the reason might be power consumption. It usually beeps when it needs more electricity. [Disabling Advanced Power Management](http://www.htpcguides.com/spin-down-and-manage-hard-drive-power-on-raspberry-pi/) will solve this problem in most cases (`sudo hdparm -B 255 /dev/sda`).

# Going further

## Pro tips

Consider buying an [uninterruptible power supply (UPS) for your Raspberry to prevent damage caused by power outage](https://www.modmypi.com/raspberry-pi/breakout-boards/pi-modules/ups-pico).

## Talk

A year ago, I gave a talk at [HumanTalks Compiègne](http://humantalks.com/cities/compiegne) about my Raspberry Pi. Here are the video and the slides.

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/ECzGnX644yc?rel=0" frameborder="0" allowfullscreen></iframe>

<iframe width="700" height="600" src="https://romainpellerin.eu/slides/embedder.html#self-hosting/slides.html" allowfullscreen></iframe>

[Slides are available in HTML](https://romainpellerin.eu/slides/self-hosting/slides.html).

## Interesting external links

- [Piwall : Créer un mur d’écrans low cost avec des Raspberry Pi](http://www.minimachines.net/actu/piwall-creer-un-mur-decrans-low-cost-avec-des-raspberry-pi-28656)
- [Raspberry Pi – Allonger la durée de vie de vos cartes SD](http://korben.info/raspberry-pi-allonger-la-duree-de-vie-de-vos-cartes-sd.html)
- [Se passer de Dropbox en montant son coffre-fort numérique à la maison](http://linuxfr.org/news/se-passer-de-dropbox-en-montant-son-coffre-fort-numerique-a-la-maison)
- [Plus de 50 idées pour votre Raspberry Pi](http://korben.info/idees-raspberry-pi.html)
- [Piloter sa maison grâce au web](http://www.24joursdeweb.fr/2014/piloter-sa-maison-grace-au-web/)
- [PiScan: A personal shopping and inventory-tracking device based on the Raspberry Pi](https://github.com/Banrai/PiScan)
- [THE Official RASPBERRY PI PROJECTS BOOK](https://www.raspberrypi.org/magpi-issues/Projects_Book_v1.pdf)
- [Raspberry Pi Zero Headless Setup](http://davidmaitland.me/2015/12/raspberry-pi-zero-headless-setup/)
- [RASPBERRY PI GETS TURNED ON](http://hackaday.com/2016/06/28/raspberry-pi-gets-turned-on/)
- [Installation serveur HTTP(S) rapide](http://www.geek-directeur-technique.com/2016/07/19/installation-serveur-https-rapide)
- [The R.Pi IoT Shield adds IoT connectivity to your DIY project](https://techcrunch.com/2016/11/16/the-r-pi-iot-shield-adds-iot-connectivity-to-your-diy-project/)
- [Raspberry Pi 3 based home automation with NodeJS and React Native.](https://github.com/deepsyx/home-automation)
- [Using your Raspberry Pi as a DLNA/UPnP media server](https://bbrks.me/rpi-minidlna-media-server/)
- [raspberry-pi-security-camera](https://github.com/rpellerin/raspberry-pi-security-camera)
- [Protéger son VPS](https://mon-blog.jbriault.fr/index.php/blog/2019/02/06/s%C3%A9curiser-l-administration-de-son-vps)

### Read-only Raspberry Pi

- [RaspberryPi & Raspbian en lecture seul (ReadOnly) pour préserver la carte SD](http://david.mercereau.info/raspberrypi-raspbian-en-lecture-seul-readonly-pour-preserver-la-carte-sd/)
- [Tiny Core Linux](http://tinycorelinux.net/ports.html)
