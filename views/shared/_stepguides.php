<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="modal fade" id="stepguideModal" tabindex="-1" role="dialog" aria-labelledby="Tutoriais" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Guias passo-a-passo</h4>
            </div>

            <div class="modal-body">

                <?php if(\Yii::$app->user->can('Gerente')) : ?>
                    <p><strong>Está iniciando a implantação da ferramenta? Comece <a href="#" data-step-type="cadastro-inicial">por aqui</a>.</strong></p>
                <?php endif; ?>

                <p><strong>Clique na ferramenta desejada para inciar o guia passo-a-passo. Guias disponíveis para você:</strong></p>
                <ul>
                    <?php if(!\Yii::$app->user->can('Analista')) : ?>
                        <li><a href="#" data-step-type="armadilhas">Armadilhas</a></li>
                        <?php if(\Yii::$app->user->can('Administrador')) : ?>
                            <li><a href="#" data-step-type="cadastro-administrativo">Cadastro Administrativo</a></li>
                        <?php endif; ?>
                        <?php if(\Yii::$app->user->can('Gerente')) : ?>
                            <li><a href="#" data-step-type="cadastro-basico">Cadastro Básico</a></li>
                            <li><a href="#" data-step-type="cadastro-inicial">Cadastro Inicial</a></li>
                        <?php endif; ?>
                        <li><a href="#" data-step-type="focos">Cadastro de Focos</a></li>
                        <li>
                            <a href="#" data-step-type="reconhecimento-geografico">Cadastro de Reconhecimento Geográfico</a>
                            <ul>
                                <li><a href="#" data-step-type="rg-geolocalizacao">Geolocalização do Município</a></li>
                                <li><a href="#" data-step-type="rg-boletim">Boletins de RG</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if(!\Yii::$app->user->can('Analista') && \Yii::$app->user->getIdentity()->moduloIsHabilitado(\app\models\Modulo::MODULO_DENUNCIA)) : ?>
                        <li>
                            <a href="#" data-step-type="denuncias">Denúncias</a>
                            <?php if(\Yii::$app->user->can('Gerente')) : ?>
                                <ul>
                                    <li><a href="#" data-step-type="denuncias-social">Denúncias em Redes Sociais</a></li>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endif; ?>
                    <?php if(!\Yii::$app->user->can('Analista')) : ?>
                        <li><a href="#" data-step-type="pontos-estrategicos">Pontos Estratégicos</a></li>
                    <?php endif; ?>
                    <?php if(\Yii::$app->user->can('Gerente')) : ?>
                        <li><a href="#" data-step-type="relatorios-focos">Relatórios de Focos</a></li>
                        <li><a href="#" data-step-type="relatorios-rg">Relatórios de RG</a></li>
                    <?php endif; ?>
                    <li><a href="#" data-step-type="visao-geral">Visão Geral</a></li>
                </ul>

                <p><strong>Em caso de dúvida, problema ou sugestão você pode fazer clicando no botão Feedback, na direita da tela.</strong></p>
            </div>

            <div class="modal-footer">
                <?= Html::button('Fechar', ['class' => 'btn btn-flat white', 'data-role' => 'cancel', 'data-dismiss' => 'modal']) ?>
            </div>
        </div>
    </div>
</div>
