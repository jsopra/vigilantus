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
            <?= $form->field($model, 'endereco')->textInput(['placeholder' => 'Exemplo: Rua dos Alfeneiros 4']) ?>
        </div>

        <div class="row">
            <?= $form->field($model, 'pontos_referencia')->textInput(['placeholder' => 'Exemplo: próximo ao bar do caldeirão furado']) ?>
        </div>

        <div class="row mapa">
            <?= $form->field($model, 'coordenadasJson')->hiddenInput()->label('Posicione o marcador abaixo no local exato onde ocorreu o problema:') ?>
            <div id="map"></div>
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
        var distanciaMapa = 13;

        L.mapbox.accessToken = 'pk.eyJ1IjoidmlnaWxhbnR1cyIsImEiOiJXVEZJM1RFIn0.PWHuvfBY6oegZu3R65tWGA';
        var map = L.mapbox
            .map('map', 'vigilantus.kjkb4j0a')
            .setView([" . $municipio->latitude . " , " . $municipio->longitude . "], distanciaMapa);

        var marker;
        var featureGroup = L.featureGroup().addTo(map);

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

        var setMarkerPositionFromMapBoxApiResult = function(coordinates) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(new L.LatLng(coordinates[1], coordinates[0])).addTo(featureGroup);
            map.setView([coordinates[1], coordinates[0]], distanciaMapa + 2);
        };

        var ajaxPosicaoMapa = function(id_bairro, successCallback, errorCallback) {
            var url = '" . Url::to(['registrar-ocorrencia/coordenadas-bairro']) . "/';
            url += '?id=" . $municipio->id . "';
            url += '&bairro_id=' + id_bairro;

            if (undefined == successCallback || null == successCallback) {
                successCallback = function() { };
            }

            if (undefined == errorCallback || null == errorCallback) {
                errorCallback = function() { };
            }

            $.ajax({
                url: url,
                dataType: 'json',
                success: successCallback,
                error: errorCallback
            });
        };

        var buscarPosicaoMapa = function(e) {
            var id_bairro = $('#ocorrenciaform-bairro_id').val();
            if (id_bairro) {
                return ajaxPosicaoMapa(id_bairro, setMarkerPositionFromMapBoxApiResult);
            }
        };

        $(document).ready(function(){
            var coordenadas = '" . ($model->coordenadasJson ?: '') . "';
            var coordinates = coordenadas.split(',');

            if (coordinates.length != 2) {
                coordinates = [" . $municipio->longitude . " , " . $municipio->latitude . "];
            }

            marker = L.marker(
                new L.LatLng(coordinates[1], coordinates[0]),
                {draggable: true}
            ).addTo(featureGroup);
            map.setView([coordinates[1], coordinates[0]], distanciaMapa);

            $('#ocorrenciaform-bairro_id').on('change', buscarPosicaoMapa);
        });
    ";

    $this->registerJs($javascript);
    ?>
<?php endif; ?>
