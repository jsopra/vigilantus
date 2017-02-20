<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OcorrenciaStatus;
use kartik\rating\StarRating;

$this->title = 'Solução da ocorrência #' . $model->id;
$this->params['breadcrumbs'][] = 'Avaliar Ocorrências';

?>
<div class="ocorrencia-avaliar">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([]); ?>
        <div class="col-xs-12">
            <?= $form->field($model, 'rating')->widget(StarRating::classname(), [
                'pluginOptions' => [
                    'size'=>'lg',
                    'showClear' => false,
                    'showCaption' => false,
                ]
            ]); ?>
        </div>

        <div class="col-xs-12">
            <?= $form->field($model, 'comentario_avaliacao')
                ->hint('Caso deseje, descreva como foi o atendimendo deste ocorrência.')
                ->textArea() ?>
        </div>

    <div class="form-group form-actions">
            <?php
            echo Html::submitButton(
                'Avaliar',
                ['class' => 'btn btn-flat success']
            );
            ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
