<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use app\models\Bairro;
use app\models\OcorrenciaStatus;
use app\models\OcorrenciaTipoProblema;
use app\helpers\models\OcorrenciaHelper;
use app\models\Configuracao;
use yii\helpers\Url;

$this->title = 'Ocorrências Abertas';
$this->params['breadcrumbs'][] = $this->title;

$diasVerde = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_OCORRENCIA_VERDE, \Yii::$app->user->identity->cliente->id);
$diasVemelho = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_OCORRENCIA_VERMELHO, \Yii::$app->user->identity->cliente->id);
?>
<div class="ocorrencia-index">

    <h1 id="stepguide-title"><?= Html::encode($this->title) ?></h1>

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
                        intro: "Este é o painel de gestão de Ocorrências. Uma ocorrência pode ser cadastrada por você, ou mesmo recebida através da página pública ou das redes sociais"
                    },
                    {
                        element: "#stepguide-create-ocorrencia",
                        intro: "Você pode transcrever uma ocorrência recebida por uma forma não automatizada"
                    },
                    {
                        element: "#stepguide-create-carga-ocorrencias",
                        intro: "Ou também fazer uma carga de ocorrências no sistema"
                    },
                    {
                        element: "thead",
                        intro: "Todas as ocorrências ficarão listadas abaixo. Você poderá filtrar por status, tipo de problema, bairro, ou acompanhar indicadores de atendimento da ocorrência. A última coluna traz alguns ícones que darão continuidade à uma ocorrência: ver detalhes, baixar anexo, aprovar, reprovar e informar alteração de status"
                    },
                ],
            });

            intro.start();
        })
    ';
    $view->registerJs($script);
}
