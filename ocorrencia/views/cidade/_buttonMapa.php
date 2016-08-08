<?php
use yii\helpers\Url;
?>

<a type="button" class="btn btn-danger btn-lg" data-toggle="tooltip" data-placement="bottom" title="Ver o mapa de transmissores" href="<?= Url::to(['cidade/index', 'id' => $cliente->id]); ?>">
    <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
    &nbsp; Ver o mapa
</a>
