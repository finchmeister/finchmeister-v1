#!/usr/bin/env bash

PIIP=finchmeister.co.uk

scp pokerstatsALL.csv pi@${PIIP}:/var/www/secure-php-scripts/poker-stats/pokerstatsALL.csv
#ssh pi@${PIIP} 'php /var/www/secure-php-scripts/poker-stats/insert_stats_into_db.php'
#cd /var/www/secure-php-scripts/
#echo pwd
#php insert_stats_into_db.php