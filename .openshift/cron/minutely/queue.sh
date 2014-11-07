#!/bin/bash

minute=$(date +%M)
if [[ $minute =~ [05]$ ]]; then
    php $OPENSHIFT_REPO_DIR/yii queue/run
fi