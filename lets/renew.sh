#!/bin/bash
chmod +x ./renew.sh
rm source -rf
mkdir source
rm result -rf
mkdir result
cp 0.com.bak result/
cd /etc/nginx/
find . -type f -name "*.conf" -not -path "./cache/*" -not -path "./conf.d/*" -not -path "./sites-enabled/*" ! -name "nginx.conf" -exec cp {} /var/www/html/default/lets/source/ \;
cd /var/www/html/default/lets/
php get-config.php
rm /etc/letsencrypt/configs/* -rf
cp result/* /etc/letsencrypt/configs/
mkdir -p /etc/letsencrypt2/configs/
cp result/* /etc/letsencrypt2/configs/
#chmod +x ./letsencrypt-renewal-global.sh
#./letsencrypt-renewal-global.sh
