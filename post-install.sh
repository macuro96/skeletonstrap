#!/bin/sh
if [ -n "$DYNO" ]
then
    php init --env=Production --overwrite=All
fi
