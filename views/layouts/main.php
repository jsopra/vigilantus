<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
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
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        
        <?php $this->beginBody() ?>
        <?php
        NavBar::begin([
            'brandLabel' => 'Vigilantus',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                [
                    'label' => 'Home', 
                    'url' => ['/site/index'],
                    'visible' => Yii::$app->user->isGuest,
                ],
                [
                    'label' => 'Mais informações', 
                    'url' => ['/site/about'],
                    'visible' => Yii::$app->user->isGuest,
                ],
                [
                    'label' => 'Cadastro',
                    'visible' => !Yii::$app->user->isGuest,
                    'items' => [
                        ['label' => 'Bairros', 'url' => ['/bairro']],
                        ['label' => 'Quarteirões de Bairros', 'url' => ['/bairro-quarteirao']],
                        ['label' => 'Condições de Imóveis', 'url' => ['/imovel-condicao']],
                        ['label' => 'Tipos de Bairros', 'url' => ['/bairro-tipo']],
                        ['label' => 'Tipos de Imóveis', 'url' => ['/imovel-tipo']],
                    ]
                ],
                [
                    'label' => 'Sistema',
                    'visible' => !Yii::$app->user->isGuest,
                    'items' => [
                        ['label' => 'Usuários', 'url' => ['/usuario']],
                        ['label' => 'Alterar senha', 'url' => ['/usuario/change-password']],
                    ]
                ],
                ['label' => 'Contato', 'url' => ['/site/contato']],
                Yii::$app->user->isGuest ?
                    ['label' => 'Login', 'url' => ['/site/login'], 'itemOptions' => ['icon' => 'icon-off']] :
                    ['label' => 'Logout (' . Yii::$app->user->identity->login . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']],
            ],
        ]);
        NavBar::end();
        ?>

        <div class="container">
            <?=
            Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ])
            ?>
            <?= Alert::widget() ?>
            <?= $content ?>
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
        
<?php $this->endBody() ?>
    </body>
</html>
<?php
$this->endPage();
