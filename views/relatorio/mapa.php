<?php
use app\models\Bairro;
use app\models\Municipio;
use app\models\EspecieTransmissor;
use app\helpers\GoogleMapsAPIHelper;
?>

<?php echo $this->render('_filtroRelatorioAreaTratamento', ['model' => $model]); ?>

<?php echo $this->render('_menuRelatorioAreaTratamento', []); ?>

<br />

<script src="<?= GoogleMapsAPIHelper::getAPIUrl(); ?>"></script>

<div id="map"  style="height: 500px; width: 100%;"></div>
        
<?php
$municipio = Municipio::find()->one();
$municipio->loadCoordenadas();
?>

<?php if($municipio->latitude && $municipio->longitude) : ?>
    <script>
        var map;
        
        var defaultZoom = 13;
        var defaultLat = <?= $municipio->latitude; ?>;
        var defaultLong = <?= $municipio->longitude; ?>;
            
        var options = {
            zoom: defaultZoom,
            center: new google.maps.LatLng(defaultLat, defaultLong),
            mapTypeId: google.maps.MapTypeId.HYBRID,
            disableDefaultUI: true,
            zoomControl: true
        };
            
        map = new google.maps.Map(document.getElementById('map'), options);   
            
        <?php 
        $modelFocos = $model->dataProviderAreasFoco->getModels();

        $qtdeQuarteiroes = count($modelFocos);
        if ($qtdeQuarteiroes > 0) : ?>
                var quarteiraoColor = '#000000';
            <?php 
            $i = 0;
            foreach($modelFocos as $foco) : ?>

                <?php
                $quarteirao = $foco->bairroQuarteirao; 
                $quarteirao->loadCoordenadas();
                if(!$quarteirao->coordenadas)
                    continue;
                
                $quarteiraoCoordenada = $quarteirao->coordenadas;
                
                $corFoco = $foco->especieTransmissor->cor;
                ?>

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
                    strokeColor: '<?= $corFoco; ?>',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '<?= $corFoco; ?>',
                    fillOpacity: 0.35,
                    map: map,
                    center: quarteiraoBoundsObj<?= $i; ?>.getCenter(),
                    radius: <?= $foco->especieTransmissor->qtde_metros_area_foco; ?>
                };

                var circle<?= $i; ?> = new google.maps.Circle(options<?= $i; ?>);
                
                <?php $i++; ?>
                    
            <?php endforeach; ?>
        <?php endif; ?>
    </script>
<?php endif; ?>
   