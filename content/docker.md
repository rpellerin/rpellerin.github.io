Title: Docker
Date: 2022-07-05 12:30
Category: Linux
Tags: docker, linux, container
Slug: docker
Authors: Romain Pellerin
Summary: My cheatsheet for Docker

**WIP ARTICLE**

Up until now, Docker has always been a mystery to me. Mostly because I never took time to dive into it. For the past year, I was lucky enough to have David Gageot as my manager at Doctolib. As far as I know, he was one of the early contributors to it, and remains to this day one of the main contributors. Docker has no secrets for him. Here is a video starring David himself, a year after Docker was released to the public:

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/d3bUoz_G2VU?rel=0" frameborder="0" allowfullscreen></iframe>

During his time at Doctolib, I learned quite a lot. And I kept feeding my interest for Docker even after he left. Therefore this article serves as my personal cheatseet for Docker. It is a collection of basic knownledge and advanced tricks.

- Docker images can run nested Docker images (not advisable for production)
- `FROM <image name>`: sets the base image for our Docker image
- `WORKDIR /home/yolo` creates the `/home/yolo` directory
- `CMD` vs `RUN`: `RUN` is a build step, it creates a layer in the docker image and is therefore cached. `CMD` is the command to execute when runnning a docker image. If the command outputs something in stdin, it can be reused by other Docker images (see below).
- `ADD . /home/yolo` copy the output of the base image (through stdin) to `/home/app`. If the base image (`FROM xxx`) output assets as a result of its `CMD` command, the files will end up in `/home/yolo`.
