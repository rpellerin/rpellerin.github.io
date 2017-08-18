Title: VLC 3.0
Date: 2017-08-18 14:50
Category: Linux
Tags: vlc linux
Slug: vlc-3.0
Authors: Romain Pellerin
Summary: How to install VLC 3.0 beta on Debian/Ubuntu

VLC 3.0 is currently in beta but yet brings long awaited features such as casting to a Chromecast natively. Here is how to install it on Debian/Ubuntu:

```bash
sudo add-apt-repository ppa:videolan/master-daily
sudo apt update
sudo apt purge vlc # Delete old version
sudo apt install vlc # This might not work
```

If it worked for you, stop reading here. Otherwise, keep reading what is below.

Since there is a compatibility issue, we'll need to reinstall a tweaked version of `libgles1-mesa` (or `libglapi-mesa`, I can't remember):

```bash
sudo apt purge libgles1-mesa
dpkg-deb -R libgles1-mesa_12.0.6-0ubuntu0.16.04.1_amd64.deb .
# Edit the dependencies (DEBIAN/control) to match `libglapi-mesa=17.0.7-0ubuntu0.16.04.1` instead of `12.0.6-0ubuntu0.16.04.1`
dpkg-deb -b . fixed.deb
sudo dpkg -i fixed.deb
sudo apt install vlc
```

It sould be working now! Hope this helps.
