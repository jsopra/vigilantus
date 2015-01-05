<?php

use app\models\BairroCategoria;
use app\models\Municipio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\MapBoxAPIHelper;
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
$municipio = \Yii::$app->session->get('user.cliente')->municipio;
$municipio->loadCoordenadas();

$coordenadasBairros = $municipio->getCoordenadasBairros(array($model->id));
?>

<?php
if ($municipio->latitude && $municipio->longitude) :

    $javascript = "
    L.mapbox.accessToken = 'pk.eyJ1IjoidmlnaWxhbnR1cyIsImEiOiJXVEZJM1RFIn0.PWHuvfBY6oegZu3R65tWGA';
    var map = L.mapbox
        .map('map', 'vigilantus.kjkb4j0a')
        .setView([" . $municipio->latitude . ", " . $municipio->longitude . "], 13)
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

    var runLayer = omnivore.kml('" . Url::to(['kml/cidade']) . "', null, bairrosLayer)
        .on('ready', function() {
            map.fitBounds(runLayer.getBounds());
        })
        .addTo(map);
    ";

    $qtdeBairrosComCoordenada = count($coordenadasBairros);

    if ($qtdeBairrosComCoordenada > 0) :

        $i = 0;
        foreach($coordenadasBairros as $bairroDados) :


        endforeach;
    endif;

    if ($model->coordenadasJson) :

    endif;

$this->registerJs($javascript);
endif;
