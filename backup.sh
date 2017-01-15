#!/usr/bin/env bash
HOST=finchmeister.co.uk
USER=jfinch
TIME=`date +%b-%d-%y`
FILENAME=backup-$TIME.tar.gz
ssh ${USER}@${HOST} 'tar -cvpzf ~/${FILENAME} /var/www'


#ssh jfinch@finchmeister.co.uk 'tar -cvpzf ~/Bob.tar.gz /var/www'