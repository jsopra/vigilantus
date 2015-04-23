<?php
use app\helpers\models\MunicipioHelper;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="row">
    <div class="col-md-6">
        <h1>
            <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>&nbsp;&nbsp;<a href="<?= Url::to(['cidade/index', 'id' => $cliente->id]); ?>"><?= Html::encode($municipio->nome . '/' . $municipio->sigla_estado) ?></a>
        </h1>
    </div>

    <div class="col-md-3">

    </div>

    <div class="col-md-3" style="margin-top: 1em;">
        <div class="text-right">
            <?= $this->render($button, ['municipio' => $municipio, 'cliente' => $cliente]); ?>
        </div>
    </div>
</div>
