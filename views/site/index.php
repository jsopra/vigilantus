<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 */
$this->title = Yii::$app->name;
?>
<div class="site-index">

	<div class="jumbotron">
		<h1><?= Html::encode($this->title); ?></h1>

		<p class="lead">Apoio e gestão de prevenção da Dengue</p>

		<p><a class="btn btn-lg btn-success" href="#">Saiba mais sobre o projeto</a></p>
	</div>

	<div class="body-content">

		<div class="row">
			<div class="col-lg-4">
				<h2>Instrumentalização</h2>

				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
					dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
					ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
					fugiat nulla pariatur.</p>

				<p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
			</div>
			<div class="col-lg-4">
				<h2>Integração</h2>

				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
					dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
					ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
					fugiat nulla pariatur.</p>

				<p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
			</div>
			<div class="col-lg-4">
				<h2>Apoio à Decisão</h2>

				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
					dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
					ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
					fugiat nulla pariatur.</p>

				<p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
			</div>
		</div>

	</div>
</div>
