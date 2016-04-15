#!/bin/bash

rm -rf cache/
mkdir cache
chmod 777 cache/

cd cache
touch status.cache
chmod 666 status.cache

exit
