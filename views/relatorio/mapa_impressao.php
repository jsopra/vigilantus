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
?>

<?php $this->beginBody() ?>

<?php MapBoxAPIHelper::registerScript($this, ['fullScreen', 'omnivore']); ?>

<style>
.menu-ui {
  background:#fff;
  position:absolute;
  top:10px;right:10px;
  z-index:1;
  border-radius:3px;
  width:120px;
  border:1px solid rgba(0,0,0,0.4);
  }
  .menu-ui a {
    font-size:13px;
    color:#404040;
    display:block;
    margin:0;padding:0;
    padding:5px 10px;
    text-decoration:none;
    border-bottom:1px solid rgba(0,0,0,0.25);
    text-align:center;
    }
    .menu-ui a:first-child {
      border-radius:3px 3px 0 0;
      }
    .menu-ui a:last-child {
      border:none;
      border-radius:0 0 3px 3px;
      }
    .menu-ui a:hover {
      background:#f8f8f8;
      color:#404040;
      }
    .menu-ui a.active {
      background:#3887BE;
      color:#FFF;
      }
      .menu-ui a.active:hover {
        background:#3074a4;
        }
</style>

<div id="map" style="height: 600px; width: 100%;">
    <nav class='menu-ui no-print'>
      <a href='#' id='print' class='active'>Imprimir</a>
    </nav>
</div>

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

        map.getContainer().querySelector('#print').onclick = function() {
            window.print();
            return false;
        };
    ";

    $this->registerJs($javascript);
    ?>

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
