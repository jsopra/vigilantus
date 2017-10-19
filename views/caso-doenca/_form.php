<?php
use app\models\Doenca;
use app\models\Bairro;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\BairroQuarteirao;
use perspectivain\mapbox\MapBoxAPIHelper;
use app\helpers\MapHelper;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Url;
use app\models\casodoenca;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;
use yii\web\JsExpression;

MapBoxAPIHelper::registerScript($this, ['drawing', 'fullScreen', 'minimap', 'omnivore']);
?>

<div class="caso-doenca-form">

	<?php $form = ActiveForm::begin(); ?>

        <div>
            <div class="row">
                <div class="col-xs-3">
                    <?php
                        $doencas = Doenca::find()->orderBy('nome')->all();
                        echo $form->field($model, 'doenca_id')->dropDownList(ArrayHelper::map($doencas, 'id', 'nome'), ['prompt' => 'Selecione..']);
                    ?>
                </div>
            <div class="row">
                <div class="row" id="bairroQuarteirao">
                    <div class="col-xs-4">
                    <?php
                    $bairros = Bairro::find()->comQuarteiroes()->orderBy('nome')->all();
                    echo $form->field($model, 'bairro_id')->dropDownList(ArrayHelper::map($bairros, 'id', 'nome'), ['prompt' => 'Selecione..']);
                    ?>
                    </div>
                    <div class="col-xs-4 bairro-hide">
                        <?php
                        $quarteiroes = BairroQuarteirao::find()->doBairro($model->bairro_id)->orderBy('numero_quarteirao')->all();
                        echo $form->field($model, 'bairro_quarteirao_id')->dropDownList(ArrayHelper::map($quarteiroes, 'id', 'numero_quarteirao'));
                        ?>
                    </div>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-xs-3">
                    <?= $form->field($model, 'data_sintomas')->textInput() ?>
                </div>
                <div class="col-xs-4">
                    <?= $form->field($model, 'nome_paciente')->textInput() ?>
                </div>
            </div>

             <?= Html::error($model, 'latitude',['class' => 'help-block']); ?>

        <?= Html::error($model, 'longitude',['class' => 'help-block']); ?>

        <div class="row mapa">
            <?= $form->field($model, 'coordenadasJson')->hiddenInput()->label('Posicione o marcador abaixo no local exato onde ocorreu o problema:') ?>
            <div id="map"></div>
        </div>


            <div class="form-group form-actions">
    			<?php
                    echo Html::submitButton(
                        $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                        ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
                    );

                    echo Html::a(
                        'Cancelar',
                        array('/caso-doenca/index'),
                        array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Caso Doencas')
                );
                ?>
             </div>

	<?php ActiveForm::end(); ?>

</div>

<?php
$view = Yii::$app->getView();
$script = '
    jQuery(document).ready(function(){

    var bairroID = null;
    var quarteiraoID = null;
';

if(!$model->bairro_id) {
    $script .= 'jQuery(".bairro-hide").hide();';
}
else {
    $script .= 'bairroID = ' . $model->bairro_id . ';';
}

if($model->bairro_quarteirao_id) {
    $script .= 'quarteiraoID = ' . $model->bairro_quarteirao_id . ';';
}

$script .= '
    if(bairroID) {

        jQuery.getJSON("' . Url::toRoute(['caso-doenca/bairroQuarteiroes', 'bairro_id' => '']) . '" + bairroID, function(data) {

            options = $("#casodoenca-bairro_quarteirao_id");
            options.append($("<option />").val("").text("Selecione..."));
            $.each(data, function(key, desc) {
                options.append($("<option />").val(key).text(desc));
            });

            jQuery("#casodoenca-bairro_quarteirao_id").parent().parent().show();

            if(quarteiraoID) {
                jQuery("#casodoenca-bairro_quarteirao_id").val(quarteiraoID);
            }
        });
    }

    jQuery("#casodoenca-bairro_id").change(function() {

        if(jQuery(this).val() == "") {
            jQuery(".bairro-hide").hide();
            bairroID = null;
        }
        else {

            bairroID = jQuery(this).val();

            jQuery(this).attr("disabled","disabled");

            jQuery.getJSON("' . Url::toRoute(['caso-doenca/bairroQuarteiroes', 'bairro_id' => '']) . '" + bairroID, function(data) {

                $("#casodoenca-bairro_quarteirao_id").val("");

                options = $("#casodoenca-bairro_quarteirao_id");
                options.append($("<option />").val("").text("Selecione..."));
                $.each(data, function(key, desc) {
                    options.append($("<option />").val(key).text(desc));
                });

                jQuery("#casodoenca-bairro_quarteirao_id").parent().parent().show();
            });

        }
    });

    jQuery("form").submit(function(){
';

if($model->isNewRecord) {
    $script .= '
        jQuery("#casodoenca-bairro_id").removeAttr("disabled");

        jQuery("#casodoenca-bairro_id").attr("readonly","readonly");
     });
});
';
}
else {
    $script .= '
        jQuery("#bairroQuarteirao").find("input").removeAttr("disabled");
        jQuery("#bairroQuarteirao").find("select").removeAttr("disabled");

        jQuery("#bairroQuarteirao").find("input").attr("readonly","readonly");
        jQuery("#bairroQuarteirao").find("select").attr("readonly","readonly");
     });
});
';
}

$view->registerJs($script);

$municipio = \Yii::$app->user->identity->cliente->municipio;
$municipio->loadCoordenadas();

if($model->latitude && $model->longitude) {
    $model->loadCoordenadas();
}
if ($municipio->latitude && $municipio->longitude) : ?>
?>

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
            $('#casodoenca-coordenadasjson').val(coordinates.join());
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
            var url = '" . Url::to(['caso-doenca/bairroQuarteiroes']) . "/';
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
            var id_bairro = $('#casodoenca-bairro_id').val();
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

            $('#casodoenca-bairro_id').on('change', buscarPosicaoMapa);
        });
    ";

    $this->registerJs($javascript);
    ?>
<?php endif; ?>
