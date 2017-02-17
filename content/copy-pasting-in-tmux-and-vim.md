Title: Copy Pasting In Tmux And Vim
Date: 2016-04-06 16:30
Category: Linux
Tags: tmux, vi, vim, linux, terminal
Slug: copy-pasting-in-tmux-and-vim
Authors: Romain Pellerin
Summary: How to copy and paste with Tmux and Vim

Over the last couple of years, I've spent quite a lot of time fine-tuning my prompt. Most of my [configuration is available online](https://github.com/rpellerin/dotfiles), so feel free to use it.

My configuration is as follows:

* I use `zsh` rather than `bash` cause it's way more powerful and convenient. In addition, I use the awesome [Prezto](https://github.com/sorin-ionescu/prezto) which is a configuration framework built for `zsh`.
* `tmux` to be able to open as many terminals as I want in only one window (very helpful).
* `vim` as my main text editor.  I use it for most things involving programming (except for Android, I have to admit, as Android Studio does the job really well).

For example, this is my current terminal (actually containing 3 terminals):

[![Screenshot]({filename}/images/copy-pasting-tmux-vim-screenshot.png)]({filename}/images/copy-pasting-tmux-vim-screenshot.png)

*(the top-right terminal is of no use, I just opened it to show how clever zsh coupled with Prezto is).*

# The problem

I guess most beginners with Tmux and Vim (especially Vim) stumble upon one problem (and the number of questions on StackOverflow asserts it): how to properly copy-paste? I had that problem too. I'll answer to these questions simply.

First of all, you have to know that, on X11 systems (which means most GNU/Llinux distros), there are two "clipboards":

* \* is the selection buffer. It's the one used when you select a text and paste it using the middle button of a mouse (it's actually made of two buffers called *PRIMARY* and *SECONDARY* but we don't need to know that).
* \+ is the cut buffer (the one used with C-c C-v) also called *CLIPBOARD*.

Secondly, you may know that, in most programs you can use:

* 'Ctrl-c' or 'Ctrl-Shift-c' to copy to the cut buffer
* 'Ctrl-v' or 'Ctrl-Shift-v' to paste from the cut buffer
* 'Ctrl-Insert' to copy to the selection buffer or simply select text using a mouse
* 'Shift-Insert' to paste from the selection buffer or simply use the middle button of a mouse

Let us now see how to copy paste from one environment to another one.

# Copy-pasting

## Copy-pasting from Vim to Vim (same instance)

* In normal mode, using the keys 'yy' (yank) or 'dd' (delete) and then 'p' (paste).
* Also using the visual mode with 'y' (yank).

## Copying from Tmux

Use my [conf file](https://github.com/rpellerin/dotfiles/blob/master/.tmux.conf) and install `xclip` (`sudo apt-get install xclip`) to achieve the following:

* 'C-b [': enter copy mode
* 'v': begin selection
* 'y': copy to clipboard and exit copy mode

## Copying from Vim

* In normal or visual mode, using '"+yy' (normal) '"+y' (visual) to copy to the cut buffer or '\*yy' and '"\*y' to copy to the selection buffer.

## Pasting in Tmux

* Paste in Tmux: 'Ctrl-Shift-v' (paste the clipboard) or 'Shift-Insert' (paste the selection).
* You can also use Tmux's own buffers (list them using `tmux list-buffers` or show the current one with `tmux show-buffer`). For instance, to past from the buffer, do 'C-b ]'.
* Tmux also allows you to paste its buffer in a file, using `tmux save-buffer foo.txt`.
* To show or save a specific buffer, do `tmux show-buffer -b <n>` or `tmux save-buffer -b <n> bar.txt`.

## Pasting in Vim

* Paste in Vim: '"+p' (cut buffer) or '"\*p' (selection buffer).
* You can also use the shortcuts shown above in *Pasting in Tmux* (first bullet point).

Hope this was helpful.

# Going further 

- [vim + tmux - OMG!Code](https://www.youtube.com/watch?v=5r6yzFEXajQ)
