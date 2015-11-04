<?php

use yii\helpers\Html;
use app\models\Ocorrencia;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = 'Reprovar Ocorrência #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrências', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Reprovar';

if ($model->email) {
    Yii::$app->session->setFlash('warning', 'ATENÇÃO: O denunciante preencheu o email ao fazer a ocorrência, assim ele receberá uma mensagem informando sobre a atualização do status.');
}
?>
<div class="ocorrencia-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="ocorrencia-form">

        <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-xs-12">
                    <?= $form->field($modelForm, 'observacoes')->textArea() ?>
                </div>
            </div>

            <div class="form-group form-actions">
                <?php
                echo Html::submitButton(
                    'Enviar',
                    ['class' => 'btn btn-flat primary']
                );

                echo Html::a(
                    'Cancelar',
                    Yii::$app->request->referrer,
                    array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Ocorrências')
                );
                ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
