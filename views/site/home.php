<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = 'Resumo de Indicadores';
?>

<?= $this->render('_menuHome', ['municipio' => $cliente->municipio]); ?>

<?= $this->render('/resumo/_rg', ['model' => $modelRg, 'ultimaAtualizacao' => Yii::$app->cache->get('ultima_atualizacao_resumo_cache_rg')], true); ?>

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

            var stepsOptions = [
                {
                    element: "#stepguide-title",
                    intro: "Esta é a visão geral do software para seu Município"
                }
            ];

            if(isAnalista == "0" && isGerente == "1") {
                stepsOptions.push(
                    {
                        element: "#denuncias",
                        intro: "Você pode rapidamente avaliar problemas de atendimento em denúncias"
                    }
                );
            }

            stepsOptions.push(
                {
                    element: "#rg",
                    intro: "Como também ver um resumo do RG de seu Município...."
                }
            );

            stepsOptions.push(
                {
                    element: "#stepguide-indicador-rg-update",
                    intro: "... e se precisar de uma atualização, pode solicitar aqui"
                }
            );

            stepsOptions.push(
                {
                    element: "#focos",
                    intro: "Por fim você pode ver indicadores gerais de focos. Experimente!"
                }
            );

            intro.setOptions({steps: stepsOptions}).start();
        });
    ';
    $view->registerJs($script);
}
