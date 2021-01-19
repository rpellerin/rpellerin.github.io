Title: Vim Tricks
Date: 2017-01-18 22:50
Modified: 2021-01-19 23:00
Category: Linux
Tags: vim, vi, linux
Slug: vim-tricks
Authors: Romain Pellerin
Summary: A few tricks for Vim that I have learned over all my years of using this incredible tool.

# Left-right and up-down motions

- `h`, `j`, `k`, `l`: left, down, up, right
- `^e`: scoll window down (ctrl + e)
- `^y`: scroll window up
- `^f`: scroll down one page
- `^b`: scroll up one page
- `H`: move cursor to the top
- `M`: move cursor to the middle
- `L`: move cursor to the bottom
- `gg`: top of file
- `G`: bottom of file
- `t<char>`: till some character
- `T<char>`: till some character (backward)
- `f<char>`: find forward (it will include the found character, unlike till)
- `F<char>`: find backward

# Text objects motions

- `<n> w`: n words forward (n is optional)
- `b`: beginning of word
- `e`: end of word
- `{`: beginning of paragraph
- `}`: end of paragraph

# Text objects (only in visual mode or after an operator)

- `s`: sentences
- `p`: paragraphs
- `t`: tags (html/xml only)

# Copying and moving text / Inserting text

- `d{motion}`: delete/cut (`dd` to delete entire line)
- `p`: paste (a line that was cut for instance)
- `c`: change (replace)
- `D` / `C`: delete/change until end of line
- `y{motion{`: yank (`yy` to copy entire line)
- `>`: indent
- `^`: go to beginning of line
- `$`: go to end of line
- `I`: go to beginning of line and enter insert mode
- `A`: go to end of line and enter insert mode
- `o`: add a new line below and enter insert mode
- `O`: add a new line above and enter insert mode

## Examples

General rule: `[optional number to repeat]{command}{text object or motion}`.

- `diw`: delete in word
- `caw`: change all word (all will grab surrounding whitespaces)
- `yi(`: yank all text inside parentheses
- `di[`: delete between square brackets
- `va"`: visually select all inside doublequotes (including doublequotes)
- `3dd` is equal to `d2j`
- `3J`: join the next three lines into 1 line
- `d5}`: delete from the current line through the end of the fifth paragraph down from here

# Registers

- `:reg`: access the registers
- `i` (insert mode) then `^r` and a register letter to paste from it, instead of `"{register}p`.
- `m{register}`: mark a cursor position in some register
- ``{register}`: go back to the marked position
- `'{register}`: go to beginning of line where there is the marked position
- `` d`a ``: delete from here to position marked in register 'a'

# Autocomplete (in insert mode)

- `^x^n`: for just this file
- `^x^f`: for filenames
- `^x^]`: for tags only
- `^x^l`: whole lines
- `^n` and `^p`: go back and forth in the suggestion list

(`^n` is disabled by YouCompleteMe.)

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

Normally, to search in a non-greedy way, we add a punctuation mark, as in `.*?` which means _any string of characters as short as possible_. For instance:

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

## Other

- `v`: visually select (enters visual mode)
- `.`: repeat the last command
- `~`: invert the case for current char
- `/`: search something (c/myvar, delete and insert from here to myvar)
- `:earlier 2m`: go back to state in history that is 2 minutes old
- `:cl`: to list errors
- `:cc#` to jump to error number #
- `:cn` and `:cp`: to navigate forward and back
- `:help ^n`: to know about what ctrl n does
- `:help c_^n`: ctrl n in command mode
- `:help i_^n`, `:help v_^n`: ctrl n in insert mode or visual mode
- `:helpgrep whatever`: search in all documentation, then use :cl, :cc, :cp, :cn

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
- [shawncplus/dotfiles](https://github.com/shawncplus/dotfiles)
- [Why I love Vim: It’s the lesser-known features that make it so amazing](https://medium.freecodecamp.org/learn-linux-vim-basic-features-19134461ab85)
