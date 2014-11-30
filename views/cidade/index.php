<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
?>

<div class="row">
	<div class="col-md-6">

		<h1><?= Html::img(Url::base() . '/img/brasao/SC/chapeco.jpg');  //fix brasão da prefeitura ?>&nbsp;&nbsp;<?= Html::encode($municipio->nome . '/' . $municipio->sigla_estado) ?></h1>
	</div>

	<div class="col-md-3 col-md-offset-3" style="margin-top: 1em;">
		<div class="text-right">
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&appId=634366506660294&version=v2.0";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-share-button" data-href="" data-layout="button_count"></div>
		</div>
		<div class="text-right" style="margin-top: 1em; margin-right: -3em;">
			<a href="https://twitter.com/share" class="twitter-share-button" data-via="BrasilSemDengue" data-lang="pt">Tweetar</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		</div>
	</div>
</div>

<div class="row">

  	<div class="col-md-6">
		<?= $this->render($viewPartial, ['dados' => $dados, 'municipio' => $municipio, 'cliente' => $cliente]); ?>
	</div>

	<div class="col-md-5 col-md-offset-1">
		<?= $this->render('_denuncia', ['municipio' => $municipio, 'model' => $model, 'cliente' => $cliente]); ?>
	</div>
</div>