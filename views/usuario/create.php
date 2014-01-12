<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Usuario $model
 */

$this->title = 'Cadastrar Usuário';
$this->params['breadcrumbs'][] = ['label' => 'Usuários', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
