<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BlogPost $model
 */

$this->title = 'Atualizar Post do Blog: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Posts do Blog', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="blog-post-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
