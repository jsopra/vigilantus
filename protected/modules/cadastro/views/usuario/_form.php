<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'usuario-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('class'=>'well'),
)); ?>

    <div class="row-fluid">
        <div class="column span3">
            <?php echo $form->textFieldRow($model,'nome',array('class'=>'span12')); ?>
        </div>

        <div class="column span3">
            <?php echo $form->textFieldRow($model,'login',array('class'=>'span12')); ?>
        </div>
    </div>

    <div class="row-fluid">
        <div class="column span3">
            <?php echo $form->passwordFieldRow($model,'senha', array('class' => 'span12')); ?>
        </div>

        <div class="column span3">
            <?php echo $form->passwordFieldRow($model,'senha2', array('class' => 'span12')); ?>
        </div>
    </div>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'span6')); ?>

    <div class="row-fluid">
        <div class="column span3">
            <?php echo $form->dropDownListRow($model,'usuario_role_id', CHtml::listData(UsuarioRole::model()->doNivelDoUsuario(Yii::app()->user->getUser())->findAll(array('order' => 'id')), 'id', 'nome'), array('empty' => Yii::t('Site','Selecione...'), 'class' => 'span12')); ?>
        </div>

        <div class="column span3">
            <?php if(Yii::app()->user->isRoot()) : ?>

                <?php echo $form->dropDownListRow($model,'municipio_id', CHtml::listData(Municipio::model()->findAll(array('order' => 'nome')), 'id', 'nome'), array('empty' => Yii::t('Site','Selecione...'), 'class' => 'span12')); ?>

            <?php endif; ?>
        </div>
    </div>
		

		

<?php echo CHtml::openTag('div',array('class'=>'form-btns'));

       $this->widget(
                'bootstrap.widgets.TbButton', 
                array(
                    'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'size'=>'small', // null, 'large', 'small' or 'mini'
                    'buttonType'=>'submit', 
                    'label'=> $model->isNewRecord ? Yii::t('Usuario','Cadastrar') : Yii::t('Usuario','Atualizar')
                )
        ); 
        echo CHtml::link('Cancelar',array('usuario/index'),array('class'=>'link','rel'=>'tooltip','data-title'=>'Ir para a tela de exibição de todos os Usuarios'));

    echo CHtml::closeTag('div');

$this->endWidget(); ?>
