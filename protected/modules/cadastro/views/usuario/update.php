<?php
/*$this->breadcrumbs = array(
	Yii::t('Usuario', 'Usuarios') => array('index'),
	$model->id => array('view','id'=>$model->id),
	Yii::t('site', 'Editar'),
);*/
?>

<h1><?php echo Yii::t('Usuario', 'Atualizar Usuário') . ' #' . $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>