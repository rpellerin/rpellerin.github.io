Title: Yes, Privacy Matters
Date: 2016-02-04 01:40
Modified: 2016-07-03 02:55
Category: Linux
Tags: privacy, security, linux, gnu, ssh, free software, open source, ubuntu, exerbo, xubuntu
Slug: yes-privacy-matters
Authors: Romain Pellerin
Summary: Regarding privacy, get the right tools and build a powerful computer, with a Linux-based OS

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/BbkbdYoffX4?rel=0" frameborder="0" allowfullscreen></iframe>
<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/mqUInAOfBWI?rel=0" frameborder="0" allowfullscreen></iframe>

If you do care about your privacy, you'd better read what follows carefully ;). Basically, some rules of thumb to avoid common pitfalls and a few tricks to take care of your privacy as much as possible.

- [Useful IT policies](https://github.com/lfit/itpol)
- Use GNU/Linux (Ubuntu-based distro are not perfect but a good start, like Xubuntu; give [exerbo](http://exherbo.org/) a try if you're brave enough)
- Don't use binaries coming from the Internet, compile every program to the extent possible
- Forbid proprietary software as well - see alternatives below in this article
- Encrypt your whole HDD (and any other external ones) - see right below
- **DO NOT INSTALL** Facebook nor Twitter on your Android phone (or Cyanogen)

Before starting reading this article, you might be interested in this webpage, a [full documentation about how encryption works with Linux](https://wiki.archlinux.org/index.php/Disk_encryption).

# Secure your computer and encrypt (part of) your local hard disk drive

First, set all possible BIOS passwords (usually one for the administrator and one for user(s); each password will give different rights for the BIOS, for example sensitive settings will be accessible to the administrator only).

Secondly, set HDD passwords from the BIOS panel (again, one for admins, one for user, both have the same purpose and rights: they kind of unlock the HDD, allowing it to be read and written).

## Full disk encryption

Then, to encrypt your whole disk, you have 3 options:

1. Use the *Ubuntu built-in installer to encrypt the whole disk, erasing **EVERYTHING** on the disk.
2. Use the *Ubuntu built-in installer with Gparted to encrypt the whole disk, more flexible (select *something else*).
3. DIY. It allows you to [keep a dual boot installation](http://askubuntu.com/questions/293028/how-can-i-install-ubuntu-encrypted-with-luks-with-dual-boot).

I would recommend going with the 1., but if you're interested, [have a deeper look at thoses 3 options](https://thesimplecomputer.info/full-disk-encryption-with-ubuntu). Here is [another tutorial](http://www.tecmint.com/install-debian-8-with-luks-encrypted-home-var-lvm-partitions/) to do it with Debian (not a *Ubuntu disto).

In any case, here is how to write a new Xubuntu image on a USB stick:

    :::bash
    lsblk # To identify the USB stick
    sudo dd if=/home/user/Downloads/xubuntu-15.10-desktop-amd64.iso of=/dev/sdb \
        bs=1M && sudo sync

Please notice that with LUKS encryption, [your computer is still vulnerable as long as you have a boot partition unencrypted](https://twopointfouristan.wordpress.com/2011/04/17/pwning-past-whole-disk-encryption/).

## `/home` encryption (using the filesystem called eCryptfs)

Do it while installing your fresh new *Ubuntu. Otherwise, you can still [do it later](http://askubuntu.com/questions/366749/enable-disk-encryption-after-installation) using [`ecryptfs-migrate-home`](http://www.howtogeek.com/116032/how-to-encrypt-your-home-folder-after-installing-ubuntu/).

# Encrypt external HDD with `dm-crypt` and LUKS

1. Find the correct device (eg. `/dev/sdb1` as a second internal SATA-HDD) and umount it:

        :::bash
        sudo aptitude update ; sudo aptitude install cryptsetup
        sudo modprobe dm-crypt sha256 aes # Enable modules, might be already done
        lsblk
        sudo umount /dev/sdb1
        sudo dd if=/dev/urandom of=/dev/sdb bs=4K # Optional, add obfuscation

2. Create one big partition using the whole space (system must be Linux):

        :::bash
        sudo fdisk /dev/sdb

3. Encrypt the partition using LUKS:

        :::bash
        sudo cryptsetup --verify-passphrase -c aes-xts-plain64 -s 512 \
             -h sha256 luksFormat /dev/sdb1 # 512-bit AES encryption
             # with 256-bit SHA hashing algorithm

4. Create the filesystem:

        :::bash
        sudo cryptsetup luksOpen /dev/sdb1 myhdd

5. Format it and test mounting:

        :::bash
        sudo mkfs.ext4 /dev/mapper/myhdd -L <LABEL> -m 1
        # -m specifies the percentage of the filesystem blocks reserved
        # for the super-user
        mkdir /mnt/hdd
        mount /dev/mapper/myhdd /mnt/hdd
        df -H
        umount /mnt/hdd


6. Close container:

        :::bash
        sudo cryptsetup luksClose /dev/mapper/myhdd
        sudo eject /dev/sdb

7. Optional step, after disconnecting and reconnecting the device:

        :::bash
        sudo chown user:user /media/disk

You can check the partition using

    :::bash
    fsck -vy /dev/mapper/myhdd

Finally, you might want to backup the LUKS headers or add or change keys (passwords), if so look some keywords up on the Internet, like *`cryptsetup`* plus *`luksHeaderBackup`* or *`luksHeaderRestore`* or *`isLuks`* or *`luksDump`* or *`luksAddKey`* or *`luksRemoveKey`*.

## Automount encrypted HDDs with LUKS on bootup

In `/etc/crypttab`, add:

    :::text
    mycryptedhdd    UUID=00000000-0000-0000-0000-000000000000   none    luks,tries=3

You can find the UUID using `blkid /dev/sdb`. You can also directly enter the path `/dev/sdb`. *none* means there's no keyfile, you'll have to type the password. *tries* is the number or tries you have.

Then, in `/etc/fstab`, add:

    :::text
    /dev/mapper/mycryptedhdd     /mnt/mounteddirectory    ext4      defaults    0   0

*mycryptedhdd* must be the same name used as before. */mnt/mounteddirectory* is where the encrypted disk will be available. *ext4* is the filesystem used on the disk (see step 5). First 0 means the device will not be backed up by the dump utility, second 0 means the device will never be automatically checked by the `fsck` utility.

You're good!

# Encrypt what you put on Cloud Storages

This part is inspired from [this blog post](http://haridas.in/how-to-put-encrypted-contents-on-cloud-storages.html). **I highly recommend encrypting content put online, should it be on proprietary platforms such as Google Drive ou Dropbox, or even on ownCloud.**

    :::bash
    sudo apt-get install ecryptfs-utils
    sudo modprobe cryptfs # Optional
    mkdir ~/Dropbox/Encrypted # This directory will be put online; its content is encrypted
    mkdir ~/SecureDropbox # You'll put your unencrypted files here
    sudo mount -t ecryptfs ~/Dropbox/Encrypted ~/SecureDropbox
    # Choose a passphrase (which will act as a password), aes 32 bytes.
    # Disable plaintext passthrough. Filename encryption might be useful. I would enable it.

Filename encryption might require another last command to be run, if your content is shared on more than one computer:

    :::bash
    ecryptfs-add-passphrase --fnek

# Encrypt one single file

## Encryption

    :::bash
    openssl aes-256-cbc -in yourfile.txt -out file.enc
    # OR
    gpg -c filename

## Decryption

    :::bash
    openssl aes-256-cbc -d -in file.enc -out yourfile.txt
    # OR
    gpg filename.gpg

# Free alternatives to proprietary software

## Emails

- Thunderbird

## Web browser

- Mozilla Firefox

## Text editor

- Atom (<s>Sublime Text</s> is proprietary)
- Vim or Emacs

## Video editing

- [Shotcut](http://www.shotcut.org/)

## Graphics editor

- Gimp
- Inkscape (vector)


# Further reading

- [Quelle clé SSH choisir ? RSA, DSA, ou Ed25519 ?](http://blog.adminrezo.fr/2016/01/comment-choisir-sa-cle-ssh-rsa-dsa-ecdsa-ed25519/)
- [NSA - À propos de BULLRUN](http://linuxfr.org/news/nsa-a-propos-de-bullrun)
- [How to Destroy a Laptop with Top Secrets [cccamp15]](https://www.youtube.com/watch?v=PFsC1puqhA4)
- [Comment chiffrer ses emails ? (Thunderbird + GPG)](http://korben.info/comment-chiffrer-ses-emails-thunderbird-gpg.html)
- [OpenPGP Best Practices](https://help.riseup.net/en/security/message-security/openpgp/best-practices)
- [Je n'ai rien à cacher.](http://jenairienacacher.fr/)
- [Framasoft](http://framasoft.net/)
- [tmpfs](https://wiki.archlinux.org/index.php/Tmpfs) & [Accélérez votre navigateur en mettant son cache en RAM](http://korben.info/accelerez-votre-navigateur-en-mettant-son-cache-en-ram.html)
- [Why privacy matters](http://www.ted.com/talks/glenn_greenwald_why_privacy_matters)
- [Your Password is Too Damn Short](http://blog.codinghorror.com/your-password-is-too-damn-short/)
- [NSA-proof SSH](http://kacper.blog.redpill-linpro.com/archives/702)
- [Secure Secure Shell](https://stribika.github.io/2015/01/04/secure-secure-shell.html)
- [Cryptographie de comptoir](https://nonblocking.info/cryptographie-de-comptoir/)
- [Ma première (vraie) clé PGP](http://www.guiguishow.info/2014/07/17/ma-premiere-vraie-cle-pgp/)
- [Explaining public-key cryptography to non-geeks](https://medium.com/@vrypan/explaining-public-key-cryptography-to-non-geeks-f0994b3c2d5)
- [Le noob de l'autohébergement](http://zythom.blogspot.fr/2015/12/le-noob-de-lautohebergement.html)
- [Do not underestimate credentials leaks](https://github.com/ChALkeR/notes/blob/master/Do-not-underestimate-credentials-leaks.md)
- [It’s Always Sunny in Reykjavik (or) How I NSA-Proofed my Email](http://www.27months.com/2013/10/its-always-sunny-in-iceland-or-how-i-nsa-proofed-my-email/)
- [Things not to do on Tor](https://www.whonix.org/wiki/DoNot)
- [The IoT may be dangerous! Beware!](http://jumpespjump.blogspot.fr/2015/09/how-i-hacked-my-ip-camera-and-found.html)
- [What every Browser knows about you](https://twitter.com/HTeuMeuLeu/status/719489886265454592)
- [Panopticlick - Is your browser safe against tracking?](https://panopticlick.eff.org/)
- [Am I Unique?](https://amiunique.org/)
- [Should you encrypt or compress first?](http://blog.appcanary.com/2016/encrypt-or-compress.html)
- [Protect your Documents with GPG](http://www.linux-magazine.com/Online/Features/Protect-your-Documents-with-GPG)
- [Yes, You Have Something to Fear](https://stallman.org/articles/why-fear-surveillance.html)
- [Something to Fear](https://stallman.org/something-to-fear.html)
- [BrowserLeaks.com](https://browserleaks.com/)
- [Panopticlick](https://panopticlick.eff.org/)
- [how to make the internet not suck (as much)](http://someonewhocares.org/hosts/)
- [Simple DNS Ad Blocker](https://github.com/tbds/FreeContributor)
- [Shared lists of problem domains people may want to block with hosts files](https://github.com/jmdugan/blocklists)
- [hosts: Extending and consolidating hosts files from several well-curated sources like adaway.org, mvps.org, malwaredomainlist.com, someonewhocares.org, and potentially others. You can optionally invoke extensions to block additional sites by category](https://github.com/StevenBlack/hosts)
- [Tout ce que votre navigateur peut balancer sur vous](http://korben.info/tout-ce-que-votre-navigateur-peut-balancer-sur-vous.html)
- [Paramétrage de Firefox](http://sebsauvage.net/wiki/doku.php?id=firefox)
- [“I have nothing to hide. Why should I care about my privacy?”](https://medium.com/@FabioAEsteves/i-have-nothing-to-hide-why-should-i-care-about-my-privacy-f488281b8f1d)
- [NOTHING TO HIDE documentaire (français, film complet HD)](https://www.youtube.com/watch?v=djbwzEIv7gE)
- [Through an app, darkly: How companies construct our financial identity](https://privacyinternational.org/node/1099)
- [I tested the most recommended VPN providers using my credit card to find the best ones — and which ones you should avoid.](https://vpnreport.org/)
- [Extensions Firefox pour protéger sa vie privée](https://blog.imirhil.fr/2015/12/08/extensions-vie-privee.html)
- [More extensions](https://amiunique.org/tools)
- [Even more extensions + how to configure Firefox](http://sebsauvage.net/wiki/doku.php?id=firefox)
- [Privacy/Privacy Task Force/firefox about config privacy tweeks](https://wiki.mozilla.org/Privacy/Privacy_Task_Force/firefox_about_config_privacy_tweeks)
- [How To Protect Your Privacy On Linux](https://spreadprivacy.com/linux-privacy-tips/)
- [Pi-hole](https://pi-hole.net/)
- [Derrière les assistants vocaux, des humains vous entendent](https://www.laquadrature.net/fr/temoin_cortana)
- [My Data Request](https://mydatarequest.com/)
- [Yesterday, I finished to switch to @ProtonMail by...](https://twitter.com/sdeleuze/status/1021655647442616322)
- [YOU ONLY LIVE ONLINE - Dries DEPOORTER - Web2day 2018](https://www.youtube.com/watch?v=AOzkW2W6gfA)
- [Surveillance Kills Freedom By Killing Experimentation](https://www.wired.com/story/mcsweeneys-excerpt-the-right-to-experiment/)
