#!/usr/bin/env bash
# Last commit: $Id$
# Version Location: $HeadURL$
# Remember to run from ~/rpiwebsite/trunk
PIIP=finchmeister.co.uk
VERSION=X_02_00

# Update the version txt file going out to the PI with the new version no.
echo "${VERSION}" > html/version.txt

# Commit the WC
svn commit -m "Commit prior to deployment of ${VERSION} to PI"

# Create the branch
svn --parents copy file:///Users/jfinch/raspberrypiSVN/trunk file:///Users/jfinch/raspberrypiSVN/tags/${VERSION} -m "Deployment of ${VERSION} to PI"

# Reset the LOCAL version txt file
echo "Bleeding edge dev code - version last deployed to PI: ${VERSION}" > html/version.txt

# Create temporary directory /tmp/rpiwebsite/${VERSION}
cd /tmp
mkdir rpiwebsite
cd rpiwebsite

# Export the tag into it
svn export file:///Users/jfinch/raspberrypiSVN/tags/${VERSION}

# Rsync the tag to PI
rsync -arv --delete /tmp/rpiwebsite/${VERSION}/html/ pi@${PIIP}:/var/www/html
# Also sync the scripts outside of document root
rsync -arv --delete /tmp/rpiwebsite/${VERSION}/secure-php-scripts/ pi@${PIIP}:/var/www/secure-php-scripts
