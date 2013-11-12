<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "/*\$this->breadcrumbs=array(
	Yii::t('" . $this->modelClass . "', '$label'),
);*/\n";

?>
?>

<h1><?php echo "<?php echo Yii::t('{$this->modelClass}',  '" . $label . "'); ?>"; ?></h1>

<?php echo "<?php"; ?> $this->widget('ext.internal.PGridView', array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'isExportable' => true,
	'isEditable' => true,
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'id'             => 'selectedItems',
			'class'          => 'ext.internal.PCheckBoxColumn',
			'selectableRows' => 2,
		),
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if(++$count==7)
		echo "\t\t/*\n";
	
	if ($column->autoIncrement) echo '//';
	echo "\t\t'".$column->name."',\n";
}
if($count>=7)
	echo "\t\t*/\n";
?>
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{view}{update}{delete}'
 		),
	),
)); ?>
