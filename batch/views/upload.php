<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\EspecieTransmissor $model
 */

$this->title = 'Importar de um Arquivo';
$this->params['breadcrumbs'][] = ['label' => 'Listagem', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="batch-upload">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <ul>
        <li>Faça o upload da planilha no formato CSV.</li>
        <li>A primeira linha será considerada como um cabeçalho e será ignorada pelo sistema.</li>
        <li>O separador deve ser o caractere <code>;</code></li>
    </ul>
    <div class="row">
        <div class="col-xs-3">
            <?= $form->field($model, 'file')->fileInput() ?>
        </div>
    </div>
    <div class="form-group form-actions">
        <?php
        echo Html::submitButton(
            'Enviar Arquivo',
            ['class' => 'btn btn-flat primary']
        );

        $exampleFileUrl = array(
            Yii::$app->controller->action->id,
            'downloadExample' => 1,
        );

        foreach (Yii::$app->controller->action->modelAttributes as $attribute => $value) {
            $exampleFileUrl[$attribute] = $value;
        }
        
        echo Html::a(
            '<i class="icon-download"></i> Baixar um arquivo de exemplo',
            $exampleFileUrl,
            array('class' => 'btn btn-default')
        );

        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>