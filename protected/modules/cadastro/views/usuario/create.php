<?php
/*$this->breadcrumbs=array(
	Yii::t('Usuario', 'Usuarios') => array('index'),
	Yii::t('site', 'Novo'),
);*/
?>

<h1><?php echo Yii::t('Usuario',  'Novo Usuário'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>