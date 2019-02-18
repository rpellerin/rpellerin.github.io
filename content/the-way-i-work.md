Title: The Way I Work
Date: 2019-02-17 23:20
Category: About me
Tags: work
Slug: the-way-i-work
Authors: Romain Pellerin
Summary: My daily routine to focus and remain productive

A few months ago I stumbled upon [this article on productivity](http://blog.samaltman.com/productivity). I found it very inspiring. It somehow convinced me to rethink the way I work. Since then, I've been actively working on perfecting my daily workflow to be more productive and also more organized. Reducing my stress level was also one of my goals.

I think having a clearly defined and established workflow is more important than most people think. I find habits to be comforting. They help me concentrate and focus on what matters.

The advice given in this article doesn't apply to everybody. Being a software engineer has its own constraints and requirements. In this article, I speak about work environment, tools and fighting physical and mental fatigue.

# TODO.txt

I think the most important task one has to do daily is defining a list of priorities for the day to come. It is very important to know what to work on and when.

I tried for a long time to keep a TODO list on a piece of paper (or in a booknote). It was working, but not as well as I wished. The main problem was that I needed to be physically present at my desk to access my list or edit it. That's why I eventually switched to a digital TODO list. Many people resort to apps for that, Evernote being one of the most popular I believe.  I however use a simple text file. I find these note taking apps too distracting, with countless features I would never use. What I like about my text file is the simplicity of it. Plus I can access it at any time, as long as I am carrying my work laptop. Plus I am not tempted to access it outside of office hours.

Reading and editing my TODO list is something I do many times throughout the day, as I need to re-arrange my priorities and when I get work done. Moreover, at the end of each day, I make sure to prepare my TODO list for the following day.

My TODO list is a simple plain text file yet it has its own sections, separated by empty lines. The topmost lines of the file are the tasks I need to get done by the end of the day, sorted from the most urgent one to the least. Then, the second section contains tasks that must be worked on in the short term, preferably in the month to come. Finally comes the third section which is a backlog of tasks I want to work on some day.

I've been putting all this into practice for a few months now and I must admit it's working well for me. No distractions, simplicity, I can add/edit/remove entries with a few keystrokes, I can also make backups or send it over emails in seconds. In short, I love it. A stress-free TODO list, always up-to-date.

Below is an extract of my list, in its current state:

    :::text
    - Finish feature 1691
    - Book plane tickets and hotel for Berlin trip

    - Delete 2 `const workshopVisitMotivesCountScenario = workshopVisitMotivesScenario(motives)` in `onChange`?
    - Schedule meeting front guild early April
    - Move Jenkins loc() to Heroku

    - Articles ideas
      - eslint
      - code/bundle splitting
      - download/parse/exec time
      - debugging with devtools (CSS, ...) in ffox and chrome (breaking point with mouse clicks, ...)

# Bookmarks

Bookmarks are an essential feature of any web browser. However, not everyone agrees on how to use it. Even I use bookmarks differently on my personal laptop than on my professional laptop.

Professionally, I use bookmarks for websites I rarely visit, so rarely that neither me or my browser can remember the URL. What I mean by the browser can't remember it is that it does not show up in the list of suggested URLs are I type in the URL bar. I sort my bookmarks by topic, in folders named according to their topic. A few examples of folders I have:

- *COMPANY-RELATED*: payrolls, holidays, expensify, etc
- *TO READ LATER*: lengthy articles I'll probably read some day while waiting for the CI tests to run
- *REACT TRAININGS I GIVE*: Asana list to keep track of the sessions, useful links to share
- *1-ON-1*: contains stuff related to my quaterly objectives, the list of engineering roles and associated levels in my company, etc
- *TO PRESENT*: things I want to present at the next All Hands meetings

I never bookmark websites I visit daily, or frequently. Why? Because I can at least remember some part of the URL, or the title, and my browser is smart enough to autocomplete it and suggest it automatically. For websites I visit many times a day, like the Pull Requests page on Github, or my Twitter feed, I create keyboard shortcuts to access them instantly. For instance, Github Pull Requests is `<Super>+G` (G as in Github).

In the past, I used to have bookmarks for every website. It wasn't until recently when I switched to very few bookmarks that I noticed how stressful it was for me to have all these bookmarks being constantly displayed before my eyes.

# Opening new Pull Requests

Speaking of Github, I've got some interesting things to say about Pull Requests.

I basically open a new Pull Request every time I start working on a new feature, a new bug fix, or when I'm refactoring code. When the specs are not great, it's sometimes hard to know precisely what needs to be done, code-wise. How do I deal with this? First, I start working on what is obvious: be it the core feature, or creating a new empty React component, creating a new Rails controller, etc. Then I git commit, push and open the Pull Request. That first commit is usally quite small in terms of changes. Only then do I start listing what is "left to be done" in the Pull Request description. Github offers a convenient way to create TODO lists in markdown, with actual checkboxes that are checkable. Just write "`- [ ] Something to do`" and it'll be displayed as a list item prefixed with an unchecked checkbox.

When the TODO list is ready, I add the "WIP" label as well as my team label, and resume working.

# Inbox and Thunderbird

Any email people receive fall in one of these three categories:

- Action required on your side: it could be a quick action (like replying) or a time-consuming one (like filling a form)
- Temporal information
- Important information that must be kept for a long time, or files

It works essentially just like a mailbox. Mail people get in their mailboxes also fall into these categories: bills are actions required on your side, postcards are temporal information, payrolls are important information, parcels are like files, etc.

Nobody would open a mailbox, see what's inside, and leave it there. Therefore, it does not make any sense to have inboxes full of emails. I am doing Inbox Zero: I aim (and almost always succeed) at keeping my inbox empty. My strategy is as follows:

- I open my email client a few times a day, usually at the beginning of the day, before lunch, after lunch, a few times in the afternoon when I'm on code-breaks, and before leaving work.
- I process emails one by one:

    - If it's an "action required" email and the action takes less than 5 minutes, I do it immediately then delete the email.
    - If it's an "action required" email and the action takes more than 5 minutes, I keep the email in my inbox and add a new entry in my TODO list such as "*reply to email from X*" or "*do what X asked for*". I will then delete the email when the action is done.
    - If it's a temporal information, I take note of it if needed and delete the email immediately. A temporal information could be a Google Calendar invite, a JIRA edit from my colleagues, an incident reported by New Relic, etc.
    - If it's an important information, I move the email into the appropriate subfolder (they're called labels in Gmail). An important information could be a contract (PDF document), an organizational change, a POST-MORTEM document that I believe will be useful in the future, a train ticket that will eventually be deleted when the trip is done, etc.

As I just said, I use Gmail labels quite a lot, in the sense that many emails end up in labels. However, I only have a handful of them. Here are a few:

- Trips: business trips (train tickets, hotels, etc)
- Front stack: any important email regarding frontend
- Archives: anything that does not belong to another category

I also rely on Gmail filters extensively. I get a lot of emails I don't need to see at all, that I want deleted automatically. For instance, I often check the notifications on Github, therefore I don't need to also see the emails they send me. Also, our staging environment is deployed with every new commit (every 10 minutes or so), therefore I don't need to know every time a new commit is deployed.

Finally, I must confess I use Thundebird, and not the Gmail website. And I'm proud of it. The reasons I use Thunderbird are listed below:

- It works offline: I can access my emails when not connected to the Internet. Even though it happens very rarely, it's very handy when in the train for instance.
- Gmail is a website, and not a lightweight one. The tab consumes a lot of RAM memory, and opening/closing/opening the websites several times a day is not a better alternative, as it takes many seconds.
- Gmail is distracting: there's Google Hangout in the left bottom corner, many buttons here and there.
- I don't like the conversation-grouping feature of Gmail, I never got used to it. Thunderbird also has it but I disabled it.
- Thunderbird is simple, RAM-efficient. And having a distinct program for emails help see it as something important, it's not just another tab.

# Slack and distractions

[I hate Slack]({filename}/slack.md). But I have to deal with it. Slack is distracting (notifications mostly), there's a lot of noise and not much information. There are two-people conversations involving everyone, there are company-wide conversations to which only two people are contributing. There's the fear of missing out, and the useless necessity to read all the unread messages when coming back from days off. Plus important messages and notices get often lost in the never-ending flow of messages. It becomes hard to differentiate what's important from what is not.

So my strategy here is very simple: I open Slack every time I open Thunderbird. Sometimes, I leave it open for longer periods of time, when I'm actively engaged in a conversation. But overall, I try to use it as little as possible.

# Open spaces, headphone and sound

[I also hate open spaces]({filename}/open-offices.md). Everyone is forced to share everyone else's emotions. Everyone is an indirect contributor who did not volonteer to someone else's conversation.

The main problem with open spaces is sound, or most specifically noise. People talking out loud, laughing, going to the bathrooms, etc. I'm fighting this with a headphone. I wear it almost all the time, most of the time with no music playing. It acts as a symbolic barrier, it helps my brain isolate itself and focus. When I am actually listening to music, it has to be either a song I've heard a thousand times, so that I'm already used to it, or some lyricless song. It helps me get in the zone.

# Standing up

Moving on to the physical section now... I've been using [a standing desk](http://www.thefreedesk.com/) for a year now, at work. And I am entirely satisfied. I alternate between sitting and standing throughout the day, sometimes several times. [It has many advantages]({filename}/a-sedentary-life.md). I am feeling more energetic overall.

# Sports

I recently started going to the gym many times a week. I am lucky enough to have a gym in the very same building, where I work. My sessions are usually split in two: first I do 15 minutes of jogging, then 30 minutes of workout. In a matter of weeks, I have noticed so much improvement on my sleep quality and productivity during the day. Short nights don't have a toll on me anymore, like they used to. Not that it's advisable or that I like it, but when it happens I can easily handle a few short nights in a row now. Not to mention the slight weight loss. I usually go 2 or 3 times a week, after work.

# Conclusion

This is it. That's how I work. I hope you found something useful, or maybe you've learned something? I think most people don't pay enought attention to their daily routine and workflow. They don't seek improvement and don't see the importance of being healthy. There are many ways to reduce stress while increasing productivity. It could be a tiny habit to change or a big one. I think everyone should reflect on that every once in a while, and try to improve the way they work.

Cheers!
