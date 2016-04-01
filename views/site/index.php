<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 */
$this->title = Yii::$app->name;

/*
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
*/
?>
<div class="site-index">

	<div class="jumbotron">
		<h1><?= Html::encode($this->title); ?></h1>

		<p class="lead">Apoio e gestão de prevenção da Dengue</p>
	</div>

	<div class="body-content">

		<div class="row">

			<div class="col-lg-4">

				<h2 class="index-title">Apoio à Decisão</h2>

				<div class="items items-small row-fluid">

                    <div class="item col-lg-4">
                        <a rel="tooltip" title="Acompanhamento de execução de visitas">
                            <img src="img/icones/execucao.png" alt="Execução" />
                        </a>
                        <p>Execução</p>
                    </div>

                    <div class="item col-lg-4">
                        <a rel="tooltip" title="Acompanhamento de roteiros e ações">
                            <img src="img/icones/acompanhamento.png" alt="Acompanhamento" />
                        </a>
                        <p>Acompanhamento</p>
                    </div>

                    <div class="item col-lg-4">
                        <a rel="tooltip" title="Planejamento de ações: metas e ações integradas">
                            <img src="img/icones/planejamento.png" alt="Planejamento" />
                        </a>
                        <p>Planejamento</p>
                    </div>

                    <div class="clearfix"></div>
                </div>
			</div>

			<div class="col-lg-4">

				<h2 class="index-title">Integração</h2>

				<div class="items itensQ2">

                    <img src="img/icones/server.png" alt="Servidor" />
                    <p>Integrações com sistemas terceiros</p>

                    <div class="clearfix"></div>
                </div>

			</div>

            <div class="col-lg-4">

				<h2 class="index-title">Instrumentalização</h2>

				<div class="items items-small row-fluid">

                    <div class="item col-lg-4">
                        <a rel="tooltip" title="Histórico de visitação">
                            <img src="img/icones/arquivo.png" alt="Arquivo" />
                        </a>
                        <p>Histórico</p>
                    </div>

                    <div class="item col-lg-4">
                        <a rel="tooltip" title="Roteiros de execução de visitação">
                            <img src="img/icones/roteiro.png" alt="Roteiro" />
                        </a>
                        <p>Roteiro e Execução</p>
                    </div>

                    <div class="item col-lg-4">
                        <a rel="tooltip" title="Pesquisas segmentadas e com ferramentas auxiliares">
                            <img src="img/icones/pesquisa.png" alt="Pesquisa" />
                        </a>
                        <p>Pesquisa</p>
                    </div>

                    <div class="clearfix"></div>
                </div>

			</div>

        </div>

        <div class="row text-center btn-saiba-mais">
            <p><a class="btn btn-lg btn-glow success" href="/site/about">Saiba mais</a></p>
        </div>

    </div>
</div>
