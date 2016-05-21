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
                    A melhor experiência de prevenção em saúde para sua cidade
                </h1>
                <p class="lead text-xs-center balance-text">
                Ferramenta para controle sustentável de Dengue, Zika,
                Chikunguya e outras Zoonoses
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
    <p>Preencha seu endereço de e-mail e solicite uma demonstração do software:</p>
    <form class="form-inline" accept-charset="UTF-8" action="//formspree.io/tenha@perspectiva.in" method="POST" target="_blank">
        <div class="form-group">
            <input type="email" class="form-control" placeholder="Seu e-mail" name="email" style="width: 190px">
            <button class="btn btn-primary">Solicitar</button>
        </div>
    </form>
</section>

<section class="feature conformidade-pncd">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <img src="<?= Url::base() ?>/img/funcionalidade-conformidade-pncd.jpg" alt="Ilustração" class="img-thumbnail">
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
                <img src="<?= Url::base() ?>/img/funcionalidade-georreferenciamento.jpg" alt="Ilustração" class="img-thumbnail">
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
                    Consulte facilmente as áreas de risco da cidade e os locais
                    que possuem casos confirmados das doenças.
                <p>
            </div>
        </div>
    </div>
</section>

<section class="feature ocorrencias-denuncias">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <img src="<?= Url::base() ?>/img/funcionalidade-ocorrencias-denuncias.jpg" alt="Ilustração" class="img-thumbnail">
            </div>
            <div class="col-sm-7">
                <h1 class="balance-text">Ocorrências e denúncias</h1>
                <p class="balance-text">
                    Um portal público completo para que <strong>o cidadão possa
                    registrar ocorrências</strong> para a Secretaria de Saúde.
                <p>
                <p class="balance-text">
                    Acompanhe o tempo de resposta da sua equipe,
                    enquanto o cidadão recebe um número de protocolo para
                    acompanhar o andamento do atendimento da sua ocorrência.
                <p>
            </div>
        </div>
    </div>
</section>

<section class="feature feature-last ocorrencias-denuncias">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <img src="<?= Url::base() ?>/img/funcionalidade-analise.jpg" alt="Ilustração" class="img-thumbnail">
            </div>
            <div class="col-sm-7">
                <h1 class="balance-text">Inteligência em Prevenção</h1>
                <p class="balance-text">
                    Análises via mapa que orientam ações coletivas.
                <p>
                <p class="balance-text">
                    <strong>Avalie</strong> indicadores através de mapas, <strong>entenda</strong> as lacunas no seu
                    processo preventivo e <strong>execute ações orientadas</strong> à necessidades de cada local.
                <p>
                <p class="balance-text">
                    <strong>Faça a mudança acontecer!</strong>
                </p>
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
                    <li><a href="http://g1.globo.com/sc/santa-catarina/jornal-do-almoco/videos/t/chapeco/v/prefeitura-de-chapeco-lanca-ferramenta-online-para-denuncias-de-cunho-ambiental/4965298/">Prefeitura de Chapecó lança ferramenta online para denúncias de cunho ambiental</a></li>
                    <li><a href="http://www.radiopiratuba.com.br/noticias/noticia/id:1495;software-de-combate-a-dengue-desenvolvido-em-sc-e-apresentado-no-ministerio-da-saude.html" target="_blank">Software de combate a dengue desenvolvido em SC é apresentado no Ministério da Saúde</a></li>
                    <li><a href="https://www.youtube.com/watch?v=ab0c_LQCb2w" target="_blank">Aplicativo "Vigilantus" desenvolvido em Chapecó ajuda no combate à dengue</a></li>
                    <li><a href="http://agenciaal.alesc.sc.gov.br/index.php/noticia_single/estudantes-de-chapeco-desenvolvem-aplicativo-voltado-ao-combate-da-dengue" target="_blank">Estudantes de Chapecó criam software voltado ao combate da dengue</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
