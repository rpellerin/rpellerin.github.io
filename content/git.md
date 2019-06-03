Title: Git
Date: 2015-06-03 00:01
Modified: 2016-09-28 23:01
Category: Linux
Tags: presentation, talk, git
Slug: git
Authors: Romain Pellerin
Summary: All about Git

Here are two talks about Git I gave in the last two years, at [HumanTalks Compiègne](http://humantalks.com/cities/compiegne). I added the slides as well.

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/8d04CYP5U9Q?rel=0" frameborder="0" allowfullscreen></iframe>

<iframe width="700" height="600" src="http://romainpellerin.eu/slides/embedder.html#git/slides.html" allowfullscreen></iframe>

[Slides are available in HTML](http://romainpellerin.eu/slides/git/slides.html).

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/9gdub0OMC8Y?rel=0" frameborder="0" allowfullscreen></iframe>

<iframe width="700" height="600" src="http://romainpellerin.eu/slides/embedder.html#continuous-integration-done-right-by-leveraging-git/slides.html" allowfullscreen></iframe>

[Slides are available in HTML](http://romainpellerin.eu/slides/continuous-integration-done-right-by-leveraging-git/slides.html).

# Going further

Here are some resources to go deeper with Git.

## General

- [Tech Talk: Linus Torvalds on git](https://www.youtube.com/watch?v=4XpnKHJAok8)
- [Git Version Control Tutorial](http://www.cirosantilli.com/git-tutorial/)
- [Git from the inside out](https://codewords.recurse.com/issues/two/git-from-the-inside-out)
- [Learn git concepts, not commands](https://dev.to/unseenwizzard/learn-git-concepts-not-commands-4gjc)
- [gitignore.io: auto generate .gitignore files](https://www.gitignore.io/)
- [gitignore: A collection of useful .gitignore templates](https://github.com/github/gitignore)
- [Git - Difference Between 'assume-unchanged' and 'skip-worktree'](http://stackoverflow.com/questions/13630849/git-difference-between-assume-unchanged-and-skip-worktree)
- [How We Cut Latency Down by 30k% on Our Git Server](https://www.clever-cloud.com/blog/engineering/2015/06/09/git-server-30k-improvement/)
- [Gitrob – Évitez la catastrophe sur Github](http://korben.info/gitrob-evitez-la-catastrophe-sur-github.html)
- [What does "git pull --rebase" do?](http://gitolite.com/git-pull--rebase.html)
- [The magical (and not harmful) rebase](http://jeffkreeftmeijer.com/2010/the-magical-and-not-harmful-rebase/)
- [The mighty reflog and the amazing bisect](http://jeffkreeftmeijer.com/2010/the-mighty-reflog-and-the-amazing-bisect/)
- [More productive Git](https://increment.com/open-source/more-productive-git/)
- [Notre cours vidéo GitHub est sorti !](http://www.git-attitude.fr/2015/12/18/learning-github/)
- [Learn Enough Git to Be Dangerous](http://www.learnenough.com/git-tutorial)
- [A Git Horror Story: Repository Integrity With Signed Commits](https://mikegerwitz.com/papers/git-horror-story)
- [How to Shrink a Git Repository](http://stevelorek.com/how-to-shrink-a-git-repository.html)
- [How to squash all git commits into one?](http://stackoverflow.com/questions/1657017/how-to-squash-all-git-commits-into-one)
- [Gitminer – Pour fouiller Github en profondeur](http://korben.info/gitminer-fouiller-github-profondeur.html)
- [Git reset : rien ne se perd, tout se transforme](http://www.git-attitude.fr/2016/05/11/git-reset/)
- [Flight rules for git](https://github.com/k88hudson/git-flight-rules)
- [Entrer dans les entrailles de Git, ou comment faire un commit sans faire du Git (Alexandre Garnier)](https://www.youtube.com/watch?v=Hd_UpJPDlik)
- [Bien utiliser Git merge et rebase](https://delicious-insights.com/fr/articles/bien-utiliser-git-merge-et-rebase/)
- [Auto-squashing Git Commits](https://robots.thoughtbot.com/autosquashing-git-commits)
- [git worktree](https://git-scm.com/docs/git-worktree#_examples)
- [Git stuff](http://sethrobertson.github.io/)
- [Git Tip of the Week: Merging Revisited](http://alblue.bandlem.com/2011/10/git-tip-of-week-merging-revisited.html)
- [Git caret and tilde](http://www.paulboxley.com/blog/2011/06/git-caret-and-tilde)

## Rebasing and cherry-picking

Rebases mostly use `git am`, which given diffs apply them sequentially. Diffs are computed between a given commit and its parent.

On the other hand, cherry-picks uses a three-way merge to notice file renames.

More on the topic below:

- [What algorithm is used during Git rebase?](https://stackoverflow.com/questions/39279901/what-algorithm-is-used-during-git-rebase)
- [Issue with cherry pick: changes from previous commits are also applied](https://stackoverflow.com/questions/42530381/issue-with-cherry-pick-changes-from-previous-commits-are-also-applied)
- [In a Git cherry-pick or rebase merge conflict, how are BASE (aka “the ancestor”), LOCAL, and REMOTE determined?](https://stackoverflow.com/questions/10058068/in-a-git-cherry-pick-or-rebase-merge-conflict-how-are-base-aka-the-ancestor)

## Auto deployment

- [How To Set Up Automatic Deployment with Git with a VPS](https://www.digitalocean.com/community/tutorials/how-to-set-up-automatic-deployment-with-git-with-a-vps)
- [Setting up Push-to-Deploy with git](http://krisjordan.com/essays/setting-up-push-to-deploy-with-git)
- [One-click App Deployment with Server-side Git Hooks](http://www.sitepoint.com/one-click-app-deployment-server-side-git-hooks/)
- [Deploying With Git](http://williamdurand.fr/2012/02/25/deploying-with-git/)
- [Git-Auto-Deploy](https://github.com/olipo186/Git-Auto-Deploy)

## Commit messages

- [How to Write a Git Commit Message](http://chris.beams.io/posts/git-commit/)
- [Commit messages are not titles](http://antirez.com/news/90)
- [Git your act together](http://jeffkreeftmeijer.com/2010/git-your-act-together/)
- [The Art of the Commit](http://alistapart.com/article/the-art-of-the-commit)
- [Conventional Commits](https://conventionalcommits.org/)

## Sub-projects management: submobules and subtrees

- [Using Git subtrees for repository separation](https://makingsoftware.wordpress.com/2013/02/16/using-git-subtrees-for-repository-separation/)
- [Submodules GIT: pourquoi, comment ?](http://blog.jingleweb.fr/2013/10/submodules-git-pourquoi-comment/)
- [Comprendre et maîtriser les submodules Git](http://www.git-attitude.fr/2014/12/31/git-submodules/)
- [Subtree Merging (official documentation)](https://git-scm.com/book/en/v1/Git-Tools-Subtree-Merging)
- [Mastering Git subtrees](https://medium.com/@porteneuve/mastering-git-subtrees-943d29a798ec): `git read-tree` and `git subtree` are two different commands.
- [The power of Git subtree](https://developer.atlassian.com/blog/2015/05/the-power-of-git-subtree/)

## Tricks

- [Git diff to HTML](https://pypi.python.org/pypi/ansi2html/1.0.7)
- [Little Things I Like to Do with Git](https://csswizardry.com/2017/05/little-things-i-like-to-do-with-git/)
- [DevFest Nantes 2018 - Git Dammit !](https://www.youtube.com/watch?v=Rnh5QK__pLA)
