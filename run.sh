#!/bin/bash

WD=`pwd`
PORT=42319

cd www
echo "this application is available at http://localhost:$PORT"
php -S localhost:$PORT _mvp.php
cd $WD
