#!/bin/sh

swps=$(find . -name '*.swp')
for f in $swps
do
    echo "rm $f"
    rm $f
done

echo "clear ok"
