<?php
use app\models\Bairro;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

$this->title = 'Áreas de Tratamento';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mapa-area-tratamento-index" data-role="modal-grid">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="form well">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>

            <div class="row" id="dadosPrincipais">
                <div class="col-xs-4">
                    <?= $form->field($model, 'bairro_id')->dropDownList(Bairro::listData('nome'), ['prompt' => 'Selecione…']) ?>
                </div>

                <div class="col-xs-2">
                    <?= $form->field($model, 'lira')->dropDownList([0 => 'Não', 1 => 'Sim'], ['prompt' => 'Selecione…']) ?>
                </div>

                <div class="col-xs-2" style="padding-top: 20px;">
                    <?= Html::submitButton('Gerar', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>

<?php
echo Tabs::widget([
    'items' => [
        [
            'label' => 'Mapa',
            'content' => $this->render('_area-tratamento-mapa', ['model' => $model], true),
            'active' => true
        ],
        [
            'label' => 'Áreas de Tratamento',
            'content' => $this->render('_area-tratamento', ['model' => $model], true),
        ],
        [
            'label' => 'Focos',
            'content' => $this->render('_area-tratamento-foco', ['model' => $model], true),
        ],
    ],
]);
?>