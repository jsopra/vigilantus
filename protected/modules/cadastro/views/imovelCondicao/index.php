<?php
/*$this->breadcrumbs=array(
	Yii::t('ImovelCondicao', 'Imovel Condicaos'),
);*/
?>

<h1><?php echo Yii::t('ImovelCondicao',  'Condições de Imóvel'); ?></h1>

<?php $this->widget('ext.internal.PGridView', array(
	'id'=>'imovel-condicao-grid',
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
        array(
            'name' => 'nome',
            'value' => '$data->nome',
        ),
        array(
            'name' => 'exibe_nome',
            'fieldType' => 'checkbox',
            'filter' => array(true =>  Yii::t("Site", "Sim"), false =>  Yii::t("Site", "Não")),
            'value' => '$data->exibe_nome ? Yii::t("Site", "Sim") : Yii::t("Site", "Não")',
        ),
		array(
            'name' => 'inserido_por',
            'value' => '$data->inseridoPor->nome',
            'filter' =>  CHtml::listData(Usuario::model()->doNivelDoUsuario(Yii::app()->user->getUser())->findAll(array('order' => 'nome asc')),'id', 'nome'),
            'fieldType' => 'label',
        ),
        array(
            'name' => 'data_cadastro',
            'value' => '$data->data_cadastro',
            'filter' => false,
            'fieldType' => 'label',
        ),
        array(
            'name' => 'atualizado_por',
            'value' => '$data->atualizado_por ? $data->atualizadoPor->nome : null',
            'filter' =>  CHtml::listData(Usuario::model()->doNivelDoUsuario(Yii::app()->user->getUser())->findAll(array('order' => 'nome asc')),'id', 'nome'),
            'fieldType' => 'label',
        ),
        array(
            'name' => 'data_atualizacao',
            'value' => '$data->data_atualizacao',
            'filter' => false,
            'fieldType' => 'label',
        ),
		array(
			'class'=>'ext.internal.PButtonColumn',
			'template' => '{update}{delete}'
 		),
	),
)); ?>
