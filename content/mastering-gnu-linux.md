Title: Mastering GNU/Linux
Date: 2016-02-02 00:50
Category: Linux
Tags: linux, ubuntu, xubuntu, debian, unix, gnu
Slug: mastering-gnu-linux
Authors: Romain Pellerin
Summary: Everything you have ever wanted to know about GNU/Linux

Everything you have ever wanted to know about GNU/Linux plus some troubleshooting tips I learned the hard way.

# Installing Linux on a Dell preinstalled with Windows

- In Windows, disable Fast startup in Panel Control > Power > Action when button pressed.
- In the BIOS/UEFI, disable SecureBoot if you're planning on installing proprietary third-aprty drivers
- You might want to enable 'SD card boot' in the BIOS under Boot > Miscellaneous devices if you use a SD card to install Linux
- In SATA operation, uncheck RAID and select AHCI otherwise Linux won't be able to see NVMe SDD drives. Also uncheck legacy ROM option (very important otherwise you will experience very slow boots, up to 10 min to access the BIOS). More information: [https://www.dell.com/community/General/Dell-XPS-13-9365-Won-t-boot-USB-in-SATA-Mode-AHCI-Trying-to/td-p/5119108/page/3](https://www.dell.com/community/General/Dell-XPS-13-9365-Won-t-boot-USB-in-SATA-Mode-AHCI-Trying-to/td-p/5119108/page/3) and [https://bbs.archlinux.org/viewtopic.php?id=204629](https://bbs.archlinux.org/viewtopic.php?id=204629).

That's it. You should be able to install Linux on the entire disk now.

# Upgrading your distribution of *Ubuntu

    :::bash
    sudo do-release-upgrade
    # OR
    sudo update-manager -c

[More information](https://help.ubuntu.com/lts/serverguide/installing-upgrading.html).

# GNU/Linux

- [Nom de code : Linux](https://www.youtube.com/watch?v=ANA134vEhEI)
- [Filesystem Hierarchy Standard](https://en.wikipedia.org/wiki/Filesystem_Hierarchy_Standard)
- [A few drawings about Linux](http://jvns.ca/blog/2016/11/10/a-few-drawings-about-linux/)
- [Pourquoi la mascotte de Linux est un manchot?](https://mavielinux.com/2016/12/18/pourquoi-la-mascotte-de-linux-est-un-manchot/)
- [Linux est ton meilleur ami (Pierre Antoine Grégoire - Olivier Robert - Nicolas Helleringer)](https://www.youtube.com/watch?v=xqdWi6SblV8)

## Free Software Foundation / Open source

- [Why “Free Software” is better than “Open Source”](https://www.gnu.org/philosophy/free-software-for-freedom.html)
- ["Why I don't like open source" – my thoughts](https://remysharp.com/2015/01/09/dont-like-open-source)

# Installing Linux

- [Que faire après l’installation d’un serveur Debian ?](http://blog.adminrezo.fr/2013/05/que-faire-apres-linstallation-dun-serveur-debian/)
- [Que faire après l’installation de (U|k|x)buntu](http://blog.adminrezo.fr/2014/01/post-installation-ubuntu-kubuntu/)
- [10 things to do first in Xubuntu 14.04 LTS](https://sites.google.com/site/easylinuxtipsproject/first-xubuntu)
- [grub reinstall after partition name change](http://superuser.com/questions/419876/grub-reinstall-after-partition-name-change)
- [How do I disable the guest session?](http://bookmarks.romainpellerin.eu/?page=1)
- [Quelques greffons pour Xfce](http://g.eckenschwiller.free.fr/Tutoriels/Installation/greffons_Xfce.php)
- [Add items to Xfce Applications Menu](http://xubuntugeek.blogspot.fr/2011/12/add-items-to-xfce-applications-menu.html)
- [firefox-tweaks: attempt to make Firefox suck less](https://github.com/dfkt/firefox-tweaks)
- [Build yourself a Linux](https://github.com/MichielDerhaeg/build-linux/blob/master/README.md)

## Alongside Windows

- [Windows 8+ dual boot on UEFI](https://help.ubuntu.com/community/UEFI)
- [Réparer Grub après une installation de windows](http://www.mercereau.info/reparer-grub-apres-une-installation-de-windows/)

# The whole system

- [Knowing your system - Part 1 - Basis on UNIX-like systems](https://www.clever-cloud.com/blog/engineering/2012/11/22/knowing-your-system-part-basics-on-unixlike-systems/)
- [La compilation du noyau](http://gfx.developpez.com/tutoriel/linux/kernel/)
- [Linux From Scratcg](http://www.linuxfromscratch.org/)
- [Linux file system hierarchy v2.0](https://www.blackmoreops.com/2015/06/18/linux-file-system-hierarchy-v2-0/)
- [Understanding /proc](https://fredrb.github.io/2016/10/01/Understanding-proc/)

# Shells & Terminals

- [What are the special dollar sign shell variables?](http://stackoverflow.com/questions/5163144/what-are-the-special-dollar-sign-shell-variables/5163260#5163260)
- [How to use double or single bracket, parentheses, curly braces](http://stackoverflow.com/questions/2188199/how-to-use-double-or-single-bracket-parentheses-curly-braces)
- [Why is $(...) preferred over `...` (backticks)?](http://mywiki.wooledge.org/BashFAQ/082)
- [Shell script analyzer](http://www.shellcheck.net/)
- [Advancing in the Bash Shell](http://samrowe.com/wordpress/advancing-in-the-bash-shell/)
- [Moving the terminal cursor](https://ddfreyne.github.io/til/2016/12-03-terminal-cursor-movement/)
- [rm -rf remains](https://lambdaops.com/rm-rf-remains/)
- [The annoying alternate screen in vte-based terminal applications](http://blog.guntram.de/?p=164)
- [st - a simple terminal implementation for X](http://st.suckless.org/)
- [Write your own terminal emulator](https://vincent.bernat.im/en/blog/2017-write-own-terminal)
- [Bash scripting quirks & safety tips](http://jvns.ca/blog/2017/03/26/bash-quirks/)
- [Fun at the UNIX Terminal Part 1](http://blog.regehr.org/archives/1483)

# Software

- [Laissez-vous pousser la barbe, apprenez à écrire des Makefiles](http://putaindecode.fr/posts/shell/apprendre-les-makefiles/)
- [Hints for writing Unix tools](http://monkey.org/~marius/unix-tools-hints.html)
- [TLP: battery optimizer](http://linrunner.de/en/tlp/tlp.html)

# Commands / tips

- [Linux Performance Analysis in 60,000 Milliseconds](http://techblog.netflix.com/2015/11/linux-performance-analysis-in-60s.html)
- [Pour avoir un peu d’intimité avec l’historique Bash](https://korben.info/dintimite-lhistorique-bash.html)
- [Ten Things I Wish I’d Known About bash](https://zwischenzugs.com/2018/01/06/ten-things-i-wish-id-known-about-bash/)
- [Ten More Things I Wish I'd Known About bash](https://zwischenzugs.com/2018/01/21/ten-more-things-i-wish-id-known-about-bash/)
- [18 commands to monitor network bandwidth on Linux server](http://www.binarytides.com/linux-commands-monitor-network/)
- [Recovering hard drive disk problems](http://arthurdejong.org/recovery.html)
- [Usefulness of the Linux Framebuffer on the Virtual Console](http://hacklab.cz/2012/04/22/usefulness-linux-framebuffer-virtual-console)
- [Print PDF on many pages](http://pythonhosted.org/pdftools.pdfposter/Examples.html)
- [SSH Kung Fu](http://blog.tjll.net/ssh-kung-fu/)
- [SSH Escape Sequences (aka Kill Dead SSH Sessions)](https://lonesysadmin.net/2011/11/08/ssh-escape-sequences-aka-kill-dead-ssh-sessions/)
- [NANO : Quelques raccourcis à retenir](http://korben.info/utiliser-nano.html)
- [Les commandes fondamentales de Linux](http://wiki.linux-france.org/wiki/Les_commandes_fondamentales_de_Linux)
- [Créer un serveur mail](http://nicodewaele.free.fr/Site/Stockage/Gnu-Linux/serveur-mail-postfix-courier-imap-ubuntu.pdf)
- [Un DNS avec Bind](http://nicodewaele.free.fr/Site/Stockage/Gnu-Linux/serveur-dns-bind.pdf)
- [Unbound, un autre résolveur DNS](http://www.bortzmeyer.org/unbound.html)
- [Anatomy of a Linux DNS Lookup – Part I](https://zwischenzugs.com/2018/06/08/anatomy-of-a-linux-dns-lookup-part-i/)
- [Too long; didn’t read pour vos man pages](http://korben.info/long-didnt-read-pour-vos-man-pages.html)
- [Add a new command available in shell](http://askubuntu.com/questions/427818/how-can-i-run-this-sh-script-without-typing-the-full-path/527008#527008)
- [How to format a USB flash drive?](http://askubuntu.com/questions/22381/how-to-format-a-usb-flash-drive/571340#571340)
- [gotty: Share your terminal as a web application](https://github.com/yudai/gotty)
- [crontab.guru](http://crontab.guru/)
- [Rendre Linux écologique (green-it)](https://www.security-helpzone.com/rendre-linux-ecologique-gren-it-news-335.html)
- [HOW BREAKPOINTS ARE SET](http://majantali.net/2016/10/how-breakpoints-are-set/)
- [Réaliser l’image d’un disque dur avec Testdisk](http://korben.info/realiser-limage-dun-disque-dur-testdisk.html)
- [The right way to check the weather http://wttr.in](https://github.com/chubin/wttr.in)
- [30 interesting commands for the Linux shell](https://www.lopezferrando.com/30-interesting-shell-commands/)

# Other distros

- [#!++, A CrunchBang revival project (very lightweight distro)](https://github.com/CBPP/cbpp)

# Security

- [Shellcode Injection](https://dhavalkapil.com/blogs/Shellcode-Injection/)
- [Security Guide: How to Protect Your Infrastructure Against the Basic Attacker](http://blog.mailgun.com/security-guide-basic-infrastructure-security/)
- [/dev/random vs /dev/urandom](http://www.onkarjoshi.com/blog/191/device-dev-random-vs-urandom/) & [Myths about /dev/urandom](http://www.2uo.de/myths-about-urandom/)
- [Hygiène numérique pour l’administrateur système](https://confs.imirhil.fr/20170513_root66_securite-admin-sys/#1)
- [How To Protect Your Privacy On Linux](https://spreadprivacy.com/linux-privacy-tips/)
- [Hands-on Guide on GPG Keys](http://thegeekyway.com/hands-on-guide-on-gpg-keys/)
- [Creating a new GPG key with subkeys](https://www.void.gr/kargig/blog/2013/12/02/creating-a-new-gpg-key-with-subkeys/)
- [GNU Privacy Guard](http://www.beaupeyrat.com/wp-content/uploads/2015/07/gpg.pdf)
- [Using an offline GnuPG master key](https://incenp.org/notes/2015/using-an-offline-gnupg-master-key.html)
- [Using OpenPGP subkeys in Debian development](https://wiki.debian.org/Subkeys)
- [Securing Debian Manual](https://www.debian.org/doc/manuals/securing-debian-howto/index.fr.html)
- [How To Secure A Linux Server](https://github.com/imthenachoman/How-To-Secure-A-Linux-Server)
- [the-practical-linux-hardening-guide](https://github.com/trimstray/the-practical-linux-hardening-guide#information_source-introduction-3)

# Other

- [Operating System: From 0 to 1](https://tuhdo.github.io/os01/)
- [xmonad + vimperator](https://mobile.twitter.com/clementd/status/837600575483179009)
- [Linux Performance Analysis in 60,000 Milliseconds](https://medium.com/netflix-techblog/linux-performance-analysis-in-60-000-milliseconds-accc10403c55)
- [System Programming](https://github.com/angrave/SystemProgramming/wiki)
- [How To Tell If Your Linux Server Has Been Compromised](https://bash-prompt.net/guides/server-hacked/)
- [SystemD pro level linux des temps modernes Process management, containers (P.A. Grégoire, Q. Adam)](https://www.youtube.com/watch?v=v-jdlc5YdDc)
- [Dotfile madness](https://0x46.net/thoughts/2019/02/01/dotfile-madness/)
- [trimstray/the-book-of-secret-knowledge](https://github.com/trimstray/the-book-of-secret-knowledge#cli-tools-toc)
