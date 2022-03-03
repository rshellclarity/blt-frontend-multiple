#!/bin/bash
# Build the front-end requirements for all themes.
#
# This is intended to be used locally or in a CI environment where assets need
# to be built. It should never be used on an Acquia environment.


if [ ! -z $AH_SITE_ENVIRONMENT ]
then
  echo "Do not run this on Acquia!"
  exit 1
fi

set -ev

CURRENT_DIR=`pwd`

for theme in docroot/themes/custom/*
do
  # If it's not a directory then skip it.
  
  if [ ! -d "$theme" ]
  then
    continue
  fi
  
    cd $theme
    curl -sL https://deb.nodesource.com/setup_14.x | bash -
    sudo apt install -y nodejs
    npm install
    npm install --global gulp-cli
    npm install gulp@^4.0.2 --save
    npm ci

  cd $CURRENT_DIR
done

