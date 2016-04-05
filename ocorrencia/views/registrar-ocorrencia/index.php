<?php
use app\models\Bairro;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use perspectivain\mapbox\MapBoxAPIHelper;

$this->title = 'Registre uma ocorrência para Prefeitura Municipal de ' . Html::encode($municipio->nome . '/' . $municipio->sigla_estado);
?>

<?= $this->render('_header', ['municipio' => $municipio, 'activeTab' => $activeTab]); ?>

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
            $bairros = $municipio->getBairros()->orderBy('nome')->all();
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
            <?= $form->field($model, 'rua')->textInput(['placeholder' => 'Exemplo: Rua dos Alfeneiros']) ?>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'numero')->textInput(['placeholder' => 'Exemplo: 280']) ?>
            </div>
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'complemento')->textInput(['placeholder' => 'Exemplo: AP 201']) ?>
            </div>
        </div>

        <div class="row">
            <?= $form->field($model, 'pontos_referencia')->textInput(['placeholder' => 'Exemplo: próximo ao bar do caldeirão furado']) ?>
        </div>

        <div class="row mapa">
            <?= $form->field($model, 'coordenadasJson')->hiddenInput()->label('Posicione o marcador abaixo no local exato onde ocorreu o problema:') ?>
            <div id="map"></div>
        </div>

        <div class="form-group text-center text-xs-center">
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

        L.mapbox.accessToken = '" . Yii::$app->params['mapBoxAccessToken'] . "';
        var map = L.mapbox
            .map('map', '" . Yii::$app->params['mapBoxMapID'] . "')
            .setView([" . $municipio->latitude . " , " . $municipio->longitude . "], distanciaMapa);

        var marker;
        var featureGroup = L.featureGroup().addTo(map);

        L.control.fullscreen().addTo(map);
        L.control.scale().addTo(map);

        function coordinatesToInput(coordinates) {
            $('#ocorrenciaform-coordenadasjson').val(coordinates.join());
        }

        var setMarkerPositionFromMapBoxApiResult = function(coordinates) {
            setMarker(coordinates[1], coordinates[0], distanciaMapa + 2)
        };

        var setMarker = function(latitude, longitude, altitude) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(
                new L.LatLng(latitude, longitude),
                {draggable: true}
            ).addTo(featureGroup);
            map.setView([latitude, longitude], altitude);
            coordinatesToInput([longitude, latitude]);

            marker.on('dragend', function(e) {
                var coordinates = marker.getLatLng();
                coordinatesToInput([coordinates.lng, coordinates.lat]);
            });

        };

        var ajaxPosicaoMapa = function(id_bairro, successCallback, errorCallback) {
            var url = '" . Url::to(['registrar-ocorrencia/coordenadas-bairro']) . "/';
            url += '?slug=" . $municipio->slug . "';
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

            setMarker(coordinates[1], coordinates[0], distanciaMapa);

            $('#ocorrenciaform-bairro_id').on('change', buscarPosicaoMapa);
        });
    ";

    $this->registerJs($javascript);
    ?>
<?php endif; ?>
