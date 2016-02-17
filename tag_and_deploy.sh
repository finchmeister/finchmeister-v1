#!/usr/bin/env bash
# Last commit: $Id$
# Version Location: $HeadURL$
PIIP=192.168.0.2
VERSION=X_00_01

# Update the version html file going out to the PI with the new version no.
echo "${VERSION}" > trunk/html/version.html

# Commit the WC
svn commit trunk -m "Commit prior to deployment of ${VERSION} to PI"

# Create the branch
svn --parents copy file:///Users/jfinch/raspberrypiSVN/trunk file:///Users/jfinch/raspberrypiSVN/tags/${VERSION} -m "Deployment of ${VERSION} to PI"

# Reset the LOCAL version html file
echo "Bleeding edge dev code - version last deployed to PI: ${VERSION}" > trunk/html/version.html

# Create temporary directory /tmp/rpiwebsite/${VERSION}
cd /tmp
mkdir rpiwebsite
cd rpiwebsite

# Export the tag into it
svn export file:///Users/jfinch/raspberrypiSVN/tags/${VERSION}

# Rsync the tag to PI
rsync -r /tmp/rpiwebsite/${VERSION}/html/ pi@${PIIP}:/var/www/html