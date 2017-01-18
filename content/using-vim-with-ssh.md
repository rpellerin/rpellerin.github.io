Title: Using Vim with SSH
Date: 2016-10-19 03:35
Category: Linux
Tags: ssh, vim, terminal, vi
Slug: using-vim-with-ssh 
Authors: Romain Pellerin
Summary: How to edit and compile files on a remote server

I recently faced quite a challenging problem. I had to write C++ code and compile it on a remote server. Why would I need to compile on a remote server? Well, first my laptop is not very powerful and compiling takes some time, in comparison to the server which proved to be much faster. Moreover, my code was supposed to compile on the server "out of the box", as it was meant to be executed and tested there. Hence, no dependencies, no customized environmnent, and so forth.

Anyway, I then started looking for a way to use Vim over SSH effectively, without having to switch between terminals to compile. What's more, I wanted it to be as fast as possible. After asking for help on [StackOverflow](http://unix.stackexchange.com/questions/315844/editing-and-compiling-files-on-a-remote-server-with-vim/315846), I got a very helpful answer. I finally came up with the following solution. Let's get started!

# SSH

First, edit `~/.ssh/config` and add your remote host.

    :::bash
    Host my_server
      Hostname <server>
      User <user>
      IdentityFile ~/.ssh/id_rsa
        ControlMaster auto
        ControlPath ~/.ssh/%C
        # ControlPath ~/.ssh/cm_socket/%r@%h:%p
        ControlPersist yes

Then, enable *pubkey* authentication using `ssh-copy-id -i ~/.ssh/id_rsa.pub user@server`. You should now be able to connect using `ssh my_server`. This will establish a permanent connection. As an example, simply open another terminal and try to connect to that server, you'll notice how fast it is this time!

Later, you'll be able to disconnect from the server using `ssh -O exit my_server`.

# Tmux

Tmux is obviously a must-have and is thus already installed.

# Vim

## Prerequisite

Before starting editing files, you definitely want your Vim to be smart. Hence we'll use the plugin [vim-dispatch](https://github.com/tpope/vim-dispatch), which allows use to run the command `:make` asynchronously from Vim, in another Tmux pane (the new command introduced by this plugin is `:Make`).

## Editing and compiling

Once you're connected over SSH, let's edit files on the server using Vim. Let's go!

    :::bash
    vim sftp://my_server//home/whoever/main.cpp

Then, in Vim, type the following commands:

    :::bash
    # The next two lines have the same effect,
    # except that the first one needs spaces to be escaped
    :set makeprg=ssh\ my_server\ 'make && ./main && exit'
    :let &makeprg="ssh my_server 'make && ./main && exit'"
    :map <f10> :Copen<cr>G
    :map <f9> :update<cr>:Make<cr>
    :map <f12> :cclose<cr>

The last three commands are just shortcuts meant to make the world a better place... or just developers happier.

That's it! I hope it was helpful.
