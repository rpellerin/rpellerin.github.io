Title: How to resize an encrypted SWAP partition (LVM)
Date: 2022-07-29 19:00
Category: Linux
Tags: swap, lvm, linux
Slug: how-to-resize-an-encrypted-swap-partition-lvm
Authors: Romain Pellerin
Summary: A few commands to resize a SWAP partition

Just because I am afraid [this page](https://askubuntu.com/a/1412311) might some day get deleted, I am copy pasting here the answer, which was very useful to me, when I needed to increase the size of the SWAP partition of my freshly installed Xubuntu 22.04, after I selected the "encrypted LVM partition" option in the install wizard. I am adding some commands and missing bits of information too.

Before increasing the size, I needed to decrease the size of its neighboring root volume, as the sum of both LVM volumes was equal to the capacity of my disk. Here I add another 7G to my SWAP partition.

First, check what your LVM disk currently looks like, running `lsblk`:

    :::bash
    lsblk
    # └─sda6                  8:6    0 464,6G  0 part
    #   └─sda6_crypt        253:0    0 464,5G  0 crypt
    #     ├─vgubuntu-root   253:1    0 463,6G  0 lvm   /
    #     └─vgubuntu-swap_1 253:2    0   980M  0 lvm   [SWAP]

For me, `sda6` was `nvme0n1p3`.

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
    lvresize --verbose --resizefs -L -7G /dev/mapper/vgubuntu-root
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
    lvresize --verbose -L +7G /dev/mapper/vgubuntu-swap_1
    # Format to make it usable
    mkswap /dev/mapper/vgubuntu-swap_1

Now, deactive the volume group: `vgchange -a n` or `vgchange -a n sda6_crypt`.

And finally: `cryptsetup close crypt; reboot`

After rebooting, check the sizes using `lsblk`, `swapon -s` and `free -h`. That's it!
