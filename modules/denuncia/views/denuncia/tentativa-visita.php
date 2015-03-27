<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\DenunciaStatus;

$this->title = 'Informar tentativa de averiguação de Denúncia #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Denúncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Informar tentativa de averiguação';
?>
<div class="denuncia-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([]); ?>

    <div class="form-group form-actions">
            <?php
            echo Html::submitButton(
                'Cadastrar',
                ['class' => 'btn btn-flat success']
            );

            echo Html::a(
                'Cancelar',
                array('/denuncia/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Denúncias')
            );
            ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
