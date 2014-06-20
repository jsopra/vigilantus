<?php
use app\models\BlogPost;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
?>

<div class="blog-post-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'titulo')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'descricao')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'texto')->widget(CKEditor::className(), [
                    'options' => ['rows' => 10],
                    'preset' => 'basic'
                ]) ?>
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
                array('/blog-post/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Blog Posts')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>

</div>
