<?php
namespace app\helpers;
use \yii\web\User;
use app\models\Modulo;

class VigilantusLayoutHelper {

    /**
     * Menu de usuário logado
     * @param User $user
     * @return array
     */
    public static function getMenuUsuarioLogado(User $user) {

        return [
            [
                'label' => 'Denúncias',
                'icon' => 'bullhorn',
                'url' => ['/denuncia/denuncia/index'],
                'visible' =>  $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_DENUNCIA) && !$user->can('Analista'),
                'options' => ['id' => 'stepguide-denuncias'],
            ],
            [
                'label' => 'Focos',
                'icon' => 'screenshot',
                'url' => ['/foco-transmissor/'],
                'visible' => !$user->can('Analista'),
                'options' => ['id' => 'stepguide-focos'],
            ],
            [
                'label' => 'Localização',
                'icon' => 'globe',
                'visible' => !$user->can('Analista'),
                'items' => [
                    ['label' => 'Bairros e Quarteirões', 'url' => ['/bairro/'], 'options' => ['id' => 'step-cadastro-bairros']],
                    ['label' => 'Reconhecimento Geográfico', 'url' => ['/boletim-rg'], 'options' => ['id' => 'step-cadastro-rg']],
                ],
                'options' => ['class' => 'step-reconhecimento'],
            ],
            [
                'label' => 'Mapas',
                'icon' => 'map-marker',
                'visible' => $user->can('Gerente') || $user->can('Analista'),
                'items' => [
                    ['label' => 'Armadilhas', 'url' => ['/mapa/armadilha'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'step-mapa-armadilhas']],
                    ['label' => 'Áreas de Tratamento', 'url' => ['/relatorio/area-tratamento-mapa'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-mapa-area-tratamento']],
                    ['label' => 'Pontos Estratégicos', 'url' => ['/mapa/ponto-estrategico'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'step-mapa-pes']],
                    ['label' => 'Tratamento de Foco', 'url' => ['/mapa/tratamento-foco'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-mapa-tratamento-foco']],
                    ['label' => 'Visão Geral', 'url' => ['/mapa/visao-geral'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-mapa-visao-geral']],
                ],
                'options' => ['class' => 'step-mapas'],
            ],
            [
                'label' => 'Relatórios',
                'icon' => 'bar-chart',
                'items' => [
                    ['label' => 'Resumo de RG por Bairro', 'url' => ['/relatorio/resumo-rg-bairro'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-relatorio-resumo-rg']],
                    ['label' => 'Áreas de Tratamento', 'url' => ['/relatorio/area-tratamento'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-relatorio-areas-tratamento']],
                    ['label' => 'Focos', 'url' => ['/relatorio/focos'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-relatorio-focos']],
                    ['label' => 'Focos por Bairro', 'url' => ['/relatorio/focos-bairro'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-relatorio-focos-bairro']],
                    ['label' => 'Exportação de Focos', 'url' => ['/relatorio/focos-export'], 'options' => ['id' => 'stepguide-relatorio-focos-exportacao']],
                ],
                'options' => ['class' => 'step-relatorios'],
            ],
            [
                'label' => 'Indicadores',
                'icon' => 'bar-chart',
                'visible' => $user->can('Gerente') || $user->can('Analista'),
                'items' => [
                    ['label' => 'Resumo de Focos por Ano', 'url' => ['/indicador/resumo-focos'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-indicadores-focos-ano']],
                    ['label' => 'Evolução de Focos por Mês', 'url' => ['/indicador/evolucao-focos'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-indicadores-evolucao-focos']],
                    ['label' => 'Focos por Bairros', 'url' => ['/indicador/focos-bairro'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-indicadores-focos-bairro']],
                    ['label' => 'Focos por Tipo de Depósito', 'url' => ['/indicador/focos-tipo-deposito'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-indicadores-focos-tipo-deposito']],
                ],
                'options' => ['class' => 'step-indicadores'],
            ],
            [
                'label' => 'Cadastros',
                'icon' => 'edit',
                'visible' => !$user->can('Analista'),
                'items' => [
                    ['label' => 'Armadilhas', 'url' => ['/armadilha/'], 'options' => ['id' => 'step-armadilhas']],
                    ['label' => 'Categoria de Bairros', 'url' => ['/bairro-categoria/'], 'options' => ['id' => 'step-cadastro-categoria-bairros']],
                    ['label' => 'Doenças', 'url' => ['/doenca/'], 'options' => ['id' => 'step-cadastro-doencas']],
                    ['label' => 'Espécies de Transmissores', 'url' => ['/especie-transmissor/'], 'options' => ['id' => 'step-cadastro-especieis-transmissores']],
                    ['label' => 'Pontos Estratégicos', 'url' => ['/ponto-estrategico/'], 'options' => ['id' => 'step-pontos-estrategicos']],
                    ['label' => 'Tipos de Imóvel', 'url' => ['/imovel-tipo/'], 'options' => ['id' => 'step-cadastro-tipos-imoveis']],
                    ['label' => 'Termos de Rede Social', 'url' => ['/denuncia/social-hashtag/'], 'visible' => $user->can('Gerente') && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_DENUNCIA), 'options' => ['id' => 'stepguide-denuncias-rs-hashtag']],
                    ['label' => 'Contas de Rede Social', 'url' => ['/denuncia/social-account/'], 'visible' => $user->can('Gerente') && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_DENUNCIA), 'options' => ['id' => 'stepguide-denuncias-rs-contas']],
                    ['label' => 'Tipos de Depósitos', 'url' => ['/deposito-tipo/'], 'options' => ['id' => 'step-cadastro-tipos-deposito']],
                    ['label' => 'Tipo de Problema em Denúncia', 'url' => ['/denuncia/denuncia-tipo-problema/'], 'visible' => $user->can('Gerente') && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_DENUNCIA)],
                ],
                'options' => ['class' => 'step-cadastro'],
            ],
            [
                'label' => 'Sistema',
                'icon' => 'cog',
                'visible' => !$user->can('Analista'),
                'items' => [
                    ['label' => 'Clientes', 'url' => ['/cliente/'], 'visible' => $user->can('Root'),],
                    ['label' => 'Configurações', 'url' => ['/configuracao-cliente/'], 'visible' => $user->can('Administrador'), 'options' => ['id' => 'step-sistema-configuracoes']],
                    ['label' => 'Municípios', 'url' => ['/municipio/'], 'visible' => $user->can('Root'),],
                    ['label' => 'Módulos', 'url' => ['/modulo/'], 'visible' => $user->can('Root'),],
                    ['label' => 'Usuários', 'url' => ['/usuario/'], 'visible' => $user->can('Administrador'), 'options' => ['id' => 'step-sistema-usuarios']],
                    ['label' => 'Alterar minha senha', 'url' => ['/usuario/change-password']],
                ],
                'options' => ['class' => 'step-sistema'],
            ],
            [
                'label' => 'Blog Posts',
                'icon' => 'pencil',
                'url' => ['/blog-post/'],
                'visible' => $user->can('Root'),
            ],
            ['label' => 'Contato', 'url' => ['/site/contato'], 'icon' => 'envelope-alt'],
            [
                'label' => 'Sair',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post'],
                'icon' => 'off'
            ],
        ];
    }

    /**
     * Menu comum, exibido em qualquer página da aplicação
     * @param User $user
     * @return array
     */
    public static function getMenuComum(User $user) {

        return [
            ['label' => 'Blog', 'url' => ['/blog']],
            ['label' => '', 'url' => ['/site/contato'], 'icon' => 'envelope'],
            [
                'visible' => !$user->isGuest,
                'icon' => 'cog',
                'options' => [
                    'class' => 'dropdown'
                ],
                'items' => [
                    [
                        'label' => 'Alterar senha',
                        'url' => ['/usuario/change-password'],
                    ],
                ]
            ],
            [
                'visible' => $user->isGuest,
                'url' => ['/site/login'],
                'label' => ' Login' ,
                'icon' => 'off',
            ],
            [
                'visible' => !$user->isGuest,
                'url' => ['#'],
                'label' => 'Guias' ,
                'icon' => 'info-sign',
                'linkOptions' => ['data-toggle' => 'modal', 'data-target' => '#stepguideModal']
            ],
            [
                'visible' => !$user->isGuest,
                'url' => ['/site/logout'],
                'label' => ' Logout (' . ($user->isGuest ? '' : $user->identity->login) . ')' ,
                'icon' => 'off',
                'linkOptions' => ['data-method' => 'post']
            ],
        ];
    }

    /**
     * Código do google analytics
     * @return string
     */
    public static function getAnalyticsCode() {

        return "
            <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-47695976-1', 'vigilantus.com.br');
            ga('send', 'pageview');

            </script>
        ";

    }

    /**
     * Busca parceiros do projeto
     * @return array
     */
    public static function getPartners() {

        return [
            [
                'url' => 'http://www.fapesc.sc.gov.br/',
                'logo' => 'fapesc.png',
                'description' => 'FAPESC',
            ],
            [
                'url' => 'http://www.sebrae.com.br/uf/santa-catarina',
                'logo' => 'sebrae.png',
                'description' => 'SEBRAE',
            ],
            [
                'url' => 'http://www.sinapsedainovacao.com.br/',
                'logo' => 'sinapse.png',
                'description' => 'Sinapse da Inovação',
            ],
            [
                'url' => 'http://www.projetovisaodesucesso.com.br/',
                'logo' => 'visaodesucesso.png',
                'description' => 'Projeto Visão de Sucesso - Empreendedorismo de alto impacto na base da pirâmede',
            ],
        ];
    }
}
