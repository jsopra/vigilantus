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
        <title><?= Html::encode($this->title) ?></title>

        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css' />
        <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css' />
        <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700' rel='stylesheet' type='text/css' />

        <?php $this->head() ?>

        <?= Html::csrfMetaTags() ?>
    </head>

    <body>

        <?= $content ?>

        <footer class="footer">
            <p class="text-center perspectiva">
                Perspectiva
            </p>

            <p class="text-center">
                Tecnologia para viver melhor - &copy; 2014-<?= date('Y') ?>
            </p>
        </footer>

        <?php $this->endBody() ?>

        <script>
            window.print();
        </script>
    </body>

</html>

<?php
$this->endPage();
