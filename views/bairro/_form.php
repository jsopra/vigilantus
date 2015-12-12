<?php
use app\models\Municipio;
use app\models\BairroCategoria;
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
            <div class="col-xs-3">
                <?= $form->field($model, 'bairro_categoria_id')->dropDownList(['' => 'Selecione...'] + BairroCategoria::listData('nome')) ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'nome') ?>
            </div>
        </div>

        <?= Html::error($model, 'coordenadasJson',['class' => 'help-block']); ?>

        <div id="map"  style="height: 450px; width: 100%;"></div>

        <?= Html::activeHiddenInput($model, 'coordenadasJson'); ?>

        <div class="form-group form-actions">
            <?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
            );

            echo Html::a(
                'Cancelar',
                array('/bairro/index'),
                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de bairros')
            );

            ?>

       </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$municipio = \Yii::$app->user->identity->cliente->municipio;
$municipio->loadCoordenadas();

$centro = null;
if($model->coordenadasJson) {
    $model->loadCoordenadas();
    $centro = $model->getCentro();
}
?>

<?php
$javascript = "
L.mapbox.accessToken = '" . Yii::$app->params['mapBoxAccessToken'] . "';
var map = L.mapbox
    .map('map', '" . Yii::$app->params['mapBoxMapID'] . "')
";

if ($centro) {
    $javascript .= ".setView([" . $centro[1] . ", " . $centro[0] . "], 14)";
} else {
    $javascript .= ".setView([" . $municipio->latitude . ", " . $municipio->longitude . "], 12)";
}

$javascript .= "
    .on('ready', function() {
        new L.Control.MiniMap(L.mapbox.tileLayer('" . Yii::$app->params['mapBoxMapID'] . "'))
            .addTo(map);
    });

L.control.fullscreen().addTo(map);

var featureGroup = L.featureGroup().addTo(map);

var drawControl = new L.Control.Draw({
    edit: {
        featureGroup: featureGroup
    },
    draw: {
        polygon: true,
        polyline: false,
        rectangle: false,
        circle: false,
        marker: false
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
    e.layer.bindPopup((LGeo.area(e.layer) / 1000000).toFixed(2) + ' km<sup>2</sup>');
    e.layer.openPopup();

    coordinatesToInput(e.layer.toGeoJSON().geometry.coordinates[0]);
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

var runLayer = omnivore.kml('" . Url::to(['kml/cidade', 'except' => $model->isNewRecord ? null : $model->id]) . "')
.on('ready', function() {
    this.eachLayer(function(layer) {

    });
})
.addTo(map);

function coordinatesToInput(coordinates) {
    $('#bairro-coordenadasjson').val(JSON.stringify(coordinates));
}
";

if ($model->coordenadasJson) :

    $javascript .= "

        var polygon_options = {
          color: '#000',
          opacity: 0.5,
          weight: 1,
          fillColor: '#000',
          fillOpacity: 0.2
      };

        var bairro = L.polygon([" . MapHelper::getArrayCoordenadas($model->coordenadas) ."], polygon_options).addTo(featureGroup);

        coordinatesToInput(bairro.toGeoJSON().geometry.coordinates[0]);

        bairro.editing.enable();
    ";
endif;

$this->registerJs($javascript);
