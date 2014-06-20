<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use yii\widgets\LinkPager;
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\ContactForm $model
 */
$this->title = 'Blog';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-blog">
    <?php
    foreach ($models as $model)
        echo $this->render('_post', ['model' => $model]);

    // display pagination
    echo LinkPager::widget([
        'pagination' => $pages,
    ]);
    ?>
</div>
<script type="text/javascript">
window.___gcfg = {lang: 'pt-BR'};

(function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>
