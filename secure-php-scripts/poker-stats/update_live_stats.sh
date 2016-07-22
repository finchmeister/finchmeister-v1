#!/usr/bin/env bash

PIIP=finchmeister.co.uk

scp pokerstatsALL.csv pi@${PIIP}:/var/www/secure-php-scripts/poker-stats/pokerstatsALL.csv

#ssh pi@${PIIP} 'php /var/www/secure-php-scripts/poker-stats/insert_stats_into_db.php'
#ssh pi@$finchmeister.co.uk 'pwd'
#cd /var/www/secure-php-scripts/poker-stats
#echo pwd
#php insert_stats_into_db.php