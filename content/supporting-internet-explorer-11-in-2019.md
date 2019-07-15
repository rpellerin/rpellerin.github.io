Title: Supporting Internet Explorer 11 in 2019
Date: 2019-07-15 14:30
Category: Code
Tags: internet explorer, browsers
Slug: supporting-internet-explorer-11-in-2019
Authors: Romain Pellerin
Summary: Following the recent publication of A Conspiracy To Kill IE6, we at Doctolib thought we’d in turn unveil our plan to stop supporting not only Internet Explorer 11 but also other outdated desktop…

*Originally published on [Medium](https://medium.com/doctolib/supporting-internet-explorer-11-in-2019-12dc1f1ebc3c).*

Following the recent publication of [A Conspiracy To Kill IE6](https://blog.chriszacharias.com/a-conspiracy-to-kill-ie6), we [at Doctolib](https://www.doctolib.com/) thought we’d in turn unveil our plan to stop supporting not only Internet Explorer 11 but also other outdated desktop browser versions, and help the world move towards a faster and more secure web. Everything that follows took place between March 2019 and July 2019.

In late March 2019 Doctolib had over 80,000 paying customers. They are physicians and specialists working in private practices, hospitals, healthcare centers or even at home. They use all kinds of devices and browsers. Some devices are privately owned, some are provided by employers and some are even shared and used by many people throughout the day. As a consequence, it is impossible for us to predict with certainty what browsers our customers will use.

Moreover, most public healthcare institutions (hospitals, for example) manage their computers like they do in schools or governments. All regular users are not administrators, they only have restricted permissions. They can’t install software or decide which browser they use. Upgrade-wise, their IT departments tend to update the OS and browsers every 1 to 2 years. Privacy is also a much bigger concern for most of them, so they usually favor Firefox over Chrome, and sometimes IE 11 or Edge for compatibility reasons. Last but not least, they’re big on Firefox Extended Support Release (ESR) versions (for a reason, [it comes with long term support](https://www.mozilla.org/en-US/firefox/organizations/)).

In light of this, here’s what Doctolib’s browser market share looked like in March 2019.

<figure class="center">
<img src="{filename}/images/supporting-internet-explorer-11-in-2019/browsers-share.png" alt="Historical browser market share for Doctolib customers week by week" />
<figcaption>Historical browser market share for Doctolib customers week by week</figcaption>
</figure>

The graph shows unique browser connections per week. As one can see, Internet Explorer 11 users represent on average between 2.5 and 3.5% of all users. Among our Chrome and Firefox users, some users rely on outdated versions, sometimes more than 4-year-old versions. Same goes for Safari, with pretty stable curves not showing any signs of decline.

<figure class="center">
<img src="{filename}/images/supporting-internet-explorer-11-in-2019/safari.png" alt="Historical Safari browser market share for Doctolib customers day by day up until March 2019" />
<figcaption>Historical Safari browser market share for Doctolib customers day by day up until March 2019</figcaption>
</figure>

So what’s the big deal?

# Cost of supporting old browsers

The more browsers a company decides to support, the more problems it brings. At Doctolib, we identified the following specific problems:

- Old browsers only understand ECMAScript 5. Like most modern web applications, we have **a lot** of JavaScript dependencies. Each dependency upgrade must be checked to make sure it doesn’t contain untranspiled code. **That’s time wasted.**

- Our app is built with Ruby on Rails and React. Our JavaScript code is bundled in two different ways, depending on the files and where in the app it runs: the [RoR asset pipeline](https://guides.rubyonrails.org/asset_pipeline.html) or Webpack. The code processed by the asset pipeline does not go through Babel, as opposed to the one bundled by Webpack. Therefore we can’t write modern JavaScript in all our JavaScript files yet\*. **That’s time wasted writing and code reviewing old-fashioned JavaScript** \*(we have a work-in-progress project for using Babel with RoR).

- Our 10,000+ integration tests are executed with the latest version of Chrome. Nonetheless, since we support multiple browsers, we also have a few simple additional integration tests that run on IE11 as well as outdated Edge, Firefox and Safari browsers. These extra tests **cost time** (developers waiting for CI tests to complete) and **money** (tests run time on Heroku). **They also require extra maintenance.**

- Last but far from least, we’ve had several **production issues** in the last 6 months caused by untranspiled NPM dependencies not working in old browsers. That’s **user dissatisfaction and time wasted for engineers**. Plus, each production issue is immediately followed by a detailed **postmortem** which oftentimes means a full day of work for an engineer.

On a side note, supporting multiple browsers creates bigger JavaScript bundles because code transpiled through Babel is usually bigger and polyfills take space. It slightly increases compile time, bandwidth consumption, and JavaScript load/parse times.

Knowing this, in a perfect world we’d only support the latest version of each browser, Internet Explorer 11 excluded of course. But that is not possible.

# Limitations

Why is it so hard to have all of our customers upgrade their browsers? Why can’t we just display a banner, or send them an email, threatening to drop support for outdated browsers, and eventually do it?

Well, there are many good reasons. First, healthcare professionals are generally not computer savvy. Computers are a tool, a means to them, not an end. They don’t want to have to worry about upgrades or browser versions.

Also, as written in the introductory paragraph, people working in health establishments have no control over what browser they run. Budget and time constraints prevent these organizations from providing their employees with the latest computers, or prevent them from upgrading software on a regular basis. Also, some organizations are more concerned about privacy than others and thus insist on running specific browsers. Finally, some organizations sometimes use Internet Explorer because they also use other websites which require ActiveX for instance.

There are multiple valid reasons for running old and outdated browsers. And multiple reasons for being unable to upgrade regularly. That’s why we can’t just cut off access to these users overnight.

# Master Plan (aka Conspiracy)

To kickstart the move towards up-to-date browsers, in March 2019 we came up with a very detailed plan, spanning over several months. When the idea arose for this plan, we were supporting:

- Safari 6+
- All Edge browsers
- Chrome 50+
- Firefox 27+
- Internet Explorer 11

The ultimate goal is to support only the following browsers and publicly advertise about it:

- Safari 12+
- Edge 17+
- Chrome 50+
- Firefox 45+ (version 45 in an ESR)
- No Internet Explorer version at all

In other words, we want users of unsupported browsers to be fully aware of that.

How did we set this in motion?

The whole plan is based on displaying a banner at the right time, to the right users, progressively over time. The banner explains that the browser is no longer supported and gives links to Chrome or Firefox upgrade help pages. The banner can be hidden for 5 days before appearing again.

<figure class="center">
<img src="{filename}/images/supporting-internet-explorer-11-in-2019/red-banner.png" alt="The top red banner we display on outdated browsers" />
<figcaption>The top red banner we display on outdated browsers</figcaption>
</figure>

We started off with Safari. Why is that? Users who run Safari most likely run Mac and thus use their own device, meaning they’re admin. Public healthcare centers or hospitals never run Apple devices in France or Germany. Plus Safari users are less than 10% of our users, so we thought it’d be quick to have these users upgrade to a newer version of Safari, or switch to Chrome or Firefox. Over the month of April, once or twice a week we increased the minimum supported version of Safari until we reached 12 (the latest stable version at the time of writing). Every few days, a new batch of users would be shown the banner. First, Safari 6 users would see the banner, then three days later Safari 7 users would also, and so on, until all users of Safari 11 or below saw the banner.

Over time, we could see the number of users for these unsupported versions significantly decreasing, while seeing Safari 12 slowly but surely rising. It did not work as well as expected though, some users probably don’t care too much about seeing a banner that can be closed anyway.

<figure class="center">
<img src="{filename}/images/supporting-internet-explorer-11-in-2019/safari-2.png" alt="Usage of unsupported Safari versions among Doctolib customers" />
<figcaption>Usage of unsupported Safari versions among Doctolib customers</figcaption>
</figure>

Next we decided to tackle Firefox users. Here, we needed to be extra cautious since users in hospitals are unlikely to be able to upgrade their browsers. Therefore, to avoid impacting user experience, we decided to do it in two phases. First, we repeated what we had done for Safari users with Firefox users not working in hospitals: progressively increasing the minimum supported version until we reached 45. In the meantime, we sent emails to the hospitals, letting them know that in the coming few weeks we’d stop supporting old Firefox browsers. Only then we started showing them the banner, until all Firefox users below version 45 saw it.

We repeated this process for Edge and Internet Explorer 11 afterwards. Eventually, when all unsupported browsers had the banner displayed, we decreased the time during which it could be hidden across page refreshes from 5 days to an hour.

All along we closely monitored our usage share of web browsers through dashboards and eventually saw usage of all unwanted browsers go downwards, as we expected them to, although not as fast as we predicted.

As it turned out, having our customers working in hospitals upgrade their browsers was much harder than we thought. People and organizations need time to adapt. It’s also our mission as a tech company providing an online service to educate our customers and explain why upgrading software is important.

<figure class="center">
<img src="{filename}/images/supporting-internet-explorer-11-in-2019/ie.png" alt="Usage of IE among Doctolib customers" />
<figcaption>Usage of IE among Doctolib customers</figcaption>
</figure>

<figure class="center">
<img src="{filename}/images/supporting-internet-explorer-11-in-2019/firefox.png" alt="Usage of ESR Firefox versions among Doctolib customers" />
<figcaption>Usage of ESR Firefox versions among Doctolib customers</figcaption>
</figure>

# Conclusion

Eventually, we reached all the goals we had in terms of browsers we officially support, even though there is a significant portion of our customers still using unsupported browsers. We’ve already committed to repeat this plan in a few months to try to get rid of Firefox browsers below version 50 and Chrome browsers below 60.

What did this plan teach us? First of all, we can’t force users to upgrade their browsers. We can’t decide what they use. They have constraints of their own and we must take them into account. However, good communication and giving them time brings long lasting relationships and builds trust between us and our customers, which is a requirement if we want them to use the right tools.

Also, such an ambitious plan cannot be executed overnight. Internal communication is key and all the relevant departments must be involved as soon as possible, e.g. Customer Service, Account Management, Tech, etc. It works best when everyone is aligned and knows what we are aiming for and why.

*Originally published on [Medium](https://medium.com/doctolib/supporting-internet-explorer-11-in-2019-12dc1f1ebc3c).*
