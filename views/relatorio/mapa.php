<?php
use app\models\Bairro;
use app\models\Municipio;
use app\models\EspecieTransmissor;
use app\helpers\GoogleMapsAPIHelper;
use app\models\redis\FocosAtivos;

$this->title = 'Áreas de Tratamento';
$this->params['breadcrumbs'][] = $this->title;
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
        $qtdeQuarteiroes = count($modelFocos);
        if ($qtdeQuarteiroes > 0) : ?>
                var quarteiraoColor = '#000000';
            <?php 
            $i = 0;
            foreach($modelFocos as $foco) : 
 
                $quarteiraoCoordenada = $foco->getQuarteiraoCoordenadas();
                
                $corFoco = $foco->cor_foco;
                
                $centroQuarteirao = $foco->getCentroQuarteirao();
                ?>

                var quarteiraoPolygon<?= $i; ?> = new google.maps.Polygon({
                    paths: [<?= GoogleMapsAPIHelper::arrayToBounds($quarteiraoCoordenada); ?>],
                    strokeWeight: 0,
                    fillColor: quarteiraoColor,
                    fillOpacity: 0.45,
                    map: map
                });

                var options<?= $i; ?> = {
                    strokeColor: '<?= $corFoco; ?>',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '<?= $corFoco; ?>',
                    fillOpacity: 0.35,
                    map: map,
                    center: new google.maps.LatLng(<?= $centroQuarteirao[0]; ?>, <?= $centroQuarteirao[1]; ?>),
                    radius: <?= $foco->qtde_metros_area_foco; ?>
                };

                var circle<?= $i; ?> = new google.maps.Circle(options<?= $i; ?>);
                
                <?php $i++; ?>
                    
            <?php endforeach; ?>
        <?php endif; ?>
    </script>
<?php endif; ?>
   