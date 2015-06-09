<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\SocialHashtag $model
 */

$this->title = 'Cadastrar Termo de Monitoramento';
$this->params['breadcrumbs'][] = ['label' => 'Termos de Monitoramento de Redes Sociais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="social-hashtag-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
