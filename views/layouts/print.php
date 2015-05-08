<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <?php $this->head() ?>

        <?= Html::csrfMetaTags() ?>
    </head>
    <body>

        <?php $this->beginBody() ?>

            <?= $content ?>

            <footer style="margin-top: 3em;">
                <div style="padding-top: 1em; text-align: center;">

                    <hr style="padding: 0; margin: 0.3em; 0" />

                    <p style="padding: 0; margin: 0.3em; 0"><a href="http://www.vigilantus.com.br" target="_blank">Vigilantus</a> &copy; <?= date('Y') ?></p>
                </div>
            </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php
$this->endPage();
