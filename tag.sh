#!/usr/bin/env bash
git tag
read -p "Please Set Tag Version " GIT_VERSION
git tag $GIT_VERSION
git push origin $GIT_VERSION
