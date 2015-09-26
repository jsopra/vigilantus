<?php
use app\models\Bairro;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use perspectivain\mapbox\MapBoxAPIHelper;

$this->title = 'Registre uma ocorrência para Prefeitura Municipal de ' . $municipio->nome . '/' . $municipio->sigla_estado;
?>

<?= $this->render('_header', ['municipio' => $municipio, 'cliente' => $cliente, 'activeTab' => $activeTab]); ?>

<?php MapBoxAPIHelper::registerScript($this, ['drawing', 'fullScreen']); ?>

<div class="bloco-etapa-registro-ocorrencia">
    <h2>Descreva o <strong>local da ocorrência</strong></h2>

    <?php $form = ActiveForm::begin(['options' => []]); ?>
        <div class="row">
            <?= $form->field($model, 'endereco') ?>
        </div>

        <div class="row">
            <?= $form->field($model, 'tipo_imovel')->widget(
                Select2::classname(),
                [
                    'data' => ['' => ''] + \app\models\OcorrenciaTipoImovel::getDescricoes(),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]
            );
            ?>
        </div>

        <div class="row">
            <?php
            $bairros = Bairro::find()->doCliente($cliente->id)->comQuarteiroes()->orderBy('nome')->all();
            echo $form->field($model, 'bairro_id')->widget(
                Select2::classname(),
                [
                    'data' => ['' => ''] + ArrayHelper::map($bairros, 'id', 'nome'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]
            );
            ?>
        </div>

        <div class="row">
            <?= $form->field($model, 'pontos_referencia') ?>
        </div>

        <div class="row">
            <?= $form->field($model, 'coordenadasJson')->hiddenInput()->label(false) ?>
            <div id="map" style="height: 300px; width: 100%; margin-bottom: 1em;"></div>
        </div>

        <div class="form-group text-center">
            <?= Html::submitButton('Próximo passo', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$municipio->loadCoordenadas();
if ($municipio->latitude && $municipio->longitude) : ?>

    <?php
    $javascript = "
        L.mapbox.accessToken = 'pk.eyJ1IjoidmlnaWxhbnR1cyIsImEiOiJXVEZJM1RFIn0.PWHuvfBY6oegZu3R65tWGA';
        var map = L.mapbox
            .map('map', 'vigilantus.kjkb4j0a')
            .setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 13);

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

        L.control.fullscreen().addTo(map);

        L.control.scale().addTo(map);

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

        function coordinatesToInput(coordinates) {
            $('#ocorrenciaform-coordenadasjson').val(coordinates.join());
        }

        $(document).ready(function(){

            $('#map')." . ($model->endereco ? 'show' : 'hide') . "();

            var marker;

            $('#ocorrenciaform-endereco').select2({
                multiple: false,
                placeholder: 'Digite o endereço...',
                allowClear: true,
                ajax: {
                    multiple: false,
                    url: function(args) {
                        var uri = 'https://api.mapbox.com/v4/geocode/mapbox.places/';
                        uri += args;
                        uri += '.json?proximity=" . $municipio->longitude . "," . $municipio->latitude . "&access_token=' + L.mapbox.accessToken;
                        return uri;
                    },
                    dataType: 'json',
                    data: function (term, page) {
                        return {};
                    },
                    results: function (data, page) {
                        return {
                            results : $.map(data.features, function (item) {
                                return {
                                    text:item.place_name, slug:item.place_name, id:item.center.join()
                                }
                            })
                        };
                    }
                },
                initSelection: function(element, callback) {
                    var id = '" . ($model->coordenadasJson ? $model->coordenadasJson  : 'null') . "';
                    var text = '" . ($model->endereco ? $model->endereco : 'null') . "';
                    var data = {id: id, text: text, slug: text};
                    if(marker) {
                        map.removeLayer(marker);
                    }
                    var coordinates = id.split(',');
                    marker = L.marker(new L.LatLng(coordinates[1], coordinates[0])).addTo(featureGroup);
                    map.setView([coordinates[1], coordinates[0]], 15);

                    callback(data);
                }
            });

            $('#ocorrenciaform-endereco').on('change', function(e) {
                if($(this).val() != '') {
                    if(marker) {
                        map.removeLayer(marker);
                    }
                    var coordinates = $(this).val().split(',');
                    marker = L.marker(new L.LatLng(coordinates[1], coordinates[0])).addTo(featureGroup);
                    map.setView([coordinates[1], coordinates[0]], 15);
                    $('#map').show();
                    $('#ocorrenciaform-coordenadasjson').val($('#ocorrenciaform-endereco').select2('data').id);
                } else {
                    $('#map').hide();
                    featureGroup.removeLayer(marker);
                    map.setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 13);
                }
            });

            $('form').submit(function() {
                $('#ocorrenciaform-endereco').val($('#ocorrenciaform-endereco').select2('data').text);
            });
        });
    ";

    $this->registerJs($javascript);
    ?>
<?php endif; ?>
