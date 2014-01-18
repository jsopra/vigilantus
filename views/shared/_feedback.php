<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="feedback-btn">
    <a title="Feedback" target="_blank" href="#" data-toggle="modal" data-target="#feedbackModal">Feedback</a>
</div>​

<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="Feedback" aria-hidden="true">
    
    <div class="modal-dialog">
        
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Feedback</h4>
            </div>
            
            <?php
            $form = ActiveForm::begin([
                'id' => 'feedback-form',
                'options' => ['class' => 'form'],
            ]);
            ?>

            <div class="modal-body">
                <?= $form->field($model, 'body')->textArea(['rows' => 4]) ?>
                <?php $model->url = $_SERVER["REQUEST_URI"]; ?>
                <?= Html::activeHiddenInput($model, 'url') ?>
            </div>

            <div class="modal-footer">
                <div class="pull-left">
                    <p class="modal-feedback-message"></p>
                </div>
                <div class="pull-right">
                    <?= Html::button('Cancelar', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
                    <?= Html::submitButton('Enviar', ['class' => 'btn btn-success submitFeedback']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div> 
</div>