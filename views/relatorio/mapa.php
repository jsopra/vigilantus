<?php
use yii\helpers\Html;
use app\models\Bairro;
use app\models\Municipio;
use app\models\Cliente;
use app\models\EspecieTransmissor;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use app\models\redis\FocosAtivos;
use yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'Áreas de Tratamento';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_filtroRelatorioAreaTratamento', ['model' => $model]); ?>

<?php echo $this->render('_menuRelatorioAreaTratamento', []); ?>

<br />

<?= Html::submitButton('Imprimir', ['class' => 'btn btn-default', 'onclick' => 'gmapPrint();']) ?>

<br /><br />

<?php MapBoxAPIHelper::registerScript($this, ['fullScreen', 'minimap', 'omnivore']); ?>

<div id="map" style="height: 450px; width: 100%;"></div>

<?php
$municipio = \Yii::$app->session->get('user.cliente')->municipio;
$municipio->loadCoordenadas();
?>

<?php if($municipio->latitude && $municipio->longitude) : ?>

    <?php
    $javascript = "
        var line_points = " . Json::encode([]) . ";
        var polyline_options = {
            color: '#000'
        };

        L.mapbox.accessToken = 'pk.eyJ1IjoidmlnaWxhbnR1cyIsImEiOiJXVEZJM1RFIn0.PWHuvfBY6oegZu3R65tWGA';
        var map = L.mapbox
            .map('map', 'vigilantus.kjkb4j0a')
            .setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 13)
            .on('ready', function() {
                new L.Control.MiniMap(L.mapbox.tileLayer('vigilantus.kjkb4j0a'))
                    .addTo(map);
            });

        L.control.fullscreen().addTo(map);

        L.featureGroup().addTo(map);

        L.control.scale().addTo(map);

        var runLayer = omnivore.kml('" . Url::to($url) . "')
        .on('ready', function() {
            this.eachLayer(function(marker) {

                marker.setIcon(L.mapbox.marker.icon({
                    'marker-color': '#fc6a6a',
                    'marker-size': 'small',
                    'marker-symbol': 'danger'
                }));

                marker.bindPopup(marker.feature.properties.numero_quarteirao);

                L.circle([marker.feature.geometry.coordinates[1], marker.feature.geometry.coordinates[0]], marker.feature.properties.metros_tratamento).addTo(map);
            });
        })
        .addTo(map);
    ";

    $this->registerJs($javascript);
    ?>
<?php endif; ?>

<script>

function gmapPrint() {
    var inseriuParametro = false;
    var url = '<?= Url::toRoute(array("relatorio/download-mapa")); ?>?';

    if($('#areatratamentoreport-bairro_id').val()) {
        inseriuParametro = true;
        url += 'bairro_id=' + $('#areatratamentoreport-bairro_id').val();
    }

    if($('#areatratamentoreport-lira').val()) {
        inseriuParametro = true;
        url += (inseriuParametro ? '&' : '?') + 'lira=' + $('#areatratamentoreport-lira').val();
    }

    if($('#areatratamentoreport-especie_transmissor_id').val()) {
        inseriuParametro = true;
        url += (inseriuParametro ? '&' : '?') + 'especie_transmissor_id=' + $('#areatratamentoreport-especie_transmissor_id').val();
    }

    window.open(url,'_blank');
}
</script>
