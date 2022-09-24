Title: Android, iOS: What Alternatives?
Date: 2015-11-26 15:00
Modified: 2017-12-30 19:00
Category: Miscellaneous
Tags: mobile, android, ios, ubuntu, firefox
Slug: android-ios-what-alternatives
Authors: Romain Pellerin
Summary: What alternatives do we have to Android and iOS? How to install Firefox OS and Ubuntu for devices.

As 2015 is slowly coming to an end, let's try to shape the state of the global smartphone market share.

![Market share]({static}/images/chart-ww-smartphone-os-market-share.png)

_[Source](http://www.idc.com/prodserv/smartphone-os-market-share.jsp)_

It makes no doubt that **the world is ruled by two main OSes: Android and iOS**. But as you may have heard, those operating systems are powered by two giants, Google and Apple, and in many cases, this can lead to privacy issues. In addition, iOS is proprietary softare and Android tends to be more and more opaque as well. So, what other alternatives do we have? How can we regain control over software we use?

Since a few years, two other operating systems offering good alternatives, have been in intense development. One is made by Mozilla, called "_Firefox OS_", the other one is powered by Canonical (the company behind Ubuntu), called "_Ubuntu for devices_". Those two OSes are really different in the way they work.

Firefox OS relies on HTML5. As a web developer, you'll find developing on this platform pretty straightforward. Applications are built on top of HTML/CSS.

On the other hand, Ubuntu is... kind of a Ubuntu distribution but developed and optimized for mobiles. It allows you to do most of what you might do on a desktop distribution.

For now, as these OSes are still in early development, they're not available worldwide easily. Somes phones run theses OSes but they're not mainstream yet. An easy option is to build them yourself and install them on your own Android device. Here are short tutorials.

# Installing Ubuntu for devices

## For Nexus 4

    :::bash
    sudo add-apt-repository ppa:ubuntu-sdk-team/ppa
    sudo apt-get update
    sudo apt-get install ubuntu-device-flash phablet-tools

Plug in your phone, enable the developer mode and USB debugging.

    :::bash
    adb reboot bootloader
    sudo fastboot oem unlock
    ubuntu-device-flash touch --channel=stable --bootstrap

[More information](https://developer.ubuntu.com/en/start/ubuntu-for-devices/installing-ubuntu-for-devices/).

## For Nexus 5

There are 3 methods (updated in December 2017). First, plug in your phone. Then:

### 1

[Official tutorial](https://devices.ubports.com/#/hammerhead).

    :::bash
    git clone https://github.com/MariusQuabeck/magic-device-tool.git
    cd magic-device-tool
    sudo ./launcher.sh

### 2

[Official tutorial](https://devices.ubports.com/#/hammerhead).

    :::bash
    sudo apt-get install ubuntu-device-flash phablet-tools
    adb reboot bootloader
    sudo fastboot oem unlock
    sudo ubuntu-device-flash --server=http://system-image.ubports.com touch --device=hammerhead --channel=15.04/stable --bootstrap

### 3

[Official tutorial](https://ubports.com/page/ubuntu-nexus-5).

    :::bash
    wget https://github.com/ubports/ubports-installer/releases/download/0.1.9-beta/ubports-installer_0.1.9-beta_amd64.deb -O /tmp/ubports.deb
    sudo dpkg -i /tmp/ubports.deb
    ubports-installer

# Building and installing Firefox OS

## For Nexus 4

Plug in your phone, enable the developer mode and USB debugging.

### First step: flash your Nexus 4 with Android 4.3

    :::bash
    sudo apt-get install android-tools-adb
    wget https://dl.google.com/dl/android/aosp/mantaray-jwr66y-factory-3d8252dd.tgz
    tar xvzf occam-jwr66y-factory-74b1deab.tgz
    cd occam-jwr66y
    ./flash-all.sh # Boot to fastboot and unlock bootloader

### Second step: build Firefox OS (on a 14.04 Ubuntu)

    :::bash
    sudo dpkg --add-architecture i386
    sudo apt-get update
    sudo apt-get install --no-install-recommends autoconf2.13 bison bzip2 ccache \
        curl flex gawk gcc g++ g++-multilib gcc-4.6 g++-4.6 g++-4.6-multilib git \
        lib32ncurses5-dev lib32z1-dev zlib1g:amd64 zlib1g-dev:amd64 zlib1g:i386 \
        zlib1g-dev:i386 libgl1-mesa-dev libx11-dev make zip libxml2-utils
    sudo update-alternatives --install /usr/bin/gcc gcc /usr/bin/gcc-4.6 1
    sudo update-alternatives --install /usr/bin/gcc gcc /usr/bin/gcc-4.8 2
    sudo update-alternatives --install /usr/bin/g++ g++ /usr/bin/g++-4.6 1
    sudo update-alternatives --install /usr/bin/g++ g++ /usr/bin/g++-4.8 2
    sudo update-alternatives --set gcc "/usr/bin/gcc-4.6"
    sudo update-alternatives --set g++ "/usr/bin/g++-4.6"
    ccache --max-size 10GB

Plug in your phone, enable the developer mode and USB debugging.

    :::bash
    adb pull /system /tmp/system
    adb pull /data /tmp/data
    adb pull /vendor /tmp/vendor
    git clone git://github.com/mozilla-b2g/B2G.git
    cd B2G
    git pull
    ./repo sync
    ./config nexus-4
    ./build.sh

### Third step: install it

    :::bash
    ./flash.sh

### Some additional settings

    :::bash
    cd gaia
    make reset-gaia PRODUCTION=1
    mkdir -p locales
    sudo aptitude install mercurial
    rm -rf locales/fr
    hg clone http://hg.mozilla.org/gaia-l10n/fr locales/fr
    export LOCALE_BASEDIR=$PWD/locales
    export LOCALES_FILE=$PWD/locales/languages_dev.json
    export GAIA_DEFAULT_LOCALE=en
    export GAIA_KEYBOARD_LAYOUTS=en,fr
    rm locales/languages_dev.json -f
    echo '{
     "en-US"     : "English (US)",
     "fr"        : "Français"
    }' > locales/languages_dev.json
    make clean && make production LOCALES_FILE=locales/languages_dev.json

[More information](https://developer.mozilla.org/en-US/Firefox_OS/Building_and_installing_Firefox_OS).

# Other alternatives

- [LineageOS](https://lineageos.org/)
- [Purism's Librem 5](https://puri.sm/shop/librem-5/)
