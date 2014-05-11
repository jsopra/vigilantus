<?php

use app\models\Municipio;
use app\models\Bairro;
use app\helpers\GoogleMapsAPIHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Áreas de Tratamento';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mapa-area-tratamento-index" data-role="modal-grid">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>

        <div class="row" id="dadosPrincipais">
            <div class="col-xs-4">
                <?= $form->field($model, 'bairro_id')->dropDownList(Bairro::listData('nome'), ['prompt' => 'Selecione…']) ?>
            </div>
            
            <div class="col-xs-2">
                <?= $form->field($model, 'lira')->dropDownList([0 => 'Não', 1 => 'Sim'], ['prompt' => 'Selecione…']) ?>
            </div>
            
            <div class="col-xs-2" style="padding-top: 20px;">
                <?= Html::submitButton('Gerar', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>

<div id="map"  style="height: 500px; width: 100%;"></div>
        
<?php
$municipio = Municipio::find()->one();
$municipio->loadCoordenadas();

$bairro = is_numeric($model->bairro_id) ? Bairro::findOne($model->bairro_id) : null;
if($bairro)
    $bairro->loadCoordenadas();

if(is_object($bairro) && !$bairro->coordenadasJson && $bairro->coordenadas) 
    $bairro->coordenadasJson = GoogleMapsAPIHelper::arrayToCoordinatesJson($bairro->coordenadas);

?>

<?php if($municipio->latitude && $municipio->longitude) : ?>
    <script>
        var map;
        
        var bairroShape;

        var bairroColor = '#32CD32';

        var defaultZoom = 13;
        var defaultLat = <?= $municipio->latitude; ?>;
        var defaultLong = <?= $municipio->longitude; ?>;

        <?php if (is_object($bairro) && $bairro->coordenadasJson) : ?>
        
            var bairroBounds = [<?= GoogleMapsAPIHelper::jsonToBounds($bairro->coordenadasJson); ?>];
            var bairroBoundsObj = new google.maps.LatLngBounds();
            
            for (i = 0; i < bairroBounds.length; i++)
                bairroBoundsObj.extend(bairroBounds[i]);

            var BairroCenter = bairroBoundsObj.getCenter();
            
            bairroShape = new google.maps.Polygon({
                paths: bairroBounds,
                strokeWeight: 0,
                fillColor: bairroColor,
                fillOpacity: 0.2
            });

            defaultLat = BairroCenter.k;
            defaultLong = BairroCenter.A;
            defaultZoom = 14;
        <?php endif; ?>
            
        var options = {
            zoom: defaultZoom,
            center: new google.maps.LatLng(defaultLat, defaultLong),
            mapTypeId: google.maps.MapTypeId.HYBRID,
            disableDefaultUI: true,
            zoomControl: true
        };
            
        map = new google.maps.Map(document.getElementById('map'), options);   
        
        <?php if (is_object($bairro) && $bairro->coordenadasJson) : ?>
            bairroShape.setMap(map);
        <?php endif; ?>
            
        <?php 
        $qtdeQuarteiroes = count($model->quarteiroesComCasosAtivos);
        if ($qtdeQuarteiroes > 0) : ?>
                var quarteiraoColor = '#000000';
            <?php 
            $i = 0;
            foreach($model->quarteiroesComCasosAtivos as $quarteiraoCoordenada) : ?>

                var quarteiraoPolygon<?= $i; ?> = new google.maps.Polygon({
                    paths: [<?= GoogleMapsAPIHelper::arrayToBounds($quarteiraoCoordenada); ?>],
                    strokeWeight: 0,
                    fillColor: quarteiraoColor,
                    fillOpacity: 0.45,
                    map: map
                });

                var quarteiraoBounds<?= $i; ?> = [<?= GoogleMapsAPIHelper::arrayToBounds($quarteiraoCoordenada); ?>];
                var quarteiraoBoundsObj<?= $i; ?> = new google.maps.LatLngBounds();

                for (i = 0; i < quarteiraoBounds<?= $i; ?>.length; i++)
                    quarteiraoBoundsObj<?= $i; ?>.extend(quarteiraoBounds<?= $i; ?>[i]);

                var options<?= $i; ?> = {
                    strokeColor: '#000000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#000000',
                    fillOpacity: 0.35,
                    map: map,
                    center: quarteiraoBoundsObj<?= $i; ?>.getCenter(),
                    radius: 300
                };

                var circle<?= $i; ?> = new google.maps.Circle(options<?= $i; ?>);

            <?php endforeach; ?>
        <?php endif; ?>
    </script>
<?php endif; ?>
   