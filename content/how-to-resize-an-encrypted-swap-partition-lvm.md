Title: How to resize an encrypted SWAP partition (LVM)
Date: 2022-07-29 19:00
Category: Linux
Tags: swap, lvm, linux
Slug: how-to-resize-an-encrypted-swap-partition-lvm
Authors: Romain Pellerin
Summary: A few commands to resize a SWAP partition

Just because I am afraid [this page](https://askubuntu.com/a/1412311) might some day get deleted, I am copy pasting here the answer, which was very useful to me, when I needed to increase the size of the SWAP partition of my freshly installed Xubuntu 22.04, after I selected the "encrypted LVM partition" option in the install wizard. I am adding some commands and missing bits of information too.

Before doing anything, check what swap you have: `swapon -s`.

Scroll all the way down if you do not currently have any SWAP partition nor SWAP file, and need to add one.

# Creating a new SWAP file of 8G

    :::bash
    dd if=/dev/zero of=/swapfile.img bs=1024 count=8M
    mkswap /swapfile.img

    # Add this line to `/etc/fstab` and reboot
    /swapfile.img none swap sw 0 0

# Increasing the size of a SWAP partition

Before increasing the size, you need to decrease the size of its neighboring root volume, as the sum of both LVM volumes myst be equal to the capacity of the disk. Here, we add another 15G to the existing SWAP partition.

First, open a terminal and check what your LVM disk currently looks like, running `lsblk`:

    :::bash
    lsblk
    # sda                      8:0    0 238,5G  0 disk
    # ├─sda1                   8:1    0   512M  0 part  /boot/efi
    # ├─sda2                   8:2    0   1,7G  0 part  /boot
    # └─sda3                   8:3    0 236,3G  0 part
    #   └─sda3_crypt         253:0    0 236,3G  0 crypt
    #     ├─vgxubuntu-root   253:1    0 234,4G  0 lvm   /var/snap/firefox/common/host-hunspell
    #     │                                             /var/snap
    #     │                                             /snap
    #     │                                             /var/snap/firefox/common/host-hunspell
    #     │                                             /
    #     └─vgxubuntu-swap_1 253:2    0   1,9G  0 lvm   [SWAP]

Here, it's `└─sda3_crypt`. And we have a swap partition of 1.9G.

Now, boot your Linux from a USB stick and open a terminal:

    :::bash
    sudo su
    # `sudo`    => Execute a command as another user.
    # `sudo su [user]` => Run a command with substitute user, default is root.

    # Encrypted device should NOT be unlocked
    lsblk # => list block devices
        # └─sda6 => no `crypt`/`lvm``

    # Unlock encrypted device
    cryptsetup open /dev/sda6 crypt # Enter passphrase
        # `cryptsetup` => Manage dm-crypt + LUKS encrypted volumes.
        # `cryptsetup open <device> <name>` => Opens encrypted lv as <name>

    # Get logical volume identifiers
    lsblk
        # └─sda6                  8:6    0 464,6G  0 part
        #   └─sda6_crypt        253:0    0 464,5G  0 crypt
        #     ├─vgubuntu-root   253:1    0 463,6G  0 lvm   /
        #     └─vgubuntu-swap_1 253:2    0   980M  0 lvm   [SWAP]

    # Shrink logical root volume AND filesystem
    lvresize --verbose --resizefs -L -15G /dev/mapper/vgubuntu-root
        # `lvresize` <volume> => resize a logical volume
        #   --verbose  => Give more info.
        #   --resizefs => Resize filesystem AND LV with fsadm(8).
        #   -L         => Specifies the new size of the LV,
        #                 +/- add/subtracts to/from current size, g|G is GiB.

    # Check filesystem of logical root volume for errors
    e2fsck -f /dev/mapper/vgubuntu-root
        # `e2fsck`<fs-path> => Check a Linux ext2/ext3/ext4 file system
        #   -f => Force checking even if the file system seems clean.

    # Increase swapsize
    lvresize --verbose -L +15G /dev/mapper/vgubuntu-swap_1
    # Format to make it usable
    mkswap /dev/mapper/vgubuntu-swap_1

Now, deactive the volume group: `vgchange -a n` or `vgchange -a n ubuntu-vg`.

And finally: `cryptsetup close crypt; reboot`

After rebooting, check the sizes using `lsblk`, `swapon -s` and `free -h`. That's it!

# Adding a new SWAP partition

The steps are the same: reboot on a USB stick, `sudo su` and `cryptsetup open /dev/sda6 crypt`. Then:

    :::bash
    lvresize --verbose --resizefs -L -16G /dev/mapper/ubuntu--vg-ubuntu--lv
    e2fsck -f /dev/mapper/ubuntu--vg-ubuntu--lv
    lvdisplay # Check all is fine, and find the VG name, needed for the next command
    lvcreate -n swap -L 16G ubuntu-vg
    mkswap /dev/mapper/ubuntu--vg-swap
    lsblk # Check all looks ok. At this point, the last line should read `└─ubuntu--vg-swap 252:2    0   16G  0 lvm`

    # The next steps are the same as for editing an existing SWAP partition
    vgchange -a n
    cryptsetup close crypt; reboot

Now, edit `/etc/fstab` by adding this:

    :::bash
    /dev/mapper/vgxubuntu-swap_1 none swap sw 0 0
    swapon -a # Enable it

    # Verify it works
    free -m
    # or
    swapon -s
    cat /proc/swaps

Reboot. All should work.
