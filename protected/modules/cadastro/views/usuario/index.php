<?php
/*$this->breadcrumbs=array(
	Yii::t('Usuario', 'Usuarios'),
);*/
?>

<h1><?php echo Yii::t('Usuario',  'Usuários'); ?></h1>

<?php

$columns = array(
    'nome',
    'login',
);

if(Yii::app()->user->isRoot()) {
	$columns[] = array(
        'name' => 'municipio_id',
        'value' => '$data->municipio_id ? $data->municipio->nome : null',
        'filter' =>  CHtml::listData(Municipio::model()->findAll(array('order' => 'nome asc')),'id', 'nome'),
    );
}

$columns[] = array(
    'name' => 'usuario_role_id',
    'value' => '$data->usuario_role_id ? $data->role->nome : null',
    'filter' =>  CHtml::listData(UsuarioRole::model()->findAll(array('order' => 'id asc')),'id', 'nome'),
);

$columns[] = array(
    'name' => 'ultimo_login',
    'value' => '$data->ultimo_login ? Date::getDateTime($data->ultimo_login) : null',
    'filter' => false,
);

$columns[] = 'email';

$columns[] = array(
    'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{update}{delete}'
);
?>

<?php $this->widget('ext.internal.PGridView', array(
	'id'=>'usuario-grid',
	'isExportable' => false,
	'isEditable' => false,
	'dataProvider'=>$model->ativo()->doNivelDoUsuario(Yii::app()->user->getUser())->search(),
	'filter'=>$model,
    'columns'=> $columns
)); ?>
