#!/bin/sh
if [ -n "$DYNO" ]
then
    php init --env=Production --overwrite=All
    ./yii migrate --migrationPath=@yii/rbac/migrations
fi
