Title: Continuous Integration
Date: 2015-09-18 11:29
Modified: 2016-01-09 18:30
Category: Code
Tags: code, workflow, git
Slug: continuous-integration
Authors: Romain Pellerin
Summary: Why you should embrace continuous integration and how

After having experienced software development in many languages, from desktop programs to mobile applications and web sites, comes a moment when I felt compelled to standardize my software development process. In the last couple of months, we, at [The Smiths](http://www.wearesmiths.com/), tried to define our workflow more formally by integrating continuous integration. How to properly "synchronize" a team made of many developers, "spread all around the world", in different time zones? How to make sure there is no bottleneck at any level, during the development? First, let’s have a look at the definition.

What is **continuous integration**? According to [Wikipedia](https://en.wikipedia.org/wiki/Continuous_integration), it's:

> _[...] the practice, in software engineering, of merging all developer working copies to a shared mainline several times a day._

But not only. It's much more than this. Let's dive into continuous integration.

# Purpose

Continuous integration is an entire concept that tries to ease software development by making things much easier and more flexible. Like agile methodology, it's been getting more and more popular since the beginning of the 2000's. Nowadays, it tends to be widely adopted in any kind of software company.

## Effortless automation

Continuous integration is mostly about automation. By this, I mean being able to deploy flawlessly, many times a day. Like [Quentin Adam]({filename}/i-want-to-be-a-more-efficient-developer.md) is used to saying,

> _[Hosting has to become a commodity.](https://twitter.com/waxzce/status/639078610230476800)_

The same applies for documentation generation, testing and deploying. Repetitive tasks are most of the time painful and should be performed by computers.

## Continuous testing

**Every new release should be tested.** Automatically. Without any human intervention. In a safe and minimal environment, not a human's environment with tons of programs, custom environment variables, outdated dependencies, etc. It's a human job to design the tests but running and validating them is a machine job. This way, the results can be shared with an entire team over automatic emails and archived.

## Documentation

Writting documentation is for humans. Generating it is for machines. Again, it's a repetitive task that no one should be assigned. It's a waste of time. Machines can do it instead of us, at the right time, usually when a new release is about to come out. Furthermore, machines don't forget to bump the version numbers.

## Deploying

With continuous integration, deploying should be as simple as a [`git push`](https://twitter.com/Tim_LHUILLIER/status/396652013716770816). When time has come for deployment, no one has to use `ssh` or `rcp`, not even `ftp`. **It's unsafe insofar as it's too much of a risk to let a human get inside a production machine.** A developer has nothing to do in a production machine, as long as all the tests have been passed before pushing to production. All one can do is breaking something. A production machine is meant to be administrated by a sysadmin, no one else. Once it's set up, the only action required by developers is pushing code when it's ready to go live.

It's the same story for releases. Packaging and releasing a new version **must** be a simple task anyone can do, not only the CTO, regardless of the OS, configuration, and so on.

# Why

Now that I exposed the principles of **continuous integration**, the question you may want to ask is "Yes, but why?". Indeed, prior to continuous integration, everything was already working well, more or less. So, you might wonder how anyone can benefit from continuous integration.

In fact, continuous integration should simply be regarded as an entire development workflow. It enables your team of developers (or even a single developer) to be more agile. You can easily get rid of god-awful tasks without actually deleting them, just by delegating them to computers.

In parallel, you can improve maintainability and evolutivity of the software produced by adopting the right versioning and bug tracking tools. How? Let's take an example.

Remember in the old days, when you used to package your code into a zip file and name it according to the version? How boring it was to keep all of those files. Was it easy to track differences between two given versions? How could you determine the changes? How could you apply hotfixes on an old (but still in use) version as well as on the latest one? How could you know which developer had written which feature? Here come the version control systems, or more simply, Git.

Another example. For every new version (and sometimes between two) you had to set up your environment correctly, generate the documentation from source code, bump the version number in both code and documentation, run unit/integration/system tests and finally, if everything goes well, compile it for **as many platforms as you support** (which quite often would mean a lot), and make it available on a website (or somewhere else). Well, how pleasant would it be if **all of this** could be achieved right after a `git push`?

Let's now see how to get started with continuous integration, easily.

---

# How

Now, you might have understood that you **have to use Git**. And if you stil don't know why, you read read my article [about Git](|filename|/git.md).

In my opinion, the most important thing with continuous integration is... your Git workflow. You have to remain consistent from the beginning of a project to its end. Everyone involved has to be careful and focused at each step. Working within a big team is not something easy. Everyone has different schedules, information doesn't spread that well. That's why having a robust and error-proof Git workflow matters.

At The Smiths, we identified **four main components** that we wanted as **core parts of our Continuous Integration process**:

- Semantic versioning
- A Git workflow
- Travis-ci.org
- Agile methodology

## Semantic versioning

We decided to stick to the very simple rules proposed by [semver.org](http://semver.org/). Basically, it says that a version number should be something like _MAJOR.MINOR.PATCH_, where (what follows has been extracted from their website):

1. MAJOR version when you make incompatible API changes
2. MINOR version when you add functionality in a backwards-compatible manner
3. PATCH version when you make backwards-compatible bug fixes

Sticking to this rule forced us to think more about the logic behind the versions, reshape road maps more precisely, and allowed us to bump our version numbers more cleverly. We applied this to our mobile apps and Titanium widgets, bumping version numbers carefully over time.

## A Successful Git Workflow

This part is clearly the most important, as it’s being used every single day by everyone. In general, it’s likely the most important aspect about continuous integration, to which we had paid a lot of attention.

After some research, we came across a very popular Git workflow, the branching model.

![Git workflow]({static}/images/continuous-integration-git-workflow.png)

This picture has been taken from _[A successful Git branching model, by Vincent Driessen](http://nvie.com/posts/a-successful-git-branching-model/)_ ([thanks to him](https://twitter.com/nvie/status/644601079985008640)). Overall, it’s the best Git workflow we’ve ever encountered. **So we adopted it, with some specificities: we made a few minor changes to that original branching workflow, in order to better suit our needs.** It can be applied to any kind of software project. I also need to mention that we use GitHub, the most famous web-based Git repository hosting service, because it offers some great features, such as Pull requests, a bug tracker (issues), etc. Moreover, as most of our code has been open sourced (except our clients’ core apps), it’s not a problem at all. By the way, it also gives us a greater visibility, it’s like giving away our widgets to the Titanium community thanks to GitHub.

Now, let's move onto **our workflow** that we daily use, with Github:

### Our Git workflow

- There is a remote repository called `origin`. Then, every new developer (including the owner) has to fork this repository. The `origin` repo will **ONLY** contains two branches: `master` and `develop`.
  - `master`: contains **only the stable realeases** (General Availability), tagged with  
    `git tag -a`  
    Those are production releases.
  - `develop`: contains **only stable code** with newly developed features. This branch has been created with  
    `git checkout -b develop master`  
    It’s the latest cutting-edge release (Release Candidate). It may contains some bugs.
- No one is allowed to commit into `master` nor into `develop` (except for the first commit into `master`, then `develop` is forked from `master`), in either `origin` or the forked repository. Not even locally!
- There's **one developer responsible for the entire project**. Let's call him/her _Integrator_.
- To add a new feature, one of the developers create a local branch called `feature-x` from `develop` and commit into it. He/she may push this branch to their own forked repository.
- When this feature is fully developed and tested, and ready to go into `develop`, the developer creates a _Pull request_ from the forked repository to `origin` (from branch `feature-x` to `develop`). Then, **only one person can accept it and merge it: _Integrator_.** He/she can also refuses it and comment about the reason(s).
- If the _Pull request_ has been accepted, everyone has to update their `develop` branch (locally and on their forked repo).
- The same applies for hotfixes: anyone can create branches called `hotfix-y` and create _Pull requests_ from them.
- When time has come for a new release, _Integrator_ merges back `develop` into `master` and tags it with a release number (according to the Semantic versioning). Then, anyone update their `master` branch.
- Everyone is strongly encouraged to rebase on top of `master` as often as possible, especially before submitting PRs.

In case you're working alone on a project, no need to fork anything. Just apply the same principles to the only remote repository (_Pull requests_, merge, etc). You can skip _Pull requests_ if you want so, it just helps keeping a trace of every new feature.

I've found out that this workflow works pretty well with any of our projects, might it be an entire app or a mere widget. However, as I said we've made some adjustments to the original workflow found on the Internet, making it a bit simpler to use.

**Issues** are also great features brought by GitHub that allow us to track down bugs and list them. One can assign people to fix an issue, and let others know of its progression thanks to emails or notifications. Issues can also be linked to specific commits which helps a lot when tracking regression bugs.

Finally, we're also taking advantage of **Milestones**, a GitHub feature, which can be seen as a simplified scheduler, permitting us to create deadlines with goals to reach. It's somehow a list of deadlines with progress bars automatically filled as we would create and merge Pull requests.

#### Some commands

To generate the message for a Pull request:

    :::bash
    git log --pretty=oneline --abbrev-commit <branch-from>..<current-branch>
    # Prints the commits you made since last merge and the associated messages

To update `develop` (works with `master` as well):

    :::bash
    git fetch origin
    git checkout develop
    git merge --ff-only origin/develop
    # Only fast forward is important to keep the history clear and clean

To update a feature branch (after a PR has been merged):

    :::bash
    git fetch origin
    git checkout feature-x
    # Three options (choose only one):
    # 1.
    git merge --ff-only origin/develop
    # 2. If this fails because you have commits ahead, do:
    git rebase origin/develop
    # 3. If you're a badass and want to ignore your own changes:
    git reset --hard origin/develop

To merge a _Pull request_ (only **Ingrator** is supposed to do it) and push it:

    :::bash
    git remote add feature-x_repo git@github.com:someone/forked-repo.git
    git fetch feature-x_repo feature-x
    git checkout develop
    git merge --no-ff feature-x_repo/feature-x # You could add --no-commit to edit files before committing
    # 'no fast forward' is important to keep the history clear and clean
    git push origin develop

To delete a branch (locally and remotely) once it has been merged into the main repository:

    :::bash
    git push feature-x_repo --delete feature-x
    git branch -d feature-x

### Contributors

There's another approach to the scheme above. Instead of using only _Pull requests_, you could add other developers as [contributors](https://help.github.com/articles/setting-guidelines-for-repository-contributors/). This way, there's no Mr/Ms **Integrator**, any contributor is free to merge their features into `develop`. Also, feature branches can be on the remote repository.

### Git branching model considered harmful

The Git workflow we used as a starting point (the branching model) has been considered harmful [by someone, and thus that person proposed another model](https://barro.github.io/2016/02/a-succesful-git-branching-model-considered-harmful/). The reading is worthwhile although I'm still not fully convinced by this alternative.

## [Travis](https://travis-ci.org/), the all-in-one tool

Soon, Travis will be your best friend. But first, what is Travis?

Basically, it's an online service delivered through a website, providing sets of virtual machines started especially for a developer whenever needed. Those machines run some scripts and shut down themselves automatically. Usually, a developer sets up their Travis account to run scripts on every `git push` performed on a GitHub repository. However, one can configure Travis to be run with any kind of Git hook. **Travis is free** but they also have paid services with much more features (that we didn't require).

So, how is Travis so helpful for us?

### Most common use cases of Travis

Well, one can imagine dozens of scenarios. Documentation generated from the source on every new `git push` in `develop`? Easy. Running a whole set of tests automatically? Easy as well.

Let's dream a bit further... One of the most fancy things one can do is **running a set of tests, bump version numbers in the code and the documentation, compiling the software program and generating the updated documentation, pushing the binaries to Amazon S3 and the documentation to GitHub Pages, and making the whole set available to everyone, automatically!** And yes, Travis, can push into specific branches as well. That might be really useful to update web pages automatically (a link to the latest stable release, listed on a download page, for instance).

That is definitely the most perfect and automated scenario one could imagine, but that's easily achievable, although it would take quite a long time to set it up.

### An example with Github Pages

A good example is my blog (yes, the one you're reading). My articles are written in Markdown and contained in the branch `master`. However, what you're reading, is a [Github page](https://pages.github.com/) (contained in a branch called `gh-pages`). What is happening hunder the hood is that, every time I push on `master`, Travis fetches it, generates the HTML pages from the Markdown files, and pushes them to the branch `gh-pages` on Github. This way, my blog gets updated automatically.

I followed [this tutorial](http://blog.mathieu-leplatre.info/publish-your-pelican-blog-on-github-pages-via-travis-ci.html) to do that (and [this presentation](http://fle.github.io/lectures/pelican-github-2014.html) as well).

#### How does it work

At the root of your repo, just add a _.travis.yml_ with some pieces of configuration inside. And you're done! A few more options are available on their web interface as well.

You can have a look at [my .travis.yml for this blog](https://github.com/rpellerin/blog/blob/master/.travis.yml).

### Tips with Travis

- [Get a new token from Github](https://github.com/settings/tokens) in order to give Travis access to your repo
- In travis, disable _Build on Pull requests_, enable only if .travis.yml is present

## Agile methodology

Last but not least, agile methodology. Behind that term that sounds rather complex is a concept pretty simple. In Computer Science, we use the term "Agile Software Development". Wikipedia says:

> _Agile Software Development is a set of software development methods in which requirements and solutions evolve through collaboration between self-organizing, cross-functional teams. It promotes adaptive planning, evolutionary development, early delivery, continuous improvement, and encourages rapid and flexible response to change._

There is also an official Agile Manifesto that tries to describe it more precisely, based on twelve principles. **From that original manifesto have appeared dozens of practices that we refer to as "Agile methods"**. I won't explain them here as it is not the purpose, for more information visit the [Wikipedia page](https://en.wikipedia.org/wiki/Agile_software_development).

The one we picked is called **Scrum**. Again, I won't explain it here but will instead expose the workflow we adopted, derived from it. For a better comprehension, I recommend you read the [Wikipedia page](<https://en.wikipedia.org/wiki/Scrum_(software_development)>).

Basically, at the very beginning of the project, the CTO would meet client and discuss with them. As a result, he would obtain detailed specifications from them. Then, all together, we would brainstorm and define Milestones, which are fundamentally the most important steps to a final product. For instance, from scratch, we would aim at developing a MVP (which is a working product, see the picture below), then progressively add features, grouping features under Milestones. As a reminder, "Milestones" is a feature of GitHub. Each Milestone would last a week, tops. In Scrum, Milestones are called _Sprints_.

Starting with an MVP is the right way to go. The client is always satisfied as they would get a working copy of the product being-developed, at each stage, continuously getting improved. They would not have to wait for weeks or months before being able to test anything.

![MVP]({static}/images/continuous-integration-mvp.png)

<div style="text-align: center">*Courtesy of [Henrik Kniberg](https://twitter.com/henrikkniberg/status/685474589539983360)*</div>

Then, each developer would get assigned or choose a few features from the first Milestone until completion. Every morning, we would gather for a 10-minute stand-up meeting, during which everyone would say what they did the previous day and what is left to do, especially regarding the current day. Standing forces us to speak concisely and don't waste each other's time, since it is not as comfortable as being sat down.

At the end of the Milestone's week, we would merge all the Pull requests resulting from the features, then review all together all the work done, conduct tests and create issues on GitHub to be resolved before starting the next Milestone. Eventually, we would readjust the remaining Milestones when needed.

This would go on until completion of the final Milestone, which is supposed to bring all the features originally requested by the client.

![SCRUM]({static}/images/continuous-integration-scrum.png)

Sometimes, the part "Bug fixing" would require extra-time. In such a case, we would extend that part onto the next week, only assigning one person, so that the rest of the team could stick strictly to the rule "a Milestone per week". But that rarely happened fortunately.

## Mix them all

As a brief conclusion, when thinking back at all the parts of our workflow, one can see that it's an entire whole in which GitHub plays a key-role. Being organized, knowing everyone's role and keeping in mind goals to reach are important as well, just like the tools we decided to go with. Furthermore, being able to release intermediate versions, quickly, allowed us to give constant feedback to the client, which is reassuring.

This workflow is surely perfectible, but until now it has proved really efficient.

<br />
I hope this article was helpful. It's a good start with Continuous Integration. First, focus on your workflow (especially with Git) and then set up the right tools (including Travis) and you're good!

# Resources to go deeper

- [Introduction au déploiement continu](http://putaindecode.fr/posts/ci/le-deploiement-continu/)
- [Qu'est-ce que l'intégration continue ?](http://putaindecode.fr/posts/ci/introduction/)
- [You're Doing Agile Wrong](http://blog.ircmaxell.com/2014/10/youre-doing-agile-wrong.html)
- [SOFTWARE DEVELOPMENT EXPLAINED WITH CARS](https://toggl.com/developer-methods-infographic)
- [Continuous Integration](http://www.martinfowler.com/articles/continuousIntegration.html)
- [Déploiement continu avec Travis-CI (et GitHub Pages)](http://putaindecode.io/fr/articles/ci/travis-ci/)
- [Scrutinizer](https://scrutinizer-ci.com/)
- [GitHub Flow](http://k33g.github.io/2016/11/27/GH-FLOW.html)
- [Intégration et déploiement en continu @ Github (Alain Hélaïli)](https://www.youtube.com/watch?v=jCwzf9adAtE)

## Android

- [Automating Publishing to the Play Store](https://github.com/codepath/android_guides/wiki/Automating-Publishing-to-the-Play-Store)
- [PUBLISH TO GOOGLE PLAY - CONTINUOUS DELIVERY FOR ANDROID (PART 4)](http://blog.stablekernel.com/deploying-google-play-continuous-delivery-android-part-4/)
- [DevOps on Android: From one Git push to production](http://jeremie-martinez.com/2016/01/14/devops-on-android/)
