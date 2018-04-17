#!/bin/sh
if [ -n "$DYNO" ]
then
    php init --env=Production --overwrite=All
    ./yii migrate --interactive 0
fi
