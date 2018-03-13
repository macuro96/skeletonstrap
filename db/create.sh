#!/bin/sh

if [ "$1" = "travis" ]
then
    psql -U postgres -c "CREATE DATABASE skeletonstrap_test;"
    psql -U postgres -c "CREATE USER skeletonstrap PASSWORD 'skeletonstrap' SUPERUSER;"
else
    [ "$1" != "test" ] && sudo -u postgres dropdb --if-exists skeletonstrap
    [ "$1" != "test" ] && sudo -u postgres dropdb --if-exists skeletonstrap_test
    [ "$1" != "test" ] && sudo -u postgres dropuser --if-exists skeletonstrap
    sudo -u postgres psql -c "CREATE USER skeletonstrap PASSWORD 'skeletonstrap' SUPERUSER;"
    [ "$1" != "test" ] && sudo -u postgres createdb -O skeletonstrap skeletonstrap
    sudo -u postgres createdb -O skeletonstrap skeletonstrap_test
    LINE="localhost:5432:*:skeletonstrap:skeletonstrap"
    FILE=~/.pgpass
    if [ ! -f $FILE ]
    then
        touch $FILE
        chmod 600 $FILE
    fi
    if ! grep -qsF "$LINE" $FILE
    then
        echo "$LINE" >> $FILE
    fi
fi
