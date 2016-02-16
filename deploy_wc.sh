#!/usr/bin/env bash
# Deploy wc to PI
PIIP=192.168.0.2

rsync -r /Users/jfinch/rpiwc/html/ pi@${PIIP}:/var/www/html