<?php
$class=get_class($model);
Yii::app()->clientScript->registerScript('gii.model',"
$('#{$class}_modelClass').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('#{$class}_tableName').bind('keyup change', function(){
	var model=$('#{$class}_modelClass');
	var tableName=$(this).val();
	if(tableName.substring(tableName.length-1)!='*') {
		$('.form .row.model-class').show();
	}
	else {
		$('#{$class}_modelClass').val('');
		$('.form .row.model-class').hide();
	}
	if(!model.data('changed')) {
		var i=tableName.lastIndexOf('.');
		if(i>=0)
			tableName=tableName.substring(i+1);
		var tablePrefix=$('#{$class}_tablePrefix').val();
		if(tablePrefix!='' && tableName.indexOf(tablePrefix)==0)
			tableName=tableName.substring(tablePrefix.length);
		var modelClass='';
		$.each(tableName.split('_'), function() {
			if(this.length>0)
				modelClass+=this.substring(0,1).toUpperCase()+this.substring(1);
		});
		model.val(modelClass);
	}
});
$('.form .row.model-class').toggle($('#{$class}_tableName').val().substring($('#{$class}_tableName').val().length-1)!='*');
");
?>
<h1>Gerador de Modelos</h1>

<p>Este gerador produz uma classe de modelo para a tabela de banco de dados especificada.</p>

<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>

	<div class="row sticky">
		<?php echo $form->labelEx($model,'tablePrefix'); ?>
		<?php echo $form->textField($model,'tablePrefix', array('size'=>65)); ?>
		<div class="tooltip">
		This refers to the prefix name that is shared by all database tables.
		Setting this property mainly affects how model classes are named based on
		the table names. For example, a table prefix <code>tbl_</code> with a table name <code>tbl_post</code>
		will generate a model class named <code>Post</code>.
		<br/>
		Leave this field empty if your database tables do not use common prefix.
		</div>
		<?php echo $form->error($model,'tablePrefix'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'tableName'); ?>
		<?php echo $form->textField($model,'tableName', array('size'=>65)); ?>
		<div class="tooltip">
		This refers to the table name that a new model class should be generated for
		(e.g. <code>tbl_user</code>). It can contain schema name, if needed (e.g. <code>public.tbl_post</code>).
		You may also enter <code>*</code> (or <code>schemaName.*</code> for a particular DB schema)
		to generate a model class for EVERY table.
		</div>
		<?php echo $form->error($model,'tableName'); ?>
	</div>
	<div class="row model-class">
		<?php echo $form->label($model,'modelClass',array('required'=>true)); ?>
		<?php echo $form->textField($model,'modelClass', array('size'=>65)); ?>
		<div class="tooltip">
		This is the name of the model class to be generated (e.g. <code>Post</code>, <code>Comment</code>).
		It is case-sensitive.
		</div>
		<?php echo $form->error($model,'modelClass'); ?>
	</div>
	<div class="row sticky">
		<?php echo $form->labelEx($model,'baseClass'); ?>
		<?php echo $form->textField($model,'baseClass',array('size'=>65)); ?>
		<div class="tooltip">
			This is the class that the new model class will extend from.
			Please make sure the class exists and can be autoloaded.
		</div>
		<?php echo $form->error($model,'baseClass'); ?>
	</div>
	<div class="row sticky">
		<?php echo $form->labelEx($model,'modelPath'); ?>
		<?php echo $form->textField($model,'modelPath', array('size'=>65)); ?>
		<div class="tooltip">
			Se refere ao diretório em que a nova classe de modelo deve ser gerada.
			Deve ser especificado no formato de <em>path alias</em>, por exemplo, <code>webol.models</code>.
		</div>
		<?php echo $form->error($model,'modelPath'); ?>
	</div>
	<div class="row sticky">
		<?php echo $form->labelEx($model,'testUnitPath'); ?>
		<?php echo $form->textField($model,'testUnitPath', array('size'=>65)); ?>
		<div class="tooltip">
			Se refere ao diretório em que o teste unitário da nova classe de modelo deve ser gerado.
			Deve ser especificado no formato de <em>path alias</em>, por exemplo, <code>webol.tests.unit</code>.
		</div>
		<?php echo $form->error($model,'testUnitPath'); ?>
	</div>
	<div class="row sticky">
		<?php echo $form->labelEx($model,'testFixturesPath'); ?>
		<?php echo $form->textField($model,'testFixturesPath', array('size'=>65)); ?>
		<div class="tooltip">
			Se refere ao diretório em que o arquivo das fixtures da nova classe de modelo deve ser gerado.
			Deve ser especificado no formato de <em>path alias</em>, por exemplo, <code>webol.tests.fixtures</code>.
		</div>
		<?php echo $form->error($model,'testFixturesPath'); ?>
	</div>

<?php $this->endWidget(); ?>
