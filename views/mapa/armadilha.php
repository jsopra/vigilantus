<?php
use yii\helpers\Html;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use yii\web\JsExpression;
use Yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'Armadilhas';
$this->params['breadcrumbs'][] = $this->title;

MapBoxAPIHelper::registerScript($this, ['fullScreen', 'omnivore']);
?>

<h1 id="stepguide-title"><?= Html::encode($this->title) ?></h1>

<div id="map"  style="height: 450px; width: 100%;"></div>

<?php
$municipio = \Yii::$app->user->identity->cliente->municipio;
$municipio->loadCoordenadas();
?>

<?php if($municipio->latitude && $municipio->longitude) : ?>
    <?php
    $javascript = "
        var layers = document.getElementById('menu-ui');
        var line_points = " . Json::encode([]) . ";
        var polyline_options = {
            color: '#000'
        };

        L.mapbox.accessToken = 'pk.eyJ1IjoidmlnaWxhbnR1cyIsImEiOiJXVEZJM1RFIn0.PWHuvfBY6oegZu3R65tWGA';
        var map = L.mapbox
            .map('map', 'vigilantus.kjkb4j0a')
            .setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 12)
            .on('ready', function() {
            });

        L.control.fullscreen().addTo(map);

        L.featureGroup().addTo(map);

        L.control.scale().addTo(map);

        var armadilhasLayer = omnivore.kml('" . Url::to(['kml/armadilha']) . "')
        .on('ready', function() {
            this.eachLayer(function(marker) {

                marker.setIcon(L.mapbox.marker.icon({
                    'marker-color': '#fc6a6a',
                    'marker-size': 'small',
                    'marker-symbol': 'chemist'
                }));

            });
        })
        .addTo(map);
    ";

    $this->registerJs($javascript);
    ?>
<?php endif; ?>

<?php
if(isset($_GET['step'])) {
    $view = Yii::$app->getView();
    $script = '
        $(document).ready(function() {

            var intro = introJs();
            intro.setOption("skipLabel", "Sair");
            intro.setOption("doneLabel", "Fechar");
            intro.setOption("nextLabel", "Próximo");
            intro.setOption("prevLabel", "Anterior");
            intro.setOption("tooltipPosition", "auto");
            intro.setOption("positionPrecedence", ["left", "right", "bottom", "top"]);

            intro.setOptions({
                steps: [
                    {
                        element: "#stepguide-title",
                        intro: "Este é mapa de armadilhas. Ele lista todas as armadilhas cadastradas"
                    },
                    {
                        element: "#stepguide-mapa-visao-geral",
                        intro: "Você também verá armadilhas no mapa de visão geral, que mostra as principais informações geolocalizadas do sistema"
                    },
                ],
            });

            intro.start();
        })
    ';
    $view->registerJs($script);
}
