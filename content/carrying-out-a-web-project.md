Title: Carrying Out A Web Project
Date: 2015-12-03 00:40
Category: Code
Tags: web, code, html, css, javascript
Slug: carrying-out-a-web-project
Authors: Romain Pellerin
Summary: Resources for web projects

Here are some resources.

# How to carry out a web project?

- [Ma méthodologie d’intégration web](http://darklg.me/2012/04/methodologie-integration-web-front-end/)
- [Some pieces of advice](https://old.etherpad-mozilla.org/RAI9OV5U2k)
- [Maintenabilité du code HTML / CSS : entre automatisation et guide de style](https://www.24joursdeweb.fr/2017/maintenabilite-du-code-html-css-entre-automatisation-et-guide-de-style/)

# CSS

- [Pour une CSS simple et réutilisable](https://github.com/dhoko/Guidelines/blob/master/CSS-guidelines.md)
- [CSS Values](http://cssvalues.com/): a good documentation about CSS properties
- [Feuilles de styles CSS - Conseils et bonnes pratiques](http://guidecss.fr/)
- [Mini Convention CSS](http://www.alsacreations.com/article/lire/1707-mini-convention-css.html)
- [Flexbox, et le CSS redevient fun ! (Hubert SABLONNIÈRE)](https://www.youtube.com/watch?v=5F_ngjHDcJQ)
- [CSS is Awesome Igor Laborie](https://www.youtube.com/watch?v=H8lICKucWL4)
- [Grid by Example](https://gridbyexample.com/)
- [Understanding Web Fonts and Getting the Most Out of Them](https://css-tricks.com/understanding-web-fonts-getting/)
- [Everything you need to know about CSS Variables](https://medium.freecodecamp.org/everything-you-need-to-know-about-css-variables-c74d922ea855)

## Support and compatibility

- [caniuse.com](https://caniuse.com): check out the tab 'Relative'
- [gs.statcounter.com](https://gs.statcounter.com)
- [developer.mozilla.org](https://developer.mozilla.org) aka MDN
- [www.chromestatus.com/features](https://www.chromestatus.com/features)
- [developer.microsoft.com/en-us/microsoft-edge/platform/status/](https://developer.microsoft.com/en-us/microsoft-edge/platform/status/)
- [webkit.org/status/](https://webkit.org/status/)
- [platform-status.mozilla.org](https://platform-status.mozilla.org)
- [bugzilla.mozilla.com](https://bugzilla.mozilla.com)

## Centering

- [How to Center in CSS](http://howtocenterincss.com/)

# Mobile development

- [Comment déboguer facilement du web y compris sur tablettes et téléphones](http://putaindecode.fr/posts/frontend/comment-deboguer-du-web-sur-tablettes-et-telephones/)

## Responsive design

- [Ultra simple Responsive navigation snippets](http://goetter.fr/nav/)
- [Responsive web design](https://developer.mozilla.org/en-US/docs/Glossary/Responsive_web_design)

# Compatibility

- [Can I Use](http://caniuse.com/)

# Testing

- [Doubling Down on Cross-Browser Testing](https://hacks.mozilla.org/2017/03/doubling-down-on-cross-browser-testing/)
- [Web Testing: A Complete guide about testing web applications](http://www.softwaretestinghelp.com/web-application-testing/)

# Finalizing

- [Web Developer Checklist](http://webdevchecklist.com/)
- [Front-End-Checklist](http://frontendchecklist.com/)
- [Donner du sens à vos pages web avec Schema.org](https://www.youtube.com/watch?v=SVrgarg3KNs)

## HTTPS and security

- [Check liste pour passer un site de HTTP à HTTPS](https://wooster.checkmy.ws/2014/10/upgrade-http-vers-https/)
- [SSL Server Test](https://www.ssllabs.com/ssltest/)
- [Observatory by Mozilla](https://observatory.mozilla.org/)
- [Mozilla SSL Configuration Generator](https://mozilla.github.io/server-side-tls/ssl-config-generator/)
- [Certificat SSL avec Letsencrypt](http://www.system-linux.eu/index.php?post/2016/01/12/Certificat-SSL-avec-Letsencrypt)
- [Why isn't HTTPS everywhere yet?](http://webappsec-test.info/~bhill2/DifferentTakeOnOE.html)
- [Cipherli.st - Strong Ciphers for Apache, nginx and Lighttpd](https://cipherli.st/)
- [Rouler en classe A avec Apache](https://blog.adminrezo.fr/2016/12/securiser-serveur-apache-https-headers/)
- [Installer et activer HTTP2 sur Apache2](https://korben.info/installter-activer-http2-apache2.html)
- [Installer et activer HTTP2 sur Nginx](https://korben.info/installer-activer-http2-nginx.html)
- [Security/Guidelines/Web Security](https://wiki.mozilla.org/Security/Guidelines/Web_Security)
- [Web Application Exploits and Defenses](https://google-gruyere.appspot.com/#0__hackers)

## Formatting / minimizing / indentation / compressing

- [HTMLLisible](http://lab.darklg.me/HTMLLisible/): auto-indent HTML
- [CSSLisible](http://csslisible.com/en/): order properties and auto-indent CSS
- [Online JavaScript/CSS Compressor](http://refresh-sf.com/)
- [Optimise your pngs from the terminal in OSX](http://www.clock.co.uk/blog/optimise-your-pngs-from-the-terminal-in-osx)
- [Cache et compression des pages web avec Gzip ou Deflate en HTTP](http://www.alsacreations.com/article/lire/914-compression-pages-html-css-gzip-deflate.html)
- [PageSpeed Insights](https://developers.google.com/speed/pagespeed/insights/)
- [Monitoring unused CSS by unleashing the raw power of the DevTools Protocol](http://blog.cowchimp.com/monitoring-unused-css-by-unleashing-the-devtools-protocol/)

## Apache setup

- [.htaccess Snippets](https://github.com/phanan/htaccess)
- [Quelques règles htaccess pour sécuriser votre site](http://korben.info/quelques-regles-htaccess-pour-securiser-votre-site.html)

## Favicon

- [Understand the Favicon](http://www.jonathantneal.com/blog/understand-the-favicon/)
- [Un favicon vite fait bien fait](http://putaindecode.io/fr/articles/favicon/)

On Linux, if you have `imagemagick` installed, do:

    :::bash
    convert favicon.png -alpha on -resize 256x256 \
        -define icon:auto-resize="256,128,96,64,48,32,16" \
        favicon.ico

# Useful resources

## Fonts

- [How we use web fonts responsibly, or, avoiding a @font-face-palm](https://www.filamentgroup.com/lab/font-loading.html)
- [Font-display ](https://font-display.glitch.me/)
- [Three Techniques for Performant Custom Font Usage](https://css-tricks.com/three-techniques-performant-custom-font-usage/)

## Icons

- [Icones web Unicode](http://goetter.fr/unicode/)
- [Pictonic - free](https://pictonic.co/free)
- [IcoMoon App](https://icomoon.io/app/)
- [iconmonstr](http://iconmonstr.com/)
- [PaymentFont: A sleek webfont containing 95 icons of all main payment operators and methods](http://paymentfont.io/)
- [Seriously, Don’t Use Icon Fonts](http://blog.cloudfour.com/seriously-dont-use-icon-fonts/)
- [Seriously, use icon fonts](http://benfrain.com/seriously-use-icon-fonts/)

# Bonus: HTML Emails

- [My Current HTML Email Development Workflow](http://www.sitepoint.com/my-current-html-email-development-workflow/)
- [Build Fully Responsive Email In Just Minutes.](https://www.inkbrush.com/)
- [Foundation for Emails](http://foundation.zurb.com/emails.html)

# Going further

## Methodology

- [A CRITICISM OF SCRUM](https://www.aaron-gray.com/a-criticism-of-scrum/)
- [WAYS TO PROTECT FLOW](https://www.aaron-gray.com/ways-to-protect-flow/)

## Web Assembly

- [Awesome wasm](https://github.com/mbasso/awesome-wasm)
- [WebAssembly 101: a developer's first steps](http://blog.openbloc.fr/webassembly-first-steps/)
- [WebAssembly, an executable format for the web ](https://blog.octo.com/en/webassembly-an-executable-format-for-the-web/)

## PWAs

- [A Pinterest Progressive Web App Performance Case Study](https://medium.com/dev-channel/a-pinterest-progressive-web-app-performance-case-study-3bd6ed2e6154)
- [Lab: Migrating to Workbox from sw-precache and sw-toolbox](https://developers.google.com/web/ilt/pwa/lab-migrating-to-workbox-from-sw-precache-and-sw-toolbox)
- [Modifications to your existing configuration](https://developers.google.com/web/tools/workbox/guides/migrate)
- [Workbox](https://developers.google.com/web/tools/workbox/)

## Linters

- [CSS](https://github.com/stylelint/stylelint)
- [JavaScript](https://github.com/eslint/eslint)

## Testing

- [Using Headless Mode in Firefox](https://hacks.mozilla.org/2017/12/using-headless-mode-in-firefox/)

## Misc

- [6 Rules of thumb to build blazing fast web server applications](http://loige.co/6-rules-of-thumb-to-build-blazing-fast-web-applications/)
- [10 things I learned making the fastest site in the world](https://hackernoon.com/10-things-i-learned-making-the-fastest-site-in-the-world-18a0e1cdf4a7)
- [Free Web Analytics Software (Piwik)](http://piwik.org/)
- [The perfect PHP clean url generator](http://cubiq.org/the-perfect-php-clean-url-generator)
- [PHP - The Right Way](http://www.phptherightway.com/)
- [What Web Can Do Today](https://whatwebcando.today/)
- [OpenSearch](http://www.opensearch.org/Home)
- [De la gestion de projet à la gestion de workflow](http://www.geek-directeur-technique.com/2016/08/24/de-la-gestion-de-projet-a-la-gestion-de-workflow)
- [WebSocketd – Faites communiquer vos pages web avec vos outils en ligne de commande](http://korben.info/websocketd-communiquer-vos-pages-web-vos-outils-ligne-de-commande.html)
- [Front-End Developer Handbook 2017](https://www.gitbook.com/book/frontendmasters/front-end-handbook-2017/details)
- [Les Web DevTools en 2017 (Jean-Francois Garreau)](https://www.youtube.com/watch?v=par742RHhVM)
- [Optimiser les performances d'une webapp (Guillaume EHRET)](https://www.youtube.com/watch?v=9PRPPJFaF_o)
- [The Illusion of Speed](https://paulbakaus.com/tutorials/performance/the-illusion-of-speed/)
- [Firefox 54: E10S-Multi, WebExtension APIs, CSS clip-path](https://hacks.mozilla.org/2017/06/firefox-54-e10s-webextension-apis-css-clip-path/)
- [How we rebuilt the viewsourceconf.org website](https://hacks.mozilla.org/2017/10/how-we-rebuilt-the-viewsourceconf-org-website/)
- [What Web Can Do Today](https://whatwebcando.today/)
- [History of the browser user-agent string](https://webaim.org/blog/user-agent-string-history/)
