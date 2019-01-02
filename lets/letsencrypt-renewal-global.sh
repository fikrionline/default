#!/bin/bash
for i in `find /etc/letsencrypt/configs/ -name '*.conf'` ;
do echo 'c' | /opt/letsencrypt/certbot-auto --config $i certonly ;
done
