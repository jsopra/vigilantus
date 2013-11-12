 <?php
$this->pageTitle = Yii::app()->name . ' - ' . Yii::t('Admin','Login');
$this->breadcrumbs=array(
	Yii::t('Admin','Login'),
);
?>


<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

        <?php echo $form->textFieldRow($model,'username',array('class'=>'span4')); ?>

        <?php echo $form->passwordFieldRow($model,'password',array('class'=>'span4')); ?>


	<div class="form-btns">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'size'=>'medium', 'icon'=>'ok white', 'label'=>'Logar')); ?>
	</div>

<?php $this->endWidget(); ?>
