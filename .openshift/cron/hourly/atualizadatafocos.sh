#!/bin/bash
hour=$(date +%H)
if [[ $hour =~ [02]$ ]]; then
    php $OPENSHIFT_REPO_DIR/yii cache-vigilantus/update-ultimo-foco-quarteirao
fi
