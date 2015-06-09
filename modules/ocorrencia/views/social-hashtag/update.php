<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\SocialHashtag $model
 */

$this->title = 'Atualizar Termo de Monitoramento: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Termos de Monitoramento de Redes Sociais', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="social-hashtag-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
