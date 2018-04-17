#!/bin/sh
if [ -n "$DYNO" ]
then
    php init --env=Production --overwrite=All
    ./yii migrate --migrationPath=@yii/rbac/migrations --interactive 0
    ./yii migrate/down --interactive 0
    ./yii migrate/up --interactive 0
fi
