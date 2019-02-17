Title: On Storing Passwords In A Safe Way
Date: 2018-07-31 00:20
Modified: 2019-01-25 00:06
Category: Computers
Tags: password, security
Slug: on-storing-passwords-in-a-safe-way
Authors: Romain Pellerin
Summary: My journey towards increased security

It's been a while since I last wrote here... Let's fix this with a new article on an important topic.

# Storing passwords

For a very long time I have stored my passwords within Firefox, synced "in the Cloud" through my Firefox account. This solution offers four main advantages:

- Passwords are synced accross devices, always at hand
- Passwords are conveniently auto-filled as I browse the web
- Passwords are encrypted on Mozilla's servers ([source](https://support.mozilla.org/en-US/questions/1169355))
- Passwords are encrypted locally on my computer ([source](https://support.mozilla.org/en-US/questions/1210914)), although a Master Password is required to prevent theft (since the encryption key is stored in plain) by adding another layer of security ([source](https://support.mozilla.org/en-US/questions/1041243))

While this solution had seemed like a good one for a while, I recently thought I could do better than putting my trust in a company - even though Mozilla is respectable and trustworthy. Eventually, every big company gets hacked and I don't really know how they encrypt my data on their servers.

Moreover, I wanted to be able to safely backup my passwords somewhere (offline, if need be, or elsewhere). My past solution had a flaw: were I to lose my laptop and smartphone (theft, apartment burns dowm, earthquake, whatever), I'd have no way to recover my passwords since I don't know any of them, not even my Firefox password (what good would it be for anyway, since two factor authentication is enabled and my two only trusted devices would be lost).

*Side note on this topic*: I always randomly type my passwords as I create accounts. I don't use services that generate them for me (like Dashlane or Lastpass to mention a few). I don't trust these programs to store my passwords (we'll see why in a bit). Nonetheless, I always make sure to include capital letters, numbers and special characters, with a length greater than 8.

With this all in mind, I needed to find a solution that could fulfill the following requirements:

- Not hosted on someone else's servers
- Encrypted at all times
- Easily accessible on multiple devices, including Linux and mobile phone(s)
- Synced accross my devices
- As much user friendly as possible so that I can easily switch to this new solution and change my habits
- Backed up offline or in a different location AND easily recoverable in case of loss of my trusted devices
- Based on a non proprietary solution - both free and open source ideally - and free of charge
- Auto form-filling on a web is a plus (through a web extension for instance)
- Two factor auth management is a plus: it could be nice to save 2FA secrets as well
- Password generation is optional, I'm fine with random typing on my keyboard every once in a while

## Market analysis

### 1password

Apparently a great tool, I've read a lof of good reviews. Advocated by the famous website [Have I Been Pwned](https://haveibeenpwned.com/1Password). However, since this is proprietary software, it is de facto a no-go for me. Plus it's a paid service.

### Keepass

[A tool initially made for Windows](https://keepass.info/).

#### Pros

- Encryption out of the box
- Free and open source
- Available on Linux through a port
- There's an Android app
- There's a [Firefox extension](https://addons.mozilla.org/fr/firefox/addon/keefox/)
- Auto form filling
- Automatic generation of passwords
- I can easily save a backup file with all my passwords on my personal cloud
- [2FA management through a plugin](https://keepass.info/plugins.html#keeotp)
- [Database can be protected with 2FA through a plugin](https://keepass.info/plugins.html#otpkeyprov)

#### Cons

- I must trust the binaries or build them myself after reviewing the sources...

### Pass (Linux CLI)

[A tool](https://www.passwordstore.org/) [for Linux](https://wiki.archlinux.org/index.php/Pass) (CLI) based on GPG. Convenient on a laptop, not very much on a mobile phone. However, I tend to trust a CLI tool more than Keepass. One of the reasons I guess is that it's on the official Linux distributions repos.

#### Pros

- Encryption out of the box with GPG
- Automatic generation of passwords
- [Cross platform](https://www.passwordstore.org/#other): Linux, Android and Firefox plugin at least
- I can easily save a backup file with all my passwords on my personal cloud
- 2FA management (actually Pass can save any arbitraty data, it's essentially made up of encrypted plain text files)
- Super easily backupable (zip the directory, that's all) on my personal cloud

#### Cons

- Not very user friendly
- I must trust binaries, specifically the Android app and Firefox binary; the Linux script has all my trust though (yes, `pass` is a script)

### Dashlane and Lastpass

Proprietary software: no go.

One cool feature Dashlane offers that is worth mentioning though: setting an emergency contact for your important accounts in case of a critical matter. I wish other tools offered the same feature.

### Conclusion

Seems like I'll either go with either Keepass or Pass. I'll update this article as I become familiar with either of them.

**Update January 2019**: I've been using `pass` for a few months now and am very satisfied so far.

# Another topic: two-factor authentication

For this one, I felt that storing my secrets on my phone (Google Authenticatgor to name it) only sounded a little too much like a Single Point of Failure. Should I ever lose access to my phone for any reason, I'd be locked out of services with two factor authentication enabled. Time had come to find a way to save a backup of these secrets.

To do so, I developed my own solution: [gauth2](https://github.com/rpellerin/gauth2/). It is forked from another project ([gauth](https://github.com/gbraad/gauth)). I improved it and redesigned it a bit. I also added some features.

How does it work? Well, it is a web app that stores in one's browser (in the `LocalStorage`, to be precise) one's secrets and generate One Time Passwords based on these secrets. The secrets can even be encrypted with a password, to prevent someone with access to the browser from stealing the secrets. It also allows secrets to be exported and imported for more convenience. I am very satisfied with it so far. This, on an encrypted laptop, coupled with Google Authenticator, is a pretty solid combination.

<br />

Hope this helps. Cheers!

# Online resources

- [2FA, SMS, and you](https://www.juliaferraioli.com/blog/2018/08/2fa-sms-you/)
- [L’authentification double facteur (2FA), oui mais pas n’importe comment !](https://korben.info/authentification-double-facteur-2fa.html)
- [My recent journey with 2FA](https://chown.me/blog/my-recent-journey-with-2FA.html)
- [The Definitive Guide to password-store](https://medium.com/@chasinglogic/the-definitive-guide-to-password-store-c337a8f023a1)
- [Using pass in a team](https://medium.com/@davidpiegza/using-pass-in-a-team-1aa7adf36592)
