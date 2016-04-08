<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 */
$this->title = Yii::$app->name;
?>
<section class="jumbotron">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="display-3 text-xs-center balance-text">
                    Software para Gestão da Vigilância em Saúde
                </h1>
                <p class="lead text-xs-center balance-text">
                Apoio e gestão de prevenção da Dengue, Zika vírus,
                Chikunguya e outras zoonoses.
                </p>
            </div>
            <div class="col-md-4 hidden-sm-down">
                <img src="<?= Url::base() ?>/img/selo-mosquito.png" alt="Mosquito">
            </div>
        </div>
    </div>
</section>

<section class="request-demo">
    <h1>Solicite uma demonstração gratuita</h1>
    <p>Preencha seu endereço de e-mail e solicite uma demonstração gratuita do software:</p>
    <form class="form-inline" accept-charset="UTF-8" action="//formspree.io/tenha@perspectiva.in" method="POST" target="_blank">
        <div class="form-group">
            <input type="email" class="form-control" placeholder="Seu e-mail" name="email">
        </div>
        <button class="btn btn-primary">Solicitar</button>
    </form>
</section>

<section class="feature conformidade-pncd">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <img src="<?= Url::base() ?>/img/funcionalidade-conformidade-pncd.jpg" alt="Ilustração">
            </div>
            <div class="col-sm-7">
                <h1 class="balance-text">Conformidade com o PNCD</h1>
                <p class="balance-text">
                    O Vigilantus gera relatórios e boletins de reconhecimento
                    geográfico em <strong>conformidade com o padrão do PNCD</strong>
                    (Plano Nacional de Combate à Dengue).
                <p>
                <p class="balance-text">
                    Além dos relatórios para o governo, o Vigilantus conta
                    com diversas outras formas de acompanhamento, como
                    gráficos, mapas, planilhas e indicadores.
                <p>
            </div>
        </div>
    </div>
</section>

<section class="feature georreferenciamento">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <img src="<?= Url::base() ?>/img/funcionalidade-georreferenciamento.jpg" alt="Ilustração">
            </div>
            <div class="col-sm-7">
                <h1 class="balance-text">Georreferenciamento</h1>
                <p class="balance-text">
                    <strong>Chega de gastar papel</strong> imprimindo mapas
                    da cidade! Com o Vigilantus você registra os pontos de foco
                    do mosquito e o sistema automaticamente calcula a área de
                    infestação.
                <p>
                <p class="balance-text">
                    É possível consultar pontos de focos e pontos de casos
                    confirmados, aplicando diversos filtros para ter a informação
                    exata.
                <p>
            </div>
        </div>
    </div>
</section>

<section class="feature ocorrencias-denuncias">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <img src="<?= Url::base() ?>/img/funcionalidade-ocorrencias-denuncias.jpg" alt="Ilustração">
            </div>
            <div class="col-sm-7">
                <h1 class="balance-text">Ocorrências e denúncias</h1>
                <p class="balance-text">
                    Um portal público completo para que <strong>o cidadão possa
                    registrar ocorrências</strong> que envolvem a Vigilância em
                    Saúde.
                <p>
                <p class="balance-text">
                    O gestor poderá acompanhar o tempo de resposta de sua equipe,
                    enquanto o cidadão receberá um número de protocolo e poderá
                    acompanhar o andamento do atendimento da ocorrência.
                <p>
            </div>
        </div>
    </div>
</section>

<section class="news">
    <div class="container">
        <h1 class="text-xs-center">Notícias</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="js-video">
                    <iframe width="560" height="315" src="http://www.youtube.com/embed/ab0c_LQCb2w?showinfo=1" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            <div class="col-md-6">
                <ul>
                    <li><a href="http://www.radiopiratuba.com.br/noticias/noticia/id:1495;software-de-combate-a-dengue-desenvolvido-em-sc-e-apresentado-no-ministerio-da-saude.html" target="_blank">Software de combate a dengue desenvolvido em SC é apresentado no Ministério da Saúde</a></li>
                    <li><a href="https://www.youtube.com/watch?v=ab0c_LQCb2w" target="_blank">Aplicativo "Vigilantus" desenvolvido em Chapecó ajuda no combate à dengue</a></li>
                    <li><a href="http://agenciaal.alesc.sc.gov.br/index.php/noticia_single/estudantes-de-chapeco-desenvolvem-aplicativo-voltado-ao-combate-da-dengue" target="_blank">Estudantes de Chapecó criam software voltado ao combate da dengue</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
