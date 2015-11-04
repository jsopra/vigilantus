<?php
use yii\helpers\Html;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use yii\web\JsExpression;
use Yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'Pontos Estratégicos';
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

        L.mapbox.accessToken = '" . Yii::$app->params['mapBoxAccessToken'] . "';
        var map = L.mapbox
            .map('map', '" . Yii::$app->params['mapBoxMapID'] . "')
            .setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 12)
            .on('ready', function() {
            });

        L.control.fullscreen().addTo(map);

        L.featureGroup().addTo(map);

        L.control.scale().addTo(map);

        var armadilhasLayer = omnivore.kml('" . Url::to(['kml/ponto-estrategico']) . "')
        .on('ready', function() {
            this.eachLayer(function(marker) {

                marker.setIcon(L.mapbox.marker.icon({
                    'marker-color': '#BBBBDC',
                    'marker-size': 'small',
                    'marker-symbol': 'golf'
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
                        intro: "Este é mapa de Pontos estratégicos. Ele lista todos os PE\'s cadastrados"
                    },
                    {
                        element: "#stepguide-mapa-visao-geral",
                        intro: "Você também verá PE\'s no mapa de visão geral, que mostra as principais informações geolocalizadas do sistema"
                    },
                ],
            });

            intro.start();
        })
    ';
    $view->registerJs($script);
}
