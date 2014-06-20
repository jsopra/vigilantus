<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BlogPost $model
 */

$this->title = 'Cadastrar Post no Blog';
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-post-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
