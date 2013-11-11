<?php
$class=get_class($model);
Yii::app()->clientScript->registerScript('gii.crud',"
$('#{$class}_controller').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('#{$class}_model').bind('keyup change', function(){
	var controller=$('#{$class}_controller');
	if(!controller.data('changed')) {
		var id=new String($(this).val().match(/\\w*$/));
		if(id.length>0)
			id=id.substring(0,1).toLowerCase()+id.substring(1);
		controller.val(id);
	}
});
");
?>
<h1>Gerador de CRUDs</h1>

<p>Este gerador produz um controller e suas views que implementam as operações CRUD (Criar, Ler, Atualizar e Excluir) para os dados do modelo especificado.</p>

<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'model'); ?>
		<?php echo $form->textField($model,'model',array('size'=>65)); ?>
		<div class="tooltip">
			A classe do modelo é case-sensitive. Pode ser um nome de classe (por exemplo <code>Post</code>)
		    ou um caminho do arquivo da classe (por exemplo <code>webol.models.Post</code>).
		    Perceba que no primeiro caso a classe precisa estar no auto-load.
		</div>
		<?php echo $form->error($model,'model'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'controller'); ?>
		<?php echo $form->textField($model,'controller',array('size'=>65)); ?>
		<div class="tooltip">
			O ID do Controller é case-sensitive. Controllers de CRUD geralmente seguem o nome
			da classe do modelo com o qual trabalham. Seguem alguns exemplos abaixo:
			<ul>
				<li><code>post</code> gera <code>PostController.php</code></li>
				<li><code>postTag</code> gera <code>PostTagController.php</code></li>
				<li><code>admin/user</code> gera <code>admin/UserController.php</code>.
					Se a aplicação possui um módulo <code>admin</code> ativado, ao invés disso
					ele gerará o <code>UserController</code> (e outros códigos de CRUD)
					dentro do módulo.
				</li>
			</ul>
		</div>
		<?php echo $form->error($model,'controller'); ?>
	</div>

	<div class="row sticky">
		<?php echo $form->labelEx($model,'baseControllerClass'); ?>
		<?php echo $form->textField($model,'baseControllerClass',array('size'=>65)); ?>
		<div class="tooltip">
			Esta é a classe base de onde o controller do CRUD extenderá.
			Certifique-se de que a classe existe e está no autoload.
		</div>
		<?php echo $form->error($model,'baseControllerClass'); ?>
	</div>

<?php $this->endWidget(); ?>
