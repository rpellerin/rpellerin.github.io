#!/bin/bash

BUILDING_BRANCH="develop"
TARGET_BRANCH="master"
DATE=$(date --iso-8601=seconds)
BUILD_DIRECTORY="output"

cd "$BUILD_DIRECTORY" # At this point, everything has been built
git init
git checkout -b "$BUILDING_BRANCH"
git remote add origin https://${GITHUB_ACTOR}:${GH_TOKEN}@github.com/${GITHUB_REPOSITORY}.git
git config --local user.email "action@github.com"
git config --local user.name "GitHub Action"
git fetch origin

BRANCH_EXISTS=`git rev-parse --verify origin/$TARGET_BRANCH`

if [ -n "$BRANCH_EXISTS" ]; then
    echo "Branch $TARGET_BRANCH exists"
    git add -A
    git commit --no-verify -m 'Latest build'
    rm ../diff -f
    git diff-index origin/$TARGET_BRANCH --binary -p > ../diff
    if [ ! -s ../diff ]; then
        echo "$TARGET_BRANCH has not changed. Won't push.";
        exit 0
    fi
    git checkout -b $TARGET_BRANCH origin/$TARGET_BRANCH
    git apply ../diff
else
    echo "Branch $TARGET_BRANCH does not exist"
    git checkout -b $TARGET_BRANCH
fi

git add -A
git commit --no-verify -m "Release-$DATE"
git push -q origin $TARGET_BRANCH:$TARGET_BRANCH
