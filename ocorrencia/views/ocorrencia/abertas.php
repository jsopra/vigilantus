<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use app\models\Bairro;
use app\models\Setor;
use app\models\OcorrenciaStatus;
use app\models\OcorrenciaTipoProblema;
use app\helpers\models\OcorrenciaHelper;
use app\models\Configuracao;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Ocorrências Abertas';
$this->params['breadcrumbs'][] = $this->title;

$diasVerde = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_OCORRENCIA_VERDE, \Yii::$app->user->identity->cliente->id);
$diasVemelho = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_OCORRENCIA_VERMELHO, \Yii::$app->user->identity->cliente->id);
?>

<script>
function verAveriguacoes(id)
{
    jQuery('#ocorrencias-visitas').children('.modal-dialog').children('.modal-content').children('.modal-body').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Carregando...');

    jQuery('#ocorrencias-visitas').modal('show');

    jQuery.get('<?= Url::toRoute(['ocorrencia/ver-averiguacoes', 'id' => '']); ?>' + id, function(data) {
        jQuery('#ocorrencias-visitas').children('.modal-dialog').children('.modal-content').children('.modal-body').html(data);
    });
}
</script>

<div class="ocorrencia-index">

    <h1 id="stepguide-title"><?= Html::encode($this->title) ?></h1>

    <div class="form well">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>

            <div class="row" id="dadosPrincipais">

                <div class="col-xs-2">
                    <?= $form->field($searchModel, 'bairro_id')->dropDownList(Bairro::listData('nome'), ['prompt' => 'Todos']) ?>
                </div>

                <div class="col-xs-2">
                    <?= $form->field($searchModel, 'endereco') ?>
                </div>

                <div class="col-xs-2">
                    <?= $form->field($searchModel, 'hash_acesso_publico') ?>
                </div>

                <div class="col-xs-2">
                    <?= $form->field($searchModel, 'ano')->input('number') ?>
                </div>

                <div class="col-xs-2">
                    <?= $form->field($searchModel, 'status')->dropDownList(OcorrenciaStatus::getDescricoes(), ['prompt' => 'Todas']) ?>
                </div>

                <div class="col-xs-3">
                    <?= $form->field($searchModel, 'ocorrencia_tipo_problema_id')->dropDownList(OcorrenciaTipoProblema::listData('nome'), ['prompt' => 'Todas']) ?>
                </div>

                <div class="col-xs-2">
                    <?= $form->field($searchModel, 'qtde_dias_aberto')->dropDownList([
                     1 => 'Até ' . $diasVerde . ' dias',
                     2 => 'Entre ' . $diasVerde . ' e ' . $diasVemelho . ' dias',
                     3 => 'Mais de ' . $diasVemelho . ' dias',
                 ], ['prompt' => 'Todas']) ?>
                </div>

                <div class="col-xs-2">
                    <?= $form->field($searchModel, 'setor_id')->dropDownList(ArrayHelper::map(Setor::find()->doUsuario(Yii::$app->user->identity)->all(), 'id', 'nome'), ['prompt' => 'Todas']) ?>
                </div>

                <div class="col-xs-1" style="padding-top: 20px;">
                    <?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    </div>

    <?= Html::a(
        'Cadastrar Ocorrência',
        Yii::$app->urlManager->createUrl('ocorrencia/ocorrencia/create'),
        [
            'class' => 'btn btn-flat success',
            'data-role' => 'create',
            'id' => 'stepguide-create-ocorrencia',
        ]);
    ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_ocorrencias-abertas',
    ]); ?>

</div>

<div class="modal fade" id="ocorrencias-visitas" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Averiguações</h4>
            </div>

            <div class="modal-body"></div>

            <div class="modal-footer">
                <div class="pull-left">
                    <p class="modal-feedback-message"></p>
                </div>
                <div class="pull-right">
                    <?= Html::submitButton('Fechar', ['class' => 'btn btn-flat success', 'data-dismiss' => 'modal']); ?>
                </div>
            </div>
        </div>
    </div>
</div>

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
                        intro: "Este é o painel de gestão de Ocorrências abertas. Todas as ocorrências abertas são listadas aqui, facilitando a sua gestão."
                    },
                    {
                        element: "#dadosPrincipais",
                        intro: "Você pode aplicar qualquer filtro nas ocorrências abertas, e vê-las abaixo listadas"
                    },
                ],
            });

            intro.start();
        })
    ';
    $view->registerJs($script);
}
