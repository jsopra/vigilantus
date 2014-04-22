<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 */
$this->title = Yii::$app->name;
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
