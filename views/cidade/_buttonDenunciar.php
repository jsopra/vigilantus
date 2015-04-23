<?php
use yii\helpers\Url;
?>
<a type="button" class="btn btn-danger btn-lg" data-toggle="tooltip" data-placement="bottom" title="Sua denúncia será avaliada pela Prefeitura Municipal e você receberá acesso para acompanhar a resolução" href="<?= Url::to(['cidade/denunciar', 'id' => $cliente->id]); ?>">
    <span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span>
    &nbsp; Faça uma denúncia
</a>
