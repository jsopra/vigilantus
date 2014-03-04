<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

/** @var \yii\db\ActiveRecord $model */
$model = new $generator->modelClass;
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
	$safeAttributes = $model->attributes();
}

echo "<?php\n";
?>
use app\models\<?= $generator->modelClass ?>;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

	<?= "<?php " ?>$form = ActiveForm::begin(); ?>

    <?php foreach ($safeAttributes as $attribute) : ?>
    <div class="row">
            <div class="col-xs-3">
                <?= "<?= " . $generator->generateActiveField($attribute) . " ?>\n"; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="form-group form-actions">
			<?= "<?php \n"; ?>
            Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar', 
                ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
            );
		
            Html::a(
                'Cancelar',
                array('/<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de <?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>')
            );
            <?= "?>\n"; ?>
        </div>

	<?= "<?php " ?>ActiveForm::end(); ?>

</div>
