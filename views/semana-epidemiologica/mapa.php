<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use yii\web\JsExpression;
use yii\helpers\Json;


$this->title = 'Mapa de visitas do agente';

$this->params['breadcrumbs'][] = ['label' => 'Semanas Epidemiológicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Visitas de Agentes', 'url' => ['agentes', 'cicloId' => $ciclo->id]];
$this->params['breadcrumbs'][] = $this->title;

MapBoxAPIHelper::registerScript($this, ['fullScreen', 'omnivore']);
?>
<div class="semana-epidemiologica-agendar">
    <h1><?= Html::encode($this->title) ?> para ciclo <span style="color: #797979;"><?= Html::encode($ciclo->nome) ?></span> </h1>

    <div id="map"  style="height: 450px; width: 100%;"></div>
</div>

<?php
$municipio = \Yii::$app->user->identity->cliente->municipio;
$municipio->loadCoordenadas();
?>

<?php if($municipio->latitude && $municipio->longitude) : ?>
    <?php
    $javascript = "
        var layers = document.getElementById('menu-ui');
        var line_points = " . Json::encode([]) . ";
        var polyline_options = {
            color: '#000'
        };

        L.mapbox.accessToken = '" . Yii::$app->params['mapBoxAccessToken'] . "';
        var map = L.mapbox
            .map('map', '" . Yii::$app->params['mapBoxMapID'] . "')
            .setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 11)
            .on('ready', function() {
            });

        L.control.fullscreen().addTo(map);

        L.featureGroup().addTo(map);

        L.control.scale().addTo(map);

        var armadilhasLayer = omnivore.kml('" . Url::to(['kml/semana-epidemiologica-visitas', 'cicloId' => $ciclo->id, 'agenteId' => $agente->id]) . "')
        .on('ready', function() {
            this.eachLayer(function(marker) {

                marker.setIcon(L.mapbox.marker.icon({
                    'marker-color': marker['feature']['properties']['status'] == 'Agendada' ? '#fc6a6a' : '#79A8E9',
                    'marker-size': 'small',
                    'marker-symbol': 'village'
                }));

            });
        })
        .addTo(map);
    ";

    $this->registerJs($javascript);
    ?>
<?php endif; ?>