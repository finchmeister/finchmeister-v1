#!/usr/bin/env bash
PIIP=192.168.0.2
VERSION=X_00_01

# Update the version html file going out to the PI with the new version no.
echo "${VERSION}" > html/version.html

# Commit the WC
svn commit -m "Commit prior to deployment of ${VERSION} to PI"

# Create the branch
svn --parents copy file:///Users/jfinch/raspberrypiSVN file:///Users/jfinch/raspberrypiSVN/tags/${VERSION} -m "Deployment of ${VERSION} to PI"

# Export the branch to tmp

# Rsync the branch to PI



# Rsync the temp file
# rsync -r /Users/jfinch/rpiwc/html/ pi@${PIIP}:/var/www/html

# Reset the LOCAL version html file
echo "Bleeding edge dev code - version last deployed to PI: ${VERSION}" > html/version.html

svn move file:///Users/jfinch/raspberrypiSVN file:///Users/jfinch/raspberrypiSVN2/trunk -m "Relocating trunk"
svn mkdir file:///Users/jfinch/raspberrypiSVN/trunk -m "Creating trunk folder"

Take a copy of ~/rpiwc
Delete file:///Users/jfinch/raspberrypiSVN/
Create new repoistry file:///Users/jfinch/raspberrypiSVN/trunk
Import copy to repositry
checkout repositry to ~/rpiwc

svn list file:///Users/jfinch/raspberrypiSVN/trunk