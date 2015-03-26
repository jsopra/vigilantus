<?php
use app\models\Equipe;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="equipe-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-6">
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
                array('/equipe/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Equipes')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>

</div>
