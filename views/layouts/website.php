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

        <?php /*
        <meta property="og:locale" content="pt_BR">
        <meta property="og:type" content="website">
        <meta property="og:title" content="<?= Html::encode($this->title) ?>">
        <meta property="og:description" content="Gestão da Vigilância em Saúde.">
        <meta property="og:image" content="//www.vigilantus.com.br/facebook-image.jpg">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        */ ?>

        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <link rel="icon" href="<?= Url::base() ?>/favicon.ico">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css">

        <script type="text/javascript">
          (function(e,t){var n=e.amplitude||{_q:[],_iq:{}};var r=t.createElement("script")
          ;r.type="text/javascript";r.async=true
          ;r.src="https://cdn.amplitude.com/libs/amplitude-4.4.0-min.gz.js"
          ;r.onload=function(){if(e.amplitude.runQueuedFunctions){
          e.amplitude.runQueuedFunctions()}else{
          console.log("[Amplitude] Error: could not load SDK")}}
          ;var i=t.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)
          ;function s(e,t){e.prototype[t]=function(){
          this._q.push([t].concat(Array.prototype.slice.call(arguments,0)));return this}}
          var o=function(){this._q=[];return this}
          ;var a=["add","append","clearAll","prepend","set","setOnce","unset"]
          ;for(var u=0;u<a.length;u++){s(o,a[u])}n.Identify=o;var c=function(){this._q=[]
          ;return this}
          ;var l=["setProductId","setQuantity","setPrice","setRevenueType","setEventProperties"]
          ;for(var p=0;p<l.length;p++){s(c,l[p])}n.Revenue=c
          ;var d=["init","logEvent","logRevenue","setUserId","setUserProperties","setOptOut","setVersionName","setDomain","setDeviceId","setGlobalUserProperties","identify","clearUserProperties","setGroup","logRevenueV2","regenerateDeviceId","logEventWithTimestamp","logEventWithGroups","setSessionId","resetSessionId"]
          ;function v(e){function t(t){e[t]=function(){
          e._q.push([t].concat(Array.prototype.slice.call(arguments,0)))}}
          for(var n=0;n<d.length;n++){t(d[n])}}v(n);n.getInstance=function(e){
          e=(!e||e.length===0?"$default_instance":e).toLowerCase()
          ;if(!n._iq.hasOwnProperty(e)){n._iq[e]={_q:[]};v(n._iq[e])}return n._iq[e]}
          ;e.amplitude=n})(window,document);

          amplitude.getInstance().init("f515c5250de1b9ae7dcb655674e33795");

          amplitude.getInstance().logEvent('LANDING_PAGE');

          function registerFunnel() {
             amplitude.getInstance().logEvent('LEAD_GENERATED');
          }

          amplitude.getInstance().logEvent('LANDING_PAGE');
        </script>

        <?php $this->head() ?>

        <?= Html::csrfMetaTags() ?>
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
                        srcset="<?= Url::base() ?>/img/website/logotipo-vigilantus-grande.png 1x, <?= Url::base() ?>/img/website/logotipo-vigilantus-pequeno@2x.png 2x, <?= Url::base() ?>/img/website/logotipo-vigilantus-grande@3x.png 3x"
                        alt="Vigilantus"
                        width="258"
                        height="50">
                </a>
                <div class="collapse navbar-toggleable-sm" id="exCollapsingNavbar">
                    <ul class="nav navbar-nav pull-md-right">
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contato</a>
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
                                <img src="<?= Url::base() ?>/img/partners/visaodesucesso.png" alt="Logotipo do Visão de Sucesso">
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-3 partner">
                            <a href="http://www.sinapsedainovacao.com.br/" title="Acessar website do Sinapse da Inovação">
                                <img src="<?= Url::base() ?>/img/partners/sinapse.png" alt="Logotipo do Sinapse da Inovação">
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-3 partner">
                            <a href="http://www.sebrae.com.br/" title="Acessar website do SEBRAE">
                                <img src="<?= Url::base() ?>/img/partners/sebrae.png" alt="Logotipo do SEBRAE">
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-3 partner">
                            <a href="http://www.fapesc.sc.gov.br/" title="Acessar website da FAPESC">
                                <img src="<?= Url::base() ?>/img/partners/fapesc.png" alt="Logotipo da FAPESC">
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-3 partner">
                            <a href="https://www.unochapeco.edu.br/" title="Acessar website da Unochapecó">
                                <img src="<?= Url::base() ?>/img/partners/inctech.png" alt="Logotipo da Inctech">
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-3 partner">
                            <a href="https://www.unochapeco.edu.br/" title="Acessar website da Unochapecó">
                                <img src="<?= Url::base() ?>/img/partners/unochapeco.png" alt="Logotipo da Unochapeco">
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <section id="contato">
                <a name="contact"></a>
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
                                <button type="submit" class="btn btn-primary" onsubmit="registerFunnel();">Enviar</button>
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
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
