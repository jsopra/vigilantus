<?php

use yii\helpers\Html;
use app\models\SocialNetwork;

$this->title = 'Contas de Rede Social';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="social-account-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <h4>Associe as redes sociais de seu Município para iniciar a integração entre elas.</h4>

    <br />

    <div class="body-content">
        <p>
            <?php if(!$cliente->hasNetwork(SocialNetwork::TWITTER)) : ?>
                <a href="#" data-social-login data-social-name="twitter" class="btn btn-info">Twitter</a>
            <?php else : ?>
                <a href="#" data-social-login data-social-name="twitter" class="btn btn-info">Twitter (associado)</a>
            <?php endif; ?>

            <?php if(!$cliente->hasNetwork(SocialNetwork::FACEBOOK)) : ?>
                <a href="#" data-social-login data-social-name="facebook" class="btn btn-primary">Facebook</a>
            <?php else : ?>
                <a href="#" data-social-login data-social-name="facebook" class="btn btn-primary">Facebook (associado)</a>
            <?php endif; ?>

            <?php if(!$cliente->hasNetwork(SocialNetwork::INSTAGRAM)) : ?>
                <a href="#" data-social-login data-social-name="instagram" class="btn btn-default">Instagram</a>
            <?php else : ?>
                <a href="#" data-social-login data-social-name="instagram" class="btn btn-default">Instagram (associado)</a>
            <?php endif; ?>
        </p>
    </div>
</div>
