#!/bin/sh

heroku psql < heroku.sql
heroku psql < skeletonstrap.sql
