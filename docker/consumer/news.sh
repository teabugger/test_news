#!/bin/sh
until nc -z -v -w30 php 9000
do
  echo "Waiting for main app connection..."
  sleep 5
done

php /srv/api/bin/console messenger:consume create-news --limit=1 --time-limit=10000
