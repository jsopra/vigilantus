<?php
use app\models\Municipio;
use app\models\Bairro;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use perspectivain\mapbox\MapBoxAPIHelper;
use app\helpers\MapHelper;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonGroup;
use Yii\helpers\Url;

MapBoxAPIHelper::registerScript($this, ['drawing', 'fullScreen', 'minimap', 'omnivore']);

?>
<div class="bairro-quarteirao-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-2">
                <?= $form->field($model, 'numero_quarteirao')->textInput() ?>
            </div>
            <div class="col-xs-2">
                <?= $form->field($model, 'numero_quarteirao_2')->textInput() ?>
            </div>
        </div>


        <div class="row">

            <div class="col-xs-3">
                <?= Html::label($model->getAttributeLabel('coordenadas_area'), 'bairroquarteirao-coordenadas_area', ['class' => 'form-group field-bairroquarteirao-coordenadas_area required']); ?>
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
                array('/bairro-quarteirao/index', 'parentID' => $bairro->id),
                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de quarteirões de bairros')
            );

            ?>

       </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$municipio = \Yii::$app->user->identity->cliente->municipio;
$municipio->loadCoordenadas();

$bairro->loadCoordenadas();
$bairroCentro = $bairro->getCentro();

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

if($model->coordenadasJson) {
    $javascript .= ".setView([" . $centro[1] . ", " . $centro[0] . "], 17)";
}
else {
    $javascript .= ".setView([" . $bairroCentro[1] . ", " . $bairroCentro[0] . "], 15)";
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

var runLayer = omnivore.kml('" . Url::to(['kml/bairro', 'id' => $bairro->id, 'except' => $model->isNewRecord ? null : $model->id]) . "')
.on('ready', function() {
    this.eachLayer(function(layer) {

        var quarteiraoPoligono = L.polygon(layer.feature.geometry.coordinates[0]);
        var quarteiraoCenter = quarteiraoPoligono.getBounds().getCenter();

        L.marker([quarteiraoCenter.lng, quarteiraoCenter.lat], {
            icon: L.divIcon({
                className: 'label',
                html: layer.feature.properties.numero_quarteirao,
                iconSize: [100, 40]
            })
        })
        .addTo(map);
    });
})
.addTo(map);

function coordinatesToInput(coordinates) {
    $('#bairroquarteirao-coordenadasjson').val(JSON.stringify(coordinates));
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

        var quarteirao = L.polygon([" . MapHelper::getArrayCoordenadas($model->coordenadas) ."], polygon_options).addTo(featureGroup);

        coordinatesToInput(" . MapHelper::getArrayCoordenadas($model->coordenadas) .");

        quarteirao.editing.enable();
    ";
endif;

$this->registerJs($javascript);
