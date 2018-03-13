#!/bin/sh

BASE_DIR=$(dirname $(readlink -f "$0"))
if [ "$1" != "test" ]
then
    psql -h localhost -U skeletonstrap -d skeletonstrap < $BASE_DIR/skeletonstrap.sql
fi
psql -h localhost -U skeletonstrap -d skeletonstrap_test < $BASE_DIR/skeletonstrap.sql
