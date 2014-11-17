<?php
use yii\helpers\Html;
use app\models\Bairro;
use app\models\Municipio;
use app\models\EspecieTransmissor;
use app\helpers\GoogleMapsAPIHelper;
use yii\helpers\Url;
?>

<br />
        
<?php
$municipio = $model->municipio;
$municipio->loadCoordenadas();
?>

<?php 
if($model->bairroQuarteirao) : 

    $model->bairroQuarteirao->loadCoordenadas();
    $centroQuarteirao = $model->bairroQuarteirao->getCentro();
?>

    <script src="<?= GoogleMapsAPIHelper::getAPIUrl(); ?>"></script>

    <div id="map" style="height: 500px; width: 100%;"></div>

    <script>

        var map;
            
        var defaultZoom = 16;
        var defaultLat = <?= $centroQuarteirao[0]; ?>;
        var defaultLong = <?= $centroQuarteirao[1]; ?>;

        var options = {
            zoom: defaultZoom,
            center: new google.maps.LatLng(defaultLat, defaultLong),
            mapTypeId: google.maps.MapTypeId.HYBRID,
            disableDefaultUI: false,
            zoomControl: true
        };

        function initialize() {
             
            map = new google.maps.Map(document.getElementById('map'), options);   

            var quarteiraoPolygon = new google.maps.Polygon({
                paths: [<?= GoogleMapsAPIHelper::arrayToBounds($model->bairroQuarteirao->coordenadas); ?>],
                strokeWeight: 0,
                fillColor: '#FF0000',
                fillOpacity: 0.6,
                map: map
            });
        }
    </script>

    <?php
        $view = Yii::$app->getView();
        $script = '
            $("a[href=#w1-tab3]").on("click", function(){
                initialize();
            });
        ';
        $view->registerJs($script);
    ?>

<?php else : ?>

    <p><strong>Quarteirão não definido!</strong></p>

<?php endif; ?>