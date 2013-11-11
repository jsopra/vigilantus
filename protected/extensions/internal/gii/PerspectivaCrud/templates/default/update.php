<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "/*\$this->breadcrumbs = array(
	Yii::t('{$this->modelClass}', '$label') => array('index'),
	\$model->{$nameColumn} => array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
	Yii::t('site', 'Editar'),
);*/\n";

?>
?>

<h1><?php echo "<?php echo Yii::t('{$this->modelClass}', 'Editar ". $this->class2name($this->modelClass) . "') . ' #' . \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
