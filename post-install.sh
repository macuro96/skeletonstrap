#!/bin/sh
if [ -n "$DYNO" ]
then
    php init --env=Production --overwrite=All
    ./yii migrate/down --interactive 0
    ./yii migrate/up --interactive 0
fi
