#!/bin/bash
hour=$(date +%H)
if [[ $hour =~ [04]$ ]]; then
    php $OPENSHIFT_REPO_DIR/yii cache-vigilantus/generate-fechamento-rg
fi
