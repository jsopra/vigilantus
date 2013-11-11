<!DOCTYPE HTML>
<?php Yii::app()->bootstrap->register(); ?>
<html lang="<?php echo Yii::app()->getLanguage(); ?>" xml:lang="<?php echo Yii::app()->getLanguage(); ?>">
    <head>
		<title><?php echo Yii::app()->name; ?></title>
        <meta charset="<?php echo Yii::app()->charset; ?>" />
		<meta name="description" content="Ferramenta de apoio e gestão de prevenção da Dengue: qualificação dos componentes do PNCD. Todos contra a dengue." />
		<meta name="keywords" content="ferramenta de apoio à prevenção da dengue, ferramenta de gestão à prevenção da dengue, ferramenta dengue, qualificação PNCD, apio ao agente de saúde, apoio à decisão saúde" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
		<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
		<?php Yii::app()->clientScript->registerCssFile(Yii::app()->getBaseUrl(true) . '/static/css/screen.css.php');?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl(true) . '/static/js/common.js.php'); ?>
		<link href='http://fonts.googleapis.com/css?family=Unica+One|Pathway+Gothic+One|Roboto+Condensed:400,700' rel='stylesheet' type='text/css' />
    </head>
	
    <body style="background-color:#FFF; text-align: center;">
		
		<div id="content">	
			<?php $this->widget('bootstrap.widgets.TbAlert', array(
				'block'=>true, // display a larger alert block?
				'fade'=>true, // use transitions?
				'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
				'alerts'=>array( // configurations per alert type
					'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				),
			)); ?>
			
			<?php echo $content; ?>	
		</div>
		
		<footer class="q4 ">
			<a name="form"></a>
			<div class="header">
				<p class="titulo">Maiores Informações</p>
			</div>

			<p class="subtitulo">Deseja receber maiores informações sobre a ferramenta?</p>

			<?php 
			$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id'=>'verticalForm',
				 'action' => Yii::app()->createUrl('default/index', array('#' => 'form')), 
			)); ?>

			<?php echo $form->textFieldRow($this->contactForm, 'nome', array('class'=>'span5')); ?>

			<?php echo $form->textFieldRow($this->contactForm, 'instituicao', array('class'=>'span5')); ?>

			<?php echo $form->textFieldRow($this->contactForm, 'telefone', array('class'=>'span5')); ?>

			<?php echo $form->textFieldRow($this->contactForm, 'email', array('class'=>'span5')); ?>

			<?php echo $form->textAreaRow($this->contactForm, 'mensagem', array('class'=>'span5', 'rows' => 5)); ?>

			<div class="row" style="margin-top: 10px;">
				<?php $this->widget(
						'bootstrap.widgets.TbButton', array(
							'buttonType'=>'submit', 
							'label'=>'Enviar', 
							'size'=>'large', 
							'type' => 'danger', 
							'htmlOptions' => array(
								'class' => 'span5'
							)
						)
				); ?>
			</div>
			<?php $this->endWidget(); ?>
			<div class="clearfix"></div>
			
		</footer>
		
		<p class="perspectiva">
			<a href="http://www.perspectiva.in">perspectiva<span class="domain">.in</span></a>	
		</p>
		</div>
	</body>
</html>
