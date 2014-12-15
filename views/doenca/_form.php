<?php
use app\models\Doenca;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="doenca-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'nome')->textInput() ?>
            </div>
        </div>

        <div class="form-group form-actions">
			<?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
            );

            echo Html::a(
                'Cancelar',
                array('/doenca/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Doencas')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>

</div>
