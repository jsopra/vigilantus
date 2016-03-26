<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 */
$this->title = Yii::$app->name;
?>
<section class="jumbotron">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="display-3 text-xs-center balance-text">
                    Lorem ipsum dolor sit amet, vim eu postea molestie
                </h1>
                <p class="lead text-xs-center balance-text">
                Lorem ipsum dolor sit amet, vim eu postea molestie,
                cetero placerat pri in. Eu enim tollit possit eum, mea.
                </p>
            </div>
            <div class="col-md-4 hidden-sm-down">
                <img src="<?= Url::base() ?>/img/selo-mosquito.png" alt="Mosquito">
            </div>
        </div>
    </div>
</section>

<section class="request-demo">
    <h1>Solicite uma demonstração gratuita</h1>
    <p>Preencha seu endereço de e-mail e solicite uma demonstração gratuita do software:</p>
    <form class="form-inline">
        <div class="form-group">
            <input type="email" class="form-control" placeholder="Seu e-mail" name="email">
        </div>
        <button class="btn btn-primary">Solicitar</button>
    </form>
</section>

<section class="feature conformidade-pncd">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <img src="<?= Url::base() ?>/img/funcionalidade.jpg" alt="Ilustração">
            </div>
            <div class="col-sm-7">
                <h1 class="balance-text">Conformidade com o PNCD</h1>
                <p class="balance-text">Lorem ipsum dolor sit amet, vim eu postea molestie,
                cetero placerat pri in. Eu enim tollit possit eum, mea
                Gorem ipsum dolor sit amet, vim eu postea molestie,
                cetero placerat pri in. Eu enim tollit possit eum, mea.<p>
                <p class="balance-text">Lorem ipsum dolor sit amet, vim eu postea molestie,
                cetero placerat pri in. Eu enim tollit possit eum, mea.<p>
            </div>
        </div>
    </div>
</section>

<section class="feature georreferenciamento">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <img src="<?= Url::base() ?>/img/funcionalidade.jpg" alt="Ilustração">
            </div>
            <div class="col-sm-7">
                <h1 class="balance-text">Georreferenciamento</h1>
                <p class="balance-text">Lorem ipsum dolor sit amet, vim eu postea molestie,
                cetero placerat pri in. Eu enim tollit possit eum, mea
                Gorem ipsum dolor sit amet, vim eu postea molestie,
                cetero placerat pri in. Eu enim tollit possit eum, mea.<p>
                <p class="balance-text">Lorem ipsum dolor sit amet, vim eu postea molestie,
                cetero placerat pri in. Eu enim tollit possit eum, mea.<p>
            </div>
        </div>
    </div>
</section>

<section class="feature ocorrencias-denuncias">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <img src="<?= Url::base() ?>/img/funcionalidade.jpg" alt="Ilustração">
            </div>
            <div class="col-sm-7">
                <h1 class="balance-text">Ocorrências e denúncias</h1>
                <p class="balance-text">Lorem ipsum dolor sit amet, vim eu postea molestie,
                cetero placerat pri in. Eu enim tollit possit eum, mea
                Gorem ipsum dolor sit amet, vim eu postea molestie,
                cetero placerat pri in. Eu enim tollit possit eum, mea.<p>
                <p class="balance-text">Lorem ipsum dolor sit amet, vim eu postea molestie,
                cetero placerat pri in. Eu enim tollit possit eum, mea.<p>
            </div>
        </div>
    </div>
</section>

<section class="news">
    <div class="container">
        <h1 class="text-xs-center">Notícias</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="js-video">
                    <iframe width="560" height="315" src="http://www.youtube.com/embed/ab0c_LQCb2w?showinfo=1" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            <div class="col-md-6">
                <ul>
                    <li><a href="http://www.radiopiratuba.com.br/noticias/noticia/id:1495;software-de-combate-a-dengue-desenvolvido-em-sc-e-apresentado-no-ministerio-da-saude.html" target="_blank">Software de combate a dengue desenvolvido em SC é apresentado no Ministério da Saúde</a></li>
                    <li><a href="https://www.youtube.com/watch?v=ab0c_LQCb2w" target="_blank">Aplicativo "Vigilantus" desenvolvido em Chapecó ajuda no combate à dengue</a></li>
                    <li><a href="http://agenciaal.alesc.sc.gov.br/index.php/noticia_single/estudantes-de-chapeco-desenvolvem-aplicativo-voltado-ao-combate-da-dengue" target="_blank">Estudantes de Chapecó criam software voltado ao combate da dengue</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
