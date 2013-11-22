<?php
/*$this->breadcrumbs=array(
	Yii::t('Bairro', 'Bairros'),
);*/
?>

<h1><?php echo Yii::t('Bairro',  'Bairros'); ?></h1>

<?php $this->widget('ext.internal.PGridView', array(
	'id'=>'bairro-grid',
	'isExportable' => false,
	'isEditable' => true,
    'createButtonEnabled' => false,
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'name' => 'municipio_id',
            'value' => '$data->municipio->nome',
            'filter' =>  CHtml::listData(Municipio::model()->findAll(array('order' => 'nome asc')),'id', 'nome'),
            'fieldType' => 'select',
            'fieldData' => array(null => Yii::t('Site', 'Selecione...')) + CHtml::listData(Municipio::model()->findAll(array('order' => 'nome asc')), 'id', 'nome'),
            'visible' => Yii::app()->user->isRoot(),
        ),
		'nome',
		array(
            'name' => 'bairro_tipo_id',
            'value' => '$data->bairroTipo->nome',
            'filter' =>  CHtml::listData(BairroTipo::model()->findAll(array('order' => 'nome asc')),'id', 'nome'),
            'fieldType' => 'select',
            'fieldData' => array(null => Yii::t('Site', 'Selecione...')) + CHtml::listData(BairroTipo::model()->findAll(array('order' => 'nome asc')), 'id', 'nome'),
            'visible' => Yii::app()->user->isRoot(),
        ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{update}{delete}'
 		),
	),
)); ?>
