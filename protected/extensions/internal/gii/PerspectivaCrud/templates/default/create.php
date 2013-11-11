<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "/*\$this->breadcrumbs=array(
	Yii::t('$this->modelClass', '$label') => array('index'),
	Yii::t('site', 'Novo'),
);*/\n";

?>
?>

<h1><?php echo "<?php echo Yii::t('{$this->modelClass}',  'Novo " . $this->modelClass . "'); ?>"; ?></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
