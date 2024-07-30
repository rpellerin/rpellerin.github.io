Title: Kotlin
Date: 2024-07-26 15:10
Category: Code
Tags: kotlin, java, code
Slug: kotlin
Authors: Romain Pellerin
Summary: Cheatsheet for beginners - such as myself!

# IDEA

Although technically any IDE with the right extensions will do, it's recommended to use IntelliJ IDEA Ultimate. There is also the Community Edition that is completely free to use.

Kotlin is included in each IntelliJ IDEA release.

## Setup IntelliJ

`IntelliJ Idea > Settings > Build, Execution, Deployment > Build Tools > Maven > Importing`. Check the 2 checkboxes "Sources" and "Documentation".

`Intellij Idea > Settings > Editor > General > Auto Import`, check `Optimize imports on the fly` for both Java and Kotlin.

`Intellij Idea > Settings > Editor > Tools > Actions on Save`, check `Reformat code`, `Optimize imports`, `Rearrange code` and `Run code cleanup`.

Install the `SonarLint` plugin for code quality checks.

To add git blame annotations, add the plugin [GitToolBox](https://plugins.jetbrains.com/plugin/7499-gittoolbox).

# Setup

## Install Java

    :::bash
    sudo apt update
    sudo apt install openjdk-21-jdk

## Install Kotlin

In most scenarios, installing Kotlin is not necessary. Only Java is.

## Install Maven

[Installing Maven, our build tool, is not required, as IntelliJ comes with a bundled version of Maven. However, installing your own version might come in handy, to be able to run Maven from the command line.](https://stackoverflow.com/questions/66399278/having-maven-plugins-in-intellij-idea-without-maven-installation-in-computer)


    :::bash
    sudo apt update
    sudo apt install maven

[Using the Maven Wrapper in a project is also an alternative.](https://maven.apache.org/wrapper/) Yet it does not hurt to install Maven globally on the system, it won't conflict.

