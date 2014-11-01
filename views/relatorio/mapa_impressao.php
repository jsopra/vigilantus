<?php
use yii\helpers\Html;
use app\models\Bairro;
use app\models\Municipio;
use app\models\EspecieTransmissor;
use app\helpers\GoogleMapsAPIHelper;
use app\models\redis\FocosAtivos;
?>

<?php $this->beginBody() ?>

<script src="<?= GoogleMapsAPIHelper::getAPIUrl(); ?>"></script>

<div id="map" style="height: 600px; width: 100%;"></div>
        
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
            disableDefaultUI: false,
            zoomControl: true
        };
            
        map = new google.maps.Map(document.getElementById('map'), options);   

        var homeControlDiv = document.createElement('div');
        var homeControl = new PrintControl(homeControlDiv, map);
        homeControlDiv.index = 1;
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);

        google.maps.event.addListenerOnce(map, 'idle', function(){
            google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
                setTimeout(function() { window.print(); }, 2000);
            });
        });

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

        function PrintControl(controlDiv, map) {

            controlDiv.style.padding = '5px';

            var controlUI = document.createElement('div');
            controlUI.style.backgroundColor = 'white';
            controlUI.style.borderStyle = 'solid';
            controlUI.style.borderWidth = '1px';
            controlUI.style.cursor = 'pointer';
            controlUI.style.textAlign = 'center';
            controlUI.title = 'Imprimir mapa';
            controlUI.style.paddingTop = '3px';
            controlUI.style.paddingBottom = '3px';
            controlUI.className = 'no-print';
            controlDiv.appendChild(controlUI);

            var controlText = document.createElement('div');
            controlText.style.fontFamily = 'Arial,sans-serif';
            controlText.style.fontSize = '11px';
            controlText.style.paddingLeft = '3px';
            controlText.style.paddingRight = '3px';
            controlText.innerHTML = '<b>Imprimir</b>';
            controlUI.appendChild(controlText);

            google.maps.event.addDomListener(controlUI, 'click', function() {
                window.print();
            });
        }
    </script>
<?php endif; ?>

<style>
@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
</style>

<?php
if (YII_ENV_PROD) {
    echo VigilantusLayoutHelper::getAnalyticsCode();
}
?>