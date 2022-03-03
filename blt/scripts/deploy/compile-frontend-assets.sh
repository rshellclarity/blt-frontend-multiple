#!/bin/bash
# Build the front-end assets for all themes.
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
  if [ -f "gulpfile.js" ]
  then
    gulp compile
  elif [ -f "package.json" ]
  then
    npm run sass
  else
    echo 'No gulpfile.js or package.json found for this theme. We will not compile assets.'
  fi
    rm -rf node_modules;
  cd $CURRENT_DIR
done

