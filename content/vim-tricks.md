Title: Vim Tricks 
Date: 2017-01-18 22:50
Category: Linux
Tags: vim, vi, linux
Slug: vim-tricks
Authors: Romain Pellerin
Summary: A few tricks for Vim that I have learned over all my years of using this incredible tool.

# Regex

## Interactive find and replace

    :::bash
    :%s/foo/bar/gc

`%` means whole document, not just the current line.

`/g` means every occurence, not just the first one. `/c` is the interactive option.

## Capturing groups

    :::bash
    :%s/abc\(def\)ghi/ko\1ok/g

Will print `kodefok`.

## Non-greedy search

Normally, to search in a non-greedy way, we add a punctuation mark, as in `.*?` which means *any string of characters as short as possible*. For instance:

    :::bash
    /<.>(.*?)<.>/

Applied to the string `abc<p>def<a>ghi<b>jkl` would match `<p>def<a>` and capture `def`. To make non-greedy searches with Vim, replace `*?` with `\{-}`.

# Misc

## Split the screen in half and display two different parts of a given file

    :::bash
    :vsp
    :set scrollbind

Then, on the right pane, scroll down or up, further in the file, and do:

    :::bash
    :set scrollbind

From that point on, as you scroll on one pane, the other one will follow. To undo:

    :::bash
    :set noscrollbind

# Useful links

- [Vim Cheat Sheet](https://vim.rtorr.com/)
- [Vimgifs](https://vimgifs.com/)
- [Vim Navigation Commands: sequences you have no excuse not to know](http://danielallendeutsch.com/blog/2-vim-navigation-commands.html)
- [Learn Vimscript the Hard Way](http://learnvimscriptthehardway.stevelosh.com/)
- [How to Do 90% of What Plugins Do (With Just Vim)](https://www.youtube.com/watch?v=XA2WjJbmmoM)
- [Vim anti-patterns](https://sanctum.geek.nz/arabesque/vim-anti-patterns/)
- [Building Vim from source](https://github.com/Valloric/YouCompleteMe/wiki/Building-Vim-from-source)
- [Use Vim as a Java IDE](https://spacevim.org/2017/02/11/use-vim-as-a-java-ide)
- [SnipMate](https://github.com/garbas/vim-snipmate)
- [Auto closing an HTML tag](http://vim.wikia.com/wiki/Auto_closing_an_HTML_tag)
- [What is the best JavaScript (programming language) setup with Vim (text editor)?](https://www.quora.com/What-is-the-best-JavaScript-programming-language-setup-with-Vim-text-editor)
- [Vimcasts](http://vimcasts.org/)
- [Here is why vim uses the hjkl keys as arrow keys](http://www.catonmat.net/blog/why-vim-uses-hjkl-as-arrow-keys/)
- [Your First Vim Plugin](https://www.youtube.com/watch?v=lwD8G1P52Sk)
- [vim + tmux - OMG!Code](https://www.youtube.com/watch?v=5r6yzFEXajQ)
- [What is your most productive shortcut with Vim?](http://stackoverflow.com/questions/1218390/what-is-your-most-productive-shortcut-with-vim)
- [nicknisi/dotfiles](https://github.com/nicknisi/dotfiles)
