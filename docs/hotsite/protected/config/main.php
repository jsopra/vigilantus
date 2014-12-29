<?php
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../../../../lib/common/extensions/bootstrap');
Yii::setPathOfAlias('perspectiva', dirname(__FILE__) . '/../../../../lib/common/extensions/perspectiva');

$commonConfig = include '_common.main.php';

$personalConfig = array();

return CMap::mergeArray($personalConfig,$commonConfig);
