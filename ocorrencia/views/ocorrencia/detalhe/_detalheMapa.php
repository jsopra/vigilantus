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
if ($model->bairroQuarteirao || ($model->latitude !== null && $model->longitude !== null)) :

?>
    <div id="map" style="height: 300px; width: 100%;"></div>

<script>
    function initialize()
    {
        var polyline_options = { color: '#000' };
        var polyline_related_options = { color: '#797979' }

        <?php
        if ($model->bairroQuarteirao) :
            $model->bairroQuarteirao->loadCoordenadas();
            ?>
            var line_points = <?= MapHelper::getArrayCoordenadas($model->bairroQuarteirao->coordenadas); ?>;
            var quarteiraoPoligono = L.polygon(line_points, polyline_options);
            var coordenadas = quarteiraoPoligono.getBounds().getCenter();
            var title = '<?= $model->bairroQuarteirao->numero_quarteirao; ?>';
            <?php
        elseif ($model->latitude !== null && $model->longitude !== null) :
            ?>
            var coordenadas = {
                lng: <?= $model->longitude ?>,
                lat: <?= $model->latitude ?>
            };
            var title = 'Posição informada pela pessoa que registrou a ocorrência.';
            <?php
        endif;
        ?>
        L.mapbox.accessToken = '<?= Yii::$app->params['mapBoxAccessToken'] ?>';
        var map = L.mapbox
            .map('map', '<?= Yii::$app->params['mapBoxMapID'] ?>')
            .setView(coordenadas, 17);

        L.control.fullscreen().addTo(map);
        L.featureGroup().addTo(map);

        L.mapbox.featureLayer({
            type: 'Feature',
            geometry: {
                type: 'Point',
                coordinates: [coordenadas.lng, coordenadas.lat]
            },
            properties: {
                title: title,
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
    $(\'a[href="#aba-mapa"]\').on("click", function() {
        initialize();
    });
';
$view->registerJs($script);
?>

<?php else : ?>
    <div class="alert alert-warning">
        <i class="icon-warning-sign"></i>
        Nenhum quarteirão foi definido para esta ocorrência, e a pessoa que
        registrou a ocorrência também não apontou no mapa o lugar onde aconteceu
        o problema.
    </div>
<?php endif; ?>
