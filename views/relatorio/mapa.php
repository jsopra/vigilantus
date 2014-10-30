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
        function initialize() {
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

            var ctaLayer = new google.maps.KmlLayer({
                url: 'http://vigilantus/relatorio/focos-kml'
            });
            ctaLayer.setMap(map);
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
<?php endif; ?>
   