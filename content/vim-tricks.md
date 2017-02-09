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
