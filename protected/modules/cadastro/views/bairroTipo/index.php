<?php
/*$this->breadcrumbs=array(
	Yii::t('BairroTipo', 'Bairro Tipos'),
);*/
?>

<h1><?php echo Yii::t('BairroTipo',  'Tipos de Bairro'); ?></h1>

<?php $this->widget('ext.internal.PGridView', array(
	'id'=>'bairro-tipo-grid',
	'isExportable' => true,
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
            'fieldData' => CHtml::listData(Municipio::model()->findAll(array('order' => 'nome asc')), 'id', 'nome'),
			'fieldRefreshDataUrl' => Yii::app()->createUrl('cadastro/bairroTipo/cidade.busca'),
            'visible' => Yii::app()->user->isRoot(),
        ),
		'nome',
        array(
            'name' => 'data_cadastro',
            'value' => '$data->data_cadastro',
            'filter' => false,
            'fieldType' => 'label',
        ),
        array(
            'name' => 'data_atualizacao',
            'value' => '$data->data_atualizacao',
            'filter' => false,
            'fieldType' => 'label',
        ),
        array(
            'name' => 'inserido_por',
            'value' => '$data->inseridoPor->nome',
            'filter' =>  CHtml::listData(Usuario::model()->doNivelDoUsuario(Yii::app()->user->getUser())->findAll(array('order' => 'nome asc')),'id', 'nome'),
            'fieldType' => 'label',
        ),
        array(
            'name' => 'atualizado_por',
            'value' => '$data->atualizado_por ? $data->atualizadoPor->nome : null',
            'filter' =>  CHtml::listData(Usuario::model()->doNivelDoUsuario(Yii::app()->user->getUser())->findAll(array('order' => 'nome asc')),'id', 'nome'),
            'fieldType' => 'label',
        ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{update}{delete}'
 		),
	),
)); ?>
