<header>
	<h1 class="pathway"><?php echo Yii::app()->name; ?></h1>
</header>

<section>
	<div class="error">
		
		<h2>Erro <?php echo $code; ?></h2>
		<?php echo CHtml::encode($message); ?>	
	
	</div>
	
</section>