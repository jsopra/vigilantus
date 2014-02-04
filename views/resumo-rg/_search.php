<?php

use app\models\Bairro;
use app\models\Municipio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\search\BoletimRgSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="boletim-rg-fechamento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

        <div class="row" id="dadosPrincipais">
            <div class="col-xs-4">
                <?= $form->field($model, 'bairro_id')->dropDownList(Bairro::listData('nome'), ['prompt' => 'Selecione…']) ?>
            </div>
            
            <div class="col-xs-2" style="padding-top: 20px;">
                <?= Html::submitButton('Gerar Planilha', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
