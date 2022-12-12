#!/bin/bash

RELEASE_DIR="release"

rm -rf $RELEASE_DIR
mkdir $RELEASE_DIR
cp -r data php js *.php *.js styles.css $RELEASE_DIR

rm -rf $RELEASE_DIR/data/stats.db

echo "Generate release in '$RELEASE_DIR'"
