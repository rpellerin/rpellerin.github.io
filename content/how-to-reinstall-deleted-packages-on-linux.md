Title: How To Reinstall Deleted Packages On Linux
Date: 2017-08-18 15:30
Category: Linux
Tags: linux, packages
Slug: how-to-reinstall-deleted-packages-on-linux
Authors: Romain Pellerin
Summary: A few commands to repair and reinstall deleted packages on Linux

Recently, I inadvertently deleted a whole lot of packages on my Linux distribution by deleting one specific package and then running `sudo apt-get autoremove`. Here is how I reinstalled them automatically, after noticing I could not boot the X server up anymore:

```bash
awk '/Purge|Remove/ { gsub( /\([^()]*\)/, "" ); gsub(/ ,/, " ");sub(/Install:/,""); print}' /var/log/apt/history.log > repair.txt
sudo apt install $(cat repair.txt)
```

Hope this helps.
