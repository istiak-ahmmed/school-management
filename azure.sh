#!/bin/bash
cp /home/site/wwwroot/default /etc/nginx/sites-available/default
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' /etc/nginx/sites-available/default
service nginx reload
