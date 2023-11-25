Title: How To Find Differences Between Two Directories On Linux
Date: 2018-12-10 23:20
Category: Linux
Tags: linux, shell, diff
Slug: how-to-find-differences-between-two-directories-on-linux
Authors: Romain Pellerin
Summary: Different ways to diff two folders

# `diff`

Compares filenames and lines. **Very slow**.

    :::bash
    /usr/bin/diff -qr /dirA /dirB

# `comm` + `find`

Only compares filenames. **Fast**.

    :::bash
    comm -3 <(find /dirA -type f -printf "%f\n"|sort) <(find /dirB -type f -printf "%f\n"|sort)>>

Also works with `diff`.

# `diff` + `find` + `md5sum`

Compares filenames and content. **Slow**.

    :::bash
    /usr/bin/diff <(find /dirA -type f -printf "%p " -exec md5sum '{}' \; | cut -d '/' -f1 | sort) \
        <(find /dirB -type f -printf "%p " -exec md5sum '{}' \; | cut -d '/' -f1 | sort)

# `git`

Compares filenames and content. **Slow**.

    :::bash
    git diff -D --no-index /dirA /dirB


Hope this helps.
