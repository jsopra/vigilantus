<?php
use yii\helpers\Html;
use app\models\Bairro;
use app\models\Municipio;
use app\models\Cliente;
use app\models\EspecieTransmissor;
use app\helpers\GoogleMapsAPIHelper;
use app\models\redis\FocosAtivos;
use yii\helpers\Url;

$this->title = 'Áreas de Tratamento';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_filtroRelatorioAreaTratamento', ['model' => $model]); ?>

<?php echo $this->render('_menuRelatorioAreaTratamento', []); ?>

<br />

<?= Html::submitButton('Imprimir', ['class' => 'btn btn-default', 'onclick' => 'gmapPrint();']) ?>

<br /><br />

<script src="<?= GoogleMapsAPIHelper::getAPIUrl(); ?>"></script>

<div id="map" style="height: 500px; width: 100%;"></div>

<?php
$municipio = \Yii::$app->session->get('user.cliente')->municipio;
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
            /*
            var ctaLayer = new google.maps.KmlLayer({
                url: 'http://vigilantus/relatorio/focos-kml'
            });
            ctaLayer.setMap(map);
            */
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
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
