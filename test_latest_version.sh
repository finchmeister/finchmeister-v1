#!/usr/bin/env bash
HOST=finchmeister.co.uk
VERSION=TAGGING_TEST_2017_01
USER=jfinch
#SVNREPODIR=file:///Users/jfinch/raspberrypiSVN # Linux rig
SVNREPODIR=file:///Users/jfinch/PersonalProjects/finchmeister1/website_repository # MBP

# Create the branch
svn --parents copy ${SVNREPODIR}/trunk ${SVNREPODIR}/tags/${VERSION} -m "Tagging for testing"

# Create temporary directory /tmp/rpiwebsite/${VERSION}
cd /tmp
if [ ! -d "rpiwebsite" ]; then
  # Control will enter here if $DIRECTORY doesn't exist.
  mkdir rpiwebsite
fi
cd rpiwebsite

# Export the tag into it
svn export ${SVNREPODIR}/tags/${VERSION}

# Rsync the tag to PI
#rsync -arv --delete /tmp/rpiwebsite/${VERSION}/html/ ${USER}@${HOST}:/var/www/html
rsync -anrv --delete jfinch@finchmeister.co.uk:/var/www/html /tmp/rpiwebsite/TAGGING_TEST_2017_01/html/