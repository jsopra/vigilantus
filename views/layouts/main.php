<?php

use yii\helpers\Html;
use app\components\themes\DetailwrapNav;
use app\components\themes\DetailwrapNavBar;
use app\components\themes\DetailwrapSideBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\Alert;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
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

        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
        <?php $this->head() ?>
    </head>
    <body>
        
        <?php $this->beginBody() ?>
        <?php
        
        DetailwrapNavBar::begin([
            'brandLabel' => 'Vigilantus',
            'brandUrl' => Yii::$app->homeUrl,
        ]);
        
        echo DetailwrapNav::widget([
            'options' => ['class' => 'nav navbar-nav pull-right hidden-xs'],
            'items' => [
                ['label' => '', 'url' => ['/site/contato'], 'icon' => 'envelope'],
                [
                    'visible' => !Yii::$app->user->isGuest,
                    'icon' => 'cog',
                    'options' => [
                        'class' => 'dropdown'
                    ],
                    'items' => [
                        [
                            'label' => 'Alterar senha', 
                            'url' => ['/usuario/change-password'],
                        ],
                    ]
                ],
                [
                    'visible' => Yii::$app->user->isGuest,
                    'url' => ['/site/login'], 
                    'label' => ' Login' ,
                    'icon' => 'off',
                ],
                [
                    'visible' => !Yii::$app->user->isGuest,
                    'url' => ['/site/logout'], 
                    'label' => ' Logout (' . (Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->login) . ')' ,
                    'icon' => 'off',
                    'linkOptions' => ['data-method' => 'post']
                ],
            ],
        ]);
        DetailwrapNavBar::end();
        ?>


        <!-- sidebar -->
        <?php if(!Yii::$app->user->isGuest) : ?>
        <?php echo DetailwrapSideBar::widget([
            'options' => ['id' => 'dashboard-menu'],
            'items' => [
                 [
                    'label' => 'Cadastro',
                    'icon' => 'edit',
                    'items' => [
                        ['label' => 'Bairros', 'url' => ['/bairro/']],
                        ['label' => 'Quarteirões de Bairros', 'url' => ['/bairro-quarteirao/']],
                        ['label' => 'Categoria de Bairros', 'url' => ['/bairro-categoria/']],
                        ['label' => 'Condições de Imóveis', 'url' => ['/imovel-condicao/']],
                        ['label' => 'Tipos de Imóvel', 'url' => ['/imovel-tipo/']],
                    ]
                ],
                [
                    'label' => 'Fichas',
                    'icon' => 'folder-open-alt',
                    'items' => [
                        ['label' => 'Boletim de RG', 'url' => ['/ficha-rg']],
                    ]
                ],
                [
                    'label' => 'Sistema',
                    'icon' => 'cog',
                    'items' => [
                        ['label' => 'Usuários', 'url' => ['/usuario/']],
                        ['label' => 'Alterar minha senha', 'url' => ['/usuario/change-password']],
                    ]
                ],
                ['label' => 'Contato', 'url' => ['/site/contato'], 'icon' => 'envelope-alt'],     
                [
                    'label' => 'Sair',
                    'url' => ['/site/logout'], 
                    'linkOptions' => ['data-method' => 'post'],
                    'icon' => 'off'
                ],
            ]
        ]); ?>
        <?php endif; ?>

        <div class="content <?= Yii::$app->user->isGuest ? 'wide-content' : ''; ?>">
            
            <div id="pad-wrapper">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>

        </div>
        
        <footer class="footer">
            <div class="container">
                <p class="text-center">&copy; Perspectiva <?= date('Y') ?></p>
            </div>
        </footer>
        
        <?php 
        if (!Yii::$app->user->isGuest) 
            echo $this->render('//shared/_feedback', array('model' => $this->context->feedbackModel)); 
        ?>
        
        <!-- asdasd-->
        
        <?php $this->endBody() ?>
    </body>
</html>
<?php
$this->endPage();
