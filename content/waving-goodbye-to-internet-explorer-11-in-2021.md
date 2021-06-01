Title: Waving Goodbye to Internet Explorer 11 in 2021
Date: 2021-06-01 23:30
Category: Code
Tags: internet explorer, browsers
Slug: waving-goodbye-to-internet-explorer-11-in-2021
Authors: Romain Pellerin
Summary: Two years ago, we published “Supporting Internet Explorer 11 in 2019”. This year, we are bidding farewell to it. But hold on a minute! What happened between then and now? Let me tell you the story of a difficult break up between Doctolib and Internet Explorer…
Image: waving-goodbye-to-internet-explorer-11-in-2021/waving-goodbye-internet-explorer.png

*Originally published on [Medium](https://medium.com/doctolib/waving-goodbye-to-internet-explorer-11-in-2021-b01a8c5c9864).*

Two years ago, we published "[Supporting Internet Explorer 11 in 2019]({filename}/supporting-internet-explorer-11-in-2019.md)". This year, we are bidding farewell to it. But hold on a minute! What happened between then and now? Let me tell you the story of a difficult break up between Doctolib and Internet Explorer... ([and we're not the only ones](https://www.theverge.com/2021/5/19/22443997/microsoft-internet-explorer-end-of-support-date) 🤫)

# Motivations

Back in 2019, a major motivation for us to stop supporting old browsers on the website healthcare professionals use to access our service was that we had two different pipelines to bundle JavaScript code: one was through Webpack and [Babel](https://babeljs.io/), the other one through the [Ruby on Rails asset pipeline](https://guides.rubyonrails.org/asset_pipeline.html) (also known as Sprockets). The good news is, last year we managed to get rid of Sprockets. Now, all of our JavaScript files go through Webpack. Therefore, we do not need to write ES5-compliant code anymore, we can write modern JavaScript everywhere in our mono-repository. However we still have to instruct Babel to transpile some packages that do not come pre-transpiled. And let's be honest, monitoring breaking changes as library authors release new versions is no fun at all.

Our other motivations were:

-   Cost of maintaining additional integration tests running on old browsers. Back then we were using Browserstack, we since then moved to Lambdatest, because we found it to be more reliable and noticed less random test fails. But that still remains a pain to maintain. Not only does Lambdatest occasionally fail, but also some specific browser versions do not mingle well with [Capybara](https://github.com/teamcapybara/capybara) (our test framework). For instance, we found that [`click_on`](https://rubydoc.info/github/jnicklas/capybara/Capybara/Node/Actions#click_link_or_button-instance_method) would consistently not work with Safari 13 in some pages.
-   We still have occasional down incidents. One of the recent ones was due to `scrollTo` being used with options, which is [not supported by Internet Explorer 11](https://developer.mozilla.org/en-US/docs/Web/API/Window/scrollTo#browser_compatibility). And of course, Babel can't do anything about it.

These motivations are still valid to this day. On top of that, since 2019, we have developed new features and brought [end-to-end encryption everywhere in the app](https://info.doctolib.fr/blog/doctolib-adopte-le-chiffrement-de-bout-en-bout-nouvelle-etape-dans-la-securisation-des-donnees-de-sante/). Yet, some of these old browsers have limited support for some of the web APIs we are using. As a consequence, these users can not enjoy the complete feature set Doctolib has to offer, and they are unable to see encrypted documents (PDF documents for instance), as encryption/decryption is done in the browser. Therefore, at the beginning of this year, we initiated a new project that we like to call "Browser Upgrade Campaign: reborn".

# Browser Upgrade Campaign: reborn

Admittedly, our previous attempt at having our customers upgrade their browsers was a failure. We are trying to get it right this time. The timeline we laid out was as follows:

<figure class="center">
<img src="{filename}/images/waving-goodbye-to-internet-explorer-11-in-2021/timeline.png" alt="The image of the timeline" />
<figcaption>Timeline of our new project to reduce the use of outdated browsers, over 2021</figcaption>
</figure>

Regarding the list of browsers we wanted to keep supporting, we kept the same list as in 2019, with one minor change: we would no longer support Edge 17. Here are the browsers we officially support from now on, on our pro website:

-   Safari 12+
-   Edge 18+ (18 is the last non Chrome-based version of Edge)
-   Chrome 50+
-   Firefox 45+ (version 45 in an Extended Support Release)
-   No Internet Explorer version at all

Preventing the use of non supported browsers internally was an easy win. All it took was good communication and the flip of a switch. After that, any Doctolib employee trying to use an outdated browser would be welcomed by the following page:

<figure class="center">
<img src="{filename}/images/waving-goodbye-to-internet-explorer-11-in-2021/page.png" alt="A screenshot of the page preventing the use of Doctolib" />
<figcaption>The page preventing people from using an outdated browser</figcaption>
</figure>

The two following steps were almost as easy. After setting up some tracking through New Relic, we realized we did not have that many new connections on outdated browsers every day. What's a new connection? It's a connection from a browser that never connected to Doctolib before. How do we determine whether it ever accessed Doctolib before? We look for existing cookies sent in the request.

Based on that fact, we first blocked any new connection on Internet Explorer 11. Then, over the next few days, we monitored the amount of support tickets and phone calls: nothing. No one complained. We then did the same operation with other deprecated browsers.

Then came the fourth step.

# Walking on eggs

Given that there had been a red banner displayed at the top of Doctolib for the last two years to users surfing with outdated browsers, our users were well aware of that. But of course we knew it was not enough. Why would our graphs still show relatively high usage of outdated browsers, uh?

In April and May we sent out two email campaigns: one to remote secretaryship centers, another one to key accounts (most of them being hospitals or Covid-19 vaccination centers). This time, unlike in 2019, we explicitly told them which users were still using non supported browsers and gave them a deadline after which we would no longer be able to use these browsers. We made sure to tell them which obsolete browsers they were using and what options they had to upgrade.

In the meantime, we compiled a big list of user accounts that were using multiple browsers, both supported and non supported ones. Every day, we'd pick a bunch of them and enable a feature switch on their account, that would prevent them from logging in with a non supported browser. Once again, we daily monitored our support, making sure everything was going as smoothly as possible.

And it finally happened for good this time: the curve went down and eventually reached zero.

<figure class="center">
<img src="{filename}/images/waving-goodbye-to-internet-explorer-11-in-2021/graph.png" alt="A graph showing the use of Internet Explorer over time" />
<figcaption>Use of Internet Explorer among healthcare professionals on Doctolib</figcaption>
</figure>

After the feature switch had been flipped for all of our users seamlessly, we removed the feature switch and hardcoded these browsers' name and versions directly in the codebase, to make sure no one would ever use them again.

This project is, as I am writing this article, coming to an end. Interestingly enough, in two years' time, our browser market share changed a lot, Internet Explorer slowing fading out into oblivion... and Microsoft Edge taking off!

<figure class="center">
<img src="{filename}/images/waving-goodbye-to-internet-explorer-11-in-2021/market-share.png" alt="Browser market share over time" />
<figcaption>Browser market share on Doctolib pro</figcaption>
</figure>

<hr />

*If you want more technical news, follow our journey through our [docto-tech-life newsletter](https://doctolib.engineering/engineering-news-ruby-rails-react/).*

*And if you want to join us in scaling a high traffic website and transforming the healthcare system, we are hiring talented developers to grow our tech and product team in France and Germany, feel free to have a look at the [open positions](https://about.doctolib.com/jobs?department=Engineering).*
