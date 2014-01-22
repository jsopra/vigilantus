<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\ContactForm $model
 */
$this->title = 'Contato';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Obrigado por entrar em contato conosco. Responderemos o mais breve possível.
        </div>

    <?php else: ?>

        <p>Preencha o formulário para entrar em contato conosco.</p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                
                    <div class="row">
                        <div class="col-xs-6">
                            <?= $form->field($model, 'name') ?>
                        </div>
                        <div class="col-xs-6">
                            <?= $form->field($model, 'email') ?>
                        </div>
                    </div>
                
                    <?= $form->field($model, 'subject') ?>
                    <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>
                    <?=
                    $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ])
                    ?>
                    <div class="form-group">
                        <?= Html::submitButton('Enviar', ['class' => 'btn btn-flat primary']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
