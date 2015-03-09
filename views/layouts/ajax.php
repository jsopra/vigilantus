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
        </script>
    </head>
    <body>

        <?php $this->beginBody() ?>


        <?= $content ?>


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
