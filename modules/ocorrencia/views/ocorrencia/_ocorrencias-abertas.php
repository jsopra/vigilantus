<?php
use yii\helpers\Html;
use app\helpers\models\OcorrenciaHelper;
use app\models\OcorrenciaStatus;

$tempoAberto = OcorrenciaHelper::getTempoAberto($model);
$buttons = OcorrenciaHelper::getIcons();
$averiguacoes = $model->quantidadeAveriguacoes;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-6">
                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> <?= \Yii::$app->formatter->asDate($model->data_criacao . ' ' . Yii::$app->timeZone); ?> <?= $tempoAberto ? " (" . $tempoAberto . ")" : ''; ?>
                </div>
                <div class="col-xs-6 text-right">
                    <span class="label label-primary"><strong><?= OcorrenciaStatus::getDescricao($model->status); ?></strong></span>
                </div>
            </div>
            <hr />
            <?php if($model->numero_controle) : ?>
            <div class="row">
                <div class="col-xs-12">
                    <p><strong># <?= $model->numero_controle; ?></strong></p>
                </div>
            </div>
            <?php endif; ?>
            <?php if($model->tipo_imovel || $model->ocorrenciaTipoProblema) : ?>
                <div class="row">
                    <div class="col-xs-12 text-left">
                        <?php if($model->tipo_imovel) : ?>
                            <p><span class="glyphicon glyphicon-home" aria-hidden="true"></span> <strong><?= Html::encode(\app\models\OcorrenciaTipoImovel::getDescricao($model->tipo_imovel)); ?></strong></p>
                        <?php endif; ?>
                        <?php if($model->ocorrenciaTipoProblema) : ?>
                            <p><span class="glyphicon glyphicon-tag" aria-hidden="true"></span> <strong><?= $model->ocorrenciaTipoProblema->nome; ?></strong></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-xs-12">
                    <p><strong>Endereço:</strong> <?= $model->endereco; ?>, <?= $model->bairro->nome; ?></p>
                    <?php if($model->pontos_referencia) : ?>
                        <p><strong>Referência:</strong> <?= $model->pontos_referencia ?>; ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php if($model->mensagem) : ?>
                <div class="row">
                    <div class="col-xs-12 text-left">
                        <p><strong>Mensagem:</strong> <?= $model->mensagem; ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-xs-3 text-left">
                <?php if($averiguacoes > 0) : ?>
                    <?= Html::a('<i class="glyphicon glyphicon-user"></i>', 'javascript:verAveriguacoes(' . $model->id . ')', [
                        'title' => Yii::t('yii', 'Ver averiguações'),
                        'class' => 'btn btn-default'
                    ]); ?>
                <?php endif; ?>
            </div>
            <div class="col-xs-9 text-right">
                <?php foreach($buttons as $button) : ?>
                    <?= $button($model, ['class' => 'btn btn-default']); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
