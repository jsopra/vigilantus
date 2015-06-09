<?php
use app\models\SocialHashtag;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="social-hashtag-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'termo')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'ativo')->checkbox() ?>
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
                array('/ocorrencia/social-hashtag/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Social Hashtags')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>

</div>
