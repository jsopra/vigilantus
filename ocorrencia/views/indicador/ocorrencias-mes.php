<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\OcorrenciaTipoProblema;
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
HighchartsAsset::register($this)->withScripts(['highstock', 'modules/exporting', 'modules/drilldown']);

$this->title = 'Evolução de ocorrências por mês';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_menuIndicadoresOcorrencias', []); ?>

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
                    <?= $form->field($model, 'ano')->input('number') ?>
                </div>

                <div class="col-xs-3">
                    <?= $form->field($model, 'problema_id')->dropDownList(OcorrenciaTipoProblema::listData('nome'), ['prompt' => 'Todos']) ?>
                </div>

                <div class="col-xs-2" style="padding-top: 20px;">
                    <?= Html::submitButton('Atualizar', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    </div>

  <?php
  $tipos = $model->getMeses(true);
  $series = $model->getSeries();
  ?>

  <?= Highcharts::widget([
     'options' => [
          'chart' => ['type' => 'column'],
          'title' => ['text' => ''],
          'xAxis' => [
              'categories' => $tipos,
          ],
          'yAxis' => [
              'min' => 0,
              'title' => ['text' => 'Qtde de Ocorrências'],
          ],
          'plotOptions' => [
              'column' => ['stacking' => 'number'],
          ],
          'series' => [
              [
                  'name' => 'Recebidas',
                  'type' => 'column',
                  'data' => $series['recebidas'],

              ],
              [
                  'name' => 'Finalizadas',
                  'type' => 'spline',
                  'data' => $series['finalizadas'],
              ]
          ],
     ]
  ]);
  ?>
</div>
