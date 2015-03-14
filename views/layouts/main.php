<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\themes\DetailwrapNav;
use app\components\themes\DetailwrapNavBar;
use app\components\themes\DetailwrapSideBar;
use app\helpers\VigilantusLayoutHelper;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\Alert;
use yii\web\View;
AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= Html::encode($this->title) ?></title>

        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css' />
        <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css' />
        <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700' rel='stylesheet' type='text/css' />

        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <?php $this->head() ?>

        <?= Html::csrfMetaTags() ?>

        <script>
            var socialHandlerUrl = '<?= Url::to(['/site/auth']); ?>';
            var stepFocosUrl = '<?= Url::to(['/foco-transmissor', 'step' => true]); ?>';
            var stepVisaoGeralUrl= '<?= Url::to(['/site/home', 'step' => true]); ?>';
            var stepArmadilhasCadastroUrl = '<?= Url::to(['/armadilha', 'step' => true]); ?>';
            var stepArmadilhasMapaUrl = '<?= Url::to(['/mapa/armadilha', 'step' => true]); ?>';
            var stepPECadastroUrl = '<?= Url::to(['/ponto-estrategico', 'step' => true]); ?>';
            var stepPEMapaUrl = '<?= Url::to(['/mapa/ponto-estrategico', 'step' => true]); ?>';
            var stepGeolocalizacaoUrl = '<?= Url::to(['/bairro', 'step' => true]); ?>';
            var stepRGUrl = '<?= Url::to(['/boletim-rg', 'step' => true]); ?>';
            var stepDenunciasUrl  = '<?= Url::to(['/denuncia/denuncia/index', 'step' => true]); ?>';

            var moduloDenunciaIsHabilitado = '<?= \Yii::$app->user->getIdentity() && \Yii::$app->user->getIdentity()->moduloIsHabilitado(\app\models\Modulo::MODULO_DENUNCIA) ? '1' : '0'; ?>';

            var isAnalista = '<?= \Yii::$app->user->can("Analista") ? "1" : "0"; ?>';
            var isGerente = '<?= \Yii::$app->user->can("Gerente") ? "1" : "0"; ?>';
        </script>
    </head>
    <body>

        <?php
        $view = Yii::$app->getView();
        $view->registerJs('var feedbackUrl = "' .Url::toRoute(['/site/feedback']) . '";', View::POS_HEAD);
        ?>

        <?php $this->beginBody() ?>
        <?php
        DetailwrapNavBar::begin([
            'brandLabel' => 'Vigilantus',
            'brandUrl' => Yii::$app->homeUrl,
            'municipios' => $this->context->municipiosDisponiveis,
            'municipioLogado' => $this->context->municipioLogado,
        ]);

            echo DetailwrapNav::widget([
                'options' => ['class' => 'nav navbar-nav pull-right hidden-xs'],
                'items' => VigilantusLayoutHelper::getMenuComum(Yii::$app->user),
            ]);

        DetailwrapNavBar::end();
        ?>


        <!-- sidebar -->
        <?php
        if(!Yii::$app->user->isGuest)
            echo DetailwrapSideBar::widget([
                'options' => ['id' => 'dashboard-menu'],
                'items' => VigilantusLayoutHelper::getMenuUsuarioLogado(Yii::$app->user),
            ]);
        ?>

        <div class="content <?= Yii::$app->user->isGuest ? 'wide-content' : ''; ?>">

            <div id="pad-wrapper">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= Alert::widget() ?>
                <?= $content ?>
                <footer class="footer">
                    <div class="row">
                        <div class="col-sm-7 col-sm-offset-1">

                            <h2 class="text-left partners-title">
                                Parceiros
                            </h2>

                            <div class="row">

                                <?php
                                $partners = VigilantusLayoutHelper::getPartners();
                                foreach($partners as $partner) :
                                ?>
                                    <div class="partner text-center">
                                        <a href="<?= $partner['url']; ?>" target="_blank">
                                            <img src="<?= Url::base(); ?>/img/partners/<?= $partner['logo']; ?>" alt="<?= $partner['description']; ?>" />
                                        </a>
                                    </div>
                                <?php
                                endforeach;
                                ?>

                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="col-sm-2 col-sm-offset-2" style="padding-top: 35px;">

                            <p class="text-center perspectiva">
                                <a href="http://perspectiva.in" target="_blank">
                                    perspectiva<span class="domain">.in</span>
                                </a>
                            </p>

                            <p class="text-center">
                                Perspectiva Negócios Digitais &copy; <?= date('Y') ?>
                            </p>
                        </div>

                    </div>
                </footer>
            </div>
        </div>

        <?php
        if (!Yii::$app->user->isGuest) {
            echo $this->render('//shared/_feedback', ['model' => $this->context->feedbackModel]);
            echo $this->render('//shared/_stepguides');
        }
        ?>

        <?php $this->endBody() ?>

        <?php
        if (YII_ENV_PROD) {
            echo VigilantusLayoutHelper::getAnalyticsCode();
        }
        ?>
    </body>
</html>
<?php
$this->endPage();
