<?php
use yii\helpers\Html;
?>
<a name="n-<?= $model->id; ?>"></a>
<article>
    <div class="">

        <div class="page-header">
            <p class="data-post">
                <time pubdate="pubdate">
                    <i class="icon-calendar"></i>
                    <?= Html::encode(Yii::$app->formatter->asDate($model->data)); ?>
                </time>
            </p>
            <h1><?= $model->titulo; ?></h1>
            <?php if ($model->descricao) : ?>
                <h2><?= Html::encode($model->descricao); ?></h2>
            <?php endif; ?>
        </div>

        <div class="post-body">
            <?= $model->texto; ?>
        </div>

        <div class="panel-share bs-callout bs-callout-danger">
            <?php
            $url = Yii::$app->urlManager->createAbsoluteUrl(['/blog/index']);
            ?>
            <ul>
                <li>
                    <a href="https://twitter.com/share" class="twitter-share-button" data-text="<?= $model->titulo; ?>" data-url="<?= $url  . '#id-' . $model->id; ?>" data-lang="pt" data-count="none">Tweetar</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                </li>
                <li>
                    <div class="g-plusone" data-size="tall" data-annotation="none" data-href="<?= $url . '#id-' . $model->id; ?>"></div>
                </li>
                <li>
                    <iframe
                        src="http://www.facebook.com/plugins/like.php?href=<?= $url . '%23id-' . $model->id; ?>&layout=button&action=like&show_faces=false&share=false&height=35&locale=pt_BR"
                        scrolling="no"
                        frameborder="0"
                        style="border:none; overflow:hidden; width: 150px; height:20px;"
                        allowTransparency="true"
                    ></iframe>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
</article>
