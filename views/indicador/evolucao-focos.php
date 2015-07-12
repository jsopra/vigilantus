<?php
use app\models\Bairro;
use app\models\EspecieTransmissor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Evolução de Focos por Mês';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_menuIndicadoresFocos', []); ?>

<br />

<div class="mapa-area-tratamento-index" data-role="modal-grid">

    <h2><?= Html::encode($this->title) ?></h2>

    <br />

    <div class="form well">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>

            <div class="row">
                <div class="col-xs-3">
                    <?= $form->field($model, 'especie_transmissor_id')->dropDownList(EspecieTransmissor::listData('nome'), ['prompt' => 'Todas']) ?>
                </div>

                <div class="col-xs-2" style="padding-top: 20px;">
                    <?= Html::submitButton('Atualizar', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    </div>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable(<?= json_encode($data); ?>);

        var options = {
          title: '',
          vAxis: {title: 'Número de Focos',  titleTextStyle: {color: 'red'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
    </script>

    <div id="chart_div" style="width: 900px; height: 500px;"></div>

</div>
