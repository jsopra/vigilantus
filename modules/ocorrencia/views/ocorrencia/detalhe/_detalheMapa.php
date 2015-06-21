<?php
use yii\helpers\Html;
use app\models\Bairro;
use app\models\Municipio;
use app\models\Cliente;
use app\models\EspecieTransmissor;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use yii\helpers\Url;

MapBoxAPIHelper::registerScript($this, ['fullScreen']);
?>

<br />

<?php
$municipio = $model->cliente->municipio;
$municipio->loadCoordenadas();
?>

<?php
if($model->bairroQuarteirao) :

    $model->bairroQuarteirao->loadCoordenadas();
    $centroQuarteirao = $model->bairroQuarteirao->getCentro();
?>

    <div id="map" style="height: 300px; width: 100%;"></div>

<script>
    function initialize() {
        var line_points = <?= MapHelper::getArrayCoordenadas($model->bairroQuarteirao->coordenadas); ?>;
        var polyline_options = {
            color: '#000'
        };

        var polyline_related_options = {
            color: '#797979'
        }

        var quarteiraoPoligono = L.polygon(line_points, polyline_options);
        var quarteiraoCenter = quarteiraoPoligono.getBounds().getCenter();

        L.mapbox.accessToken = 'pk.eyJ1IjoidmlnaWxhbnR1cyIsImEiOiJXVEZJM1RFIn0.PWHuvfBY6oegZu3R65tWGA';
        var map = L.mapbox
            .map('map', 'vigilantus.kjkb4j0a')
            .setView(quarteiraoCenter, 15);

        L.control.fullscreen().addTo(map);
        L.featureGroup().addTo(map);

        L.mapbox.featureLayer({
            type: 'Feature',
            geometry: {
                type: 'Point',
                coordinates: [quarteiraoCenter.lng, quarteiraoCenter.lat]
            },
            properties: {
                title: '<?= $model->bairroQuarteirao->numero_quarteirao; ?>',
                'marker-color': '#fc6a6a',
                'marker-symbol': 'hospital'
            }
        }).addTo(map);

        L.Util.requestAnimFrame(map.invalidateSize,map,!1,map._container);
    }
</script>

<?php
    $view = Yii::$app->getView();
    $script = '
        $("a[href=#w1-tab2]").on("click", function(){
            initialize();
        });
    ';
    $view->registerJs($script);
    ?>

<?php else : ?>

    <p><strong>Quarteirão não definido!</strong></p>

<?php endif; ?>
