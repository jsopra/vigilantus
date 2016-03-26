<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\themes\DetailwrapNav;
use app\components\themes\DetailwrapNavBar;
use app\components\themes\DetailwrapSideBar;
use app\helpers\VigilantusLayoutHelper;
use yii\widgets\Breadcrumbs;
use app\assets\WebsiteAsset;
use app\widgets\Alert;
use yii\web\View;

WebsiteAsset::register($this);

$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="theme-color" content="#6c3977">

        <title><?= Html::encode($this->title) ?></title>

        <!-- <meta name="description" content="A Perspectiva é uma empresa de desenvolvimento de softwares para computadores, dispostivos móveis e Internet.">
        <meta name="keywords" content="perspectiva, sistema, software, empresa, internet, computador, desenvolvimento, chapecó">

        <meta property="og:locale" content="pt_BR">
        <meta property="og:url" content="//vigilantus.com.br">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Perspectiva: tecnologia para viver melhor">
        <meta property="og:description" content="A Perspectiva é uma empresa de desenvolvimento de softwares para computadores, dispostivos móveis e Internet.">
        <meta property="og:image" content="//www.vigilantus.com.br/facebook-image.jpg">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630"> -->

        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <link rel="icon" href="<?= Url::base() ?>/favicon.ico">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css">

        <?php $this->head() ?>

        <?= Html::csrfMetaTags() ?>

        <script>
            // var socialHandlerUrl = '<?= Url::to(['/site/auth']); ?>';
            // var stepFocosUrl = '<?= Url::to(['/foco-transmissor', 'step' => true]); ?>';
            // var stepVisaoGeralUrl= '<?= Url::to(['/site/home', 'step' => true]); ?>';
            // var stepArmadilhasCadastroUrl = '<?= Url::to(['/armadilha', 'step' => true]); ?>';
            // var stepArmadilhasMapaUrl = '<?= Url::to(['/mapa/armadilha', 'step' => true]); ?>';
            // var stepPECadastroUrl = '<?= Url::to(['/ponto-estrategico', 'step' => true]); ?>';
            // var stepPEMapaUrl = '<?= Url::to(['/mapa/ponto-estrategico', 'step' => true]); ?>';
            // var stepGeolocalizacaoUrl = '<?= Url::to(['/bairro', 'step' => true]); ?>';
            // var stepRGUrl = '<?= Url::to(['/boletim-rg', 'step' => true]); ?>';
            // var stepOcorrenciasUrl = '<?= Url::to(['/ocorrencia/ocorrencia/index', 'step' => true]); ?>';
            // var stepOcorrenciasAbertasUrl = '<?= Url::to(['/ocorrencia/ocorrencia/abertas', 'step' => true]); ?>';
            //
            // var moduloOcorrenciaIsHabilitado = '<?= \Yii::$app->user->getIdentity() && \Yii::$app->user->getIdentity()->moduloIsHabilitado(\app\models\Modulo::MODULO_OCORRENCIA) ? '1' : '0'; ?>';
            //
            // var isAnalista = '<?= \Yii::$app->user->can("Analista") ? "1" : "0"; ?>';
            // var isGerente = '<?= \Yii::$app->user->can("Gerente") ? "1" : "0"; ?>';
        </script>
    </head>
    <body>
        <nav class="navbar navbar-light navbar-fixed-top bg-faded bg-white">
            <div class="container">
                <button class="navbar-toggler hidden-md-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar">
                    &#9776;
                </button>
                <a class="navbar-brand" href="<?= Url::base() ?>/" title="Perspectiva">
                    <img
                        src="<?= Url::base() ?>/img/website/logotipo-vigilantus-grande.png"
                        srcset="<?= Url::base() ?>/img/website/logotipo-vigilantus-grande.png 1x, <?= Url::base() ?>/img/website/logotipo-vigilantus-grande@2x.png 2x, <?= Url::base() ?>/img/website/logotipo-vigilantus-grande@3x.png 3x"
                        alt="Vigilantus"
                        width="258"
                        height="50">
                </a>
                <div class="collapse navbar-toggleable-sm" id="exCollapsingNavbar">
                    <ul class="nav navbar-nav pull-md-right">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= Url::to('/blog') ?>">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= Url::to('/site/contato') ?>">Contato</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= Url::to('/site/login') ?>">Login</a>
                        </li>
                        <li class="nav-item hidden-xs-down">
                            <a class="nav-link" href="https://www.facebook.com/vigilantus" title="Facebook"><i class="fa fa-facebook-official"></i></a>
                        </li>
                        <li class="nav-item hidden-xs-down">
                            <a class="nav-link" href="https://twitter.com/BrasilSemDengue" title="Twitter"><i class="fa fa-twitter"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <?php $this->beginBody() ?>

        <main>
            <div class="<?= Yii::$app->controller->id == 'site' ? 'home-page' : 'container'  ?>">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>

            <section id="parceiros">
                <div class="container">
                    <h1 class="text-xs-center">Parceiros</h1>
                    <div class="row">
                        <div class="col-xs-6 col-md-3 partner">
                            <a href="http://www.projetovisaodesucesso.com.br/" title="Acessar website do Projeto Visão de Sucesso">
                                <img src="<?= Url::base() ?>/img/website/partners/logotipo-visao-sucesso.png" alt="Logotipo do Visão de Sucesso">
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-3 partner">
                            <a href="http://www.sinapsedainovacao.com.br/" title="Acessar website do Sinapse da Inovação">
                                <img src="<?= Url::base() ?>/img/website/partners/logotipo-sinapse-inovacao.png" alt="Logotipo do Sinapse da Inovação">
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-3 partner">
                            <a href="http://www.sebrae.com.br/" title="Acessar website do SEBRAE">
                                <img src="<?= Url::base() ?>/img/website/partners/logotipo-sebrae.png" alt="Logotipo do SEBRAE">
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-3 partner">
                            <a href="http://www.fapesc.sc.gov.br/" title="Acessar website da FAPESC">
                                <img src="<?= Url::base() ?>/img/website/partners/logotipo-fapesc.png" alt="Logotipo da FAPESC">
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <section id="contato">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 address">
                            <h3>Endereço</h3>
                            <address>
                                <p>
                                    Av. Attílio Fontana, 591-E<br>
                                    Efapi, Bloco R (Inctech)<br>
                                    Chapecó/SC
                                </p>
                            </address>
                            <p>
                                Telefone: +55 (49) 3316 0928<br>
                                Email: <a href="mailto:tenha@perspectiva.in">tenha@perspectiva.in</a><br>
                                CNPJ: 19.634.551/0001-35
                            </p>
                        </div>

                        <div class="col-md-7 contact-form">
                          <h3>Entre em Contato</h3>
                          <form accept-charset="UTF-8" action="//formspree.io/tenha@perspectiva.in" method="POST" target="_blank">
                            <fieldset class="form-group">
                              <input type="email" required name="email" class="form-control" id="user-email" placeholder="Seu e-mail">
                            </fieldset>
                            <fieldset class="form-group">
                              <textarea class="form-control" required name="text" id="user-message" placeholder="Sua mensagem" rows="5"></textarea>
                            </fieldset>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                          </form>
                        </div>
                    </div>
                    <p class="text-xs-center copyright-notice">
                        © Copyright 2014-<?= date('Y') ?>.
                        Todos os direitos reservados por
                        <a href="http://perspectiva.in" target="_blank">
                            <img src="<?= Url::base() ?>/img/website/logotipo-perspectiva-rodape.png" alt="Logotipo da Perspectiva">
                        </a>
                    </p>
                </div>
            </section>
        </footer>

        <div id="fb-root"></div>
        <?php
        if (!Yii::$app->user->isGuest) {
            echo $this->render('//shared/_feedback', ['model' => $this->context->feedbackModel]);
        }

        $this->endBody();

        if (YII_ENV_PROD) {
            echo VigilantusLayoutHelper::getAnalyticsCode();
        }
        ?>
    </body>
    <script>
    window.fbAsyncInit = function() {
        FB.init({
          appId      : '755030161284633',
          xfbml      : true,
          version    : 'v2.3'
        });
    };

    (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/pt_BR/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>
    <script>
    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
    </script>
</html>
<?php
$this->endPage();
