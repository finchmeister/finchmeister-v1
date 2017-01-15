#!/usr/bin/env bash
# Last commit: $Id$
# Version Location: $HeadURL$
# Remember to run from ~/rpiwebsite/trunk
# DEPLOY TO DIGITAL OCEAN DROPLET
HOST=finchmeister.co.uk
VERSION=X_04_00
USER=jfinch
#SVNREPODIR=file:///Users/jfinch/raspberrypiSVN # Linux rig
SVNREPODIR=file:///Users/jfinch/PersonalProjects/finchmeister1/website_repository # MBP


# Update the version txt file going out to the PI with the new version no.
echo "${VERSION}" > html/version.txt

# Commit the WC
svn commit -m "Commit prior to deployment of ${VERSION} to PI"

# Create the branch
svn --parents copy ${SVNREPODIR}/trunk ${SVNREPODIR}/tags/${VERSION} -m "Deployment of ${VERSION} to Droplet"

# Reset the LOCAL version txt file
echo "Bleeding edge dev code - version last deployed to server: ${VERSION}" > html/version.txt

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
rsync -arv --delete /tmp/rpiwebsite/${VERSION}/html/ ${USER}@${HOST}:/var/www/html
# Also sync the scripts outside of document root
rsync -arv --delete /tmp/rpiwebsite/${VERSION}/secure-php-scripts/ ${USER}@${HOST}:/var/www/secure-php-scripts