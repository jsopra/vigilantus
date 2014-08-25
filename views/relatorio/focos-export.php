<?php
use app\models\Bairro;
use app\models\EspecieTransmissor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Exportação de Focos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mapa-area-tratamento-index" data-role="modal-grid">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="form well">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>

            <div class="row">
                <div class="col-xs-3">
                    <?= $form->field($model, 'bairro_id')->dropDownList(Bairro::listData('nome'), ['prompt' => 'Selecione…']) ?>
                </div>

                <div class="col-xs-3">
                    <?= $form->field($model, 'especie_transmissor_id')->dropDownList(EspecieTransmissor::listData('nome'), ['prompt' => 'Selecione…']) ?>
                </div>
            </div>
        
            <div class="row">
                <div class="col-xs-3">
                    <?= $form->field($model, 'inicio')->input('date', ['class' => 'form-control input-datepicker']) ?>
                </div>

                <div class="col-xs-3">
                    <?= $form->field($model, 'fim')->input('date', ['class' => 'form-control input-datepicker']) ?>
                </div>
                
            </div>
        
            <div class="row">

                <div class="col-xs-2" style="padding-top: 20px;">
                    <?= Html::submitButton('Exportar', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>