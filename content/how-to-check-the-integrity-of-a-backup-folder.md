Title: How To Check The Integrity Of A Backup Folder
Date: 2020-08-27 23:50
Category: Computers
Tags: linux, md5sum, git
Slug: how-to-check-the-integrity-of-a-backup-folder
Authors: Romain Pellerin
Summary: Checking the md5sum of files copied across two folders

Recently, I made a backup copy of a folder that I copied over several devices. In the end, I wanted to make sure the various copies did not alter any of the files. Here's how to proceed:

    :::bash
    cd <ORIGINAL FOLDER>
    find . -type f | sort | > /tmp/original.files
    cd <BACKUP FOLDER>
    find . -type f | sort | > /tmp/backup.files

    # You may not have copied all the files, so let's only keep the common ones
    comm -12 /tmp/original.files /tmp/backup.files > /tmp/common.files

    cd <ORIGINAL FOLDER>
    cat /tmp/common.files | xargs -d'\n' md5sum > /tmp/original.md5sum
    # -d'\n' so that filenames with space characters are handled
    cd <BACKUP FOLDER>
    md5sum --check /tmp/original.md5sum # Shows OK or FAILED for each file

Hope that helps.
