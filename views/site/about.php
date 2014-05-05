<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 */
$this->title = Yii::$app->name;
?>

<div class="row bg-about">
    
    <div class="row-fluid" id="hotsite-header">
        
		<div class="col-lg-12">
            <h1 class="hotsite-title">Apoio e gestão de<br />prevenção da Dengue</h1>
		</div>
        
        <div class="clearfix"></div>
	</div>
    
    <div class="clearfix"></div>
    
    <section>

        <div class="left_breaker">
            <div class="q0">
                <p>Qualificação<br />processual</p>
            </div>

            <div class="first_breaker">
                <img src="../img/arrow_black_center.png" alt="Desktop" />
            </div>
        </div>

        <div class="q1 ">

            <div class="header">

                <ul class="">
                    <li>
                        <a rel="tooltip" title="Disponível para computadores e tablets">
                            <img src="../img/desktop.png" alt="Desktop" />
                        </a>
                    </li>
                </ul>
                <p class="titulo marginTop">Apoio à Decisão</p>
            </div>

            <div class="items items row-fluid">

                <div class="item col-lg-4">
                    <a rel="tooltip" title="Acompanhamento de execução de visitas">
                        <img src="../img/icones/execucao.png" alt="Execução" />
                    </a>	
                    <p>Execução</p>
                </div>

                <div class="item col-lg-4">
                    <a rel="tooltip" title="Acompanhamento de roteiros e ações">
                        <img src="../img/icones/acompanhamento.png" alt="Acompanhamento" />
                    </a>
                    <p>Acompanhamento</p>
                </div>

                <div class="item col-lg-4">
                    <a rel="tooltip" title="Planejamento de ações: metas e ações integradas">
                        <img src="../img/icones/planejamento.png" alt="Planejamento" />
                    </a>
                    <p>Planejamento</p>
                </div>

                <div class="clearfix"></div>
            </div>
            
        </div>

        <div class="breaker">
            <img src="../img/arrow_black_inverse.png" alt="Desktop" />
        </div>

        <div class="q2 ">

            <div class="header">
                <p class="titulo">Integração</p>
            </div>
            <div class="items itensQ2">

                <img src="../img/icones/server_big.png" alt="Servidor" />
                <p>Integrações com sistemas terceiros</p>

                <div class="clearfix"></div>
            </div>

        </div>

        <div class="breaker">
            <img src="../img/arrow_black.png" alt="Desktop" />
        </div>

        <div class="q3 ">

            <div class="header">	
                
                <ul>
                    <li>
                        <a rel="tooltip" title="Disponível para smartphones e celulares">
                            <img src="../img/mobile.png" alt="Mobile" />
                        </a>
                    </li>
                    <li>
                        <a rel="tooltip" title="Disponível para computadores e tablets">
                            <img src="../img/desktop.png" alt="Desktop" />
                        </a>
                    </li>
                </ul>
                <p class="titulo marginTop">Instrumentalização do Agente</p>
            </div>

            <div class="items items row-fluid">

                <div class="item col-lg-4">
                    <a rel="tooltip" title="Histórico de visitação">
                        <img src="../img/icones/arquivo.png" alt="Arquivo" />
                    </a>
                    <p>Histórico</p>
                </div>

                <div class="item col-lg-4">
                    <a rel="tooltip" title="Roteiros de execução de visitação">
                        <img src="../img/icones/roteiro.png" alt="Roteiro" />
                    </a>
                    <p>Roteiro e Execução</p>
                </div>

                <div class="item col-lg-4">
                    <a rel="tooltip" title="Pesquisas segmentadas e com ferramentas auxiliares">
                        <img src="../img/icones/pesquisa.png" alt="Pesquisa" />
                    </a>
                    <p>Pesquisa</p>
                </div>

                <div class="clearfix"></div>
            </div>

        </div>

        <div class="breaker">
            <img src="../img/arrow_black_inverse.png" alt="Desktop" />
        </div>

    </section>	
		
    <footer class="q4 ">
        
        <div class="row text-center btn-saiba-mais">
            <p><a class="btn btn-lg btn-glow success" href="/site/contato">Solicite uma demonstração!</a></p>
        </div>

    </footer>
        
</div>

<style>
div.content {
    background: url('../img/bg_about.png') repeat 0 0 !important;
}    
</style>