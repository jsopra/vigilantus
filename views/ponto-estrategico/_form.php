<?php
use app\models\Municipio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use perspectivain\mapbox\MapBoxAPIHelper;
use app\helpers\MapHelper;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonGroup;
use Yii\helpers\Url;

MapBoxAPIHelper::registerScript($this, ['drawing', 'fullScreen', 'minimap', 'omnivore']);
?>
<div class="bairro-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-6">
                <?= $form->field($model, 'descricao') ?>
            </div>
        </div>

        <?= Html::error($model, 'latitude',['class' => 'help-block']); ?>

        <?= Html::error($model, 'longitude',['class' => 'help-block']); ?>

        <div id="map"  style="height: 450px; width: 100%;"></div>

        <?= Html::activeHiddenInput($model, 'latitude'); ?>

        <?= Html::activeHiddenInput($model, 'longitude'); ?>

        <div class="form-group form-actions">
            <?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
            );

            echo Html::a(
                'Cancelar',
                array('/ponto-estrategico/index'),
                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de pontos estratégicos')
            );

            ?>

       </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$municipio = \Yii::$app->session->get('cliente')->municipio;
$municipio->loadCoordenadas();

if($model->latitude && $model->longitude) {
    $model->loadCoordenadas();
}
?>

<?php
$javascript = "
L.mapbox.accessToken = 'pk.eyJ1IjoidmlnaWxhbnR1cyIsImEiOiJXVEZJM1RFIn0.PWHuvfBY6oegZu3R65tWGA';
var map = L.mapbox
    .map('map', 'vigilantus.kjkb4j0a')
";

if($model->latitude && $model->longitude) {
    $javascript .= ".setView([" . $model->latitude . ", " . $model->longitude . "], 14)";
}
else {
    $javascript .= ".setView([" . $municipio->latitude . ", " . $municipio->longitude . "], 12)";
}

$javascript .= "
    .on('ready', function() {
        new L.Control.MiniMap(L.mapbox.tileLayer('vigilantus.kjkb4j0a'))
            .addTo(map);
    });

L.control.fullscreen().addTo(map);

var featureGroup = L.featureGroup().addTo(map);

var drawControl = new L.Control.Draw({
    edit: {
        featureGroup: featureGroup
    },
    draw: {
        polygon: false,
        polyline: false,
        rectangle: false,
        circle: false,
        marker: true
    }
}).addTo(map);

map.on('draw:created', showPolygonArea);
map.on('draw:edited', showPolygonAreaEdited);

function showPolygonAreaEdited(e) {
    e.layers.eachLayer(function(layer) {
        showPolygonArea({ layer: layer });
    });
}

function showPolygonArea(e) {
    featureGroup.clearLayers();
    featureGroup.addLayer(e.layer);
    coordinatesToInput(e.layer.toGeoJSON().geometry.coordinates);
}

var bairrosLayer = L.geoJson(null, {
    // http://leafletjs.com/reference.html#geojson-style
    style: function(feature) {
        return {
            color: '#f00'
        };
    },
    onEachFeature: function(feature, layer) {
        //layer.bindLabel(feature.properties.description);
    }
});

var runLayer = omnivore.kml('" . Url::to(['kml/ponto-estrategico', 'except' => $model->isNewRecord ? null : $model->id]) . "')
.on('ready', function() {
    this.eachLayer(function(marker) {
        marker.setIcon(L.mapbox.marker.icon({
            'marker-color': '#BBBBDC',
            'marker-size': 'small',
            'marker-symbol': 'golf'
        }));
    });
})
.addTo(map);

function coordinatesToInput(coordinates) {
    $('#pontoestrategico-latitude').val(JSON.stringify(coordinates[1]));
    $('#pontoestrategico-longitude').val(JSON.stringify(coordinates[0]));
}
";

if ($model->latitude && $model->longitude) :
    $javascript .= "
        var pontoestrategico = L.marker([" . $model->latitude . ", " . $model->longitude . "]).addTo(featureGroup);
        pontoestrategico.editing.enable();
    ";
endif;

$this->registerJs($javascript);
