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
                'visible' =>  $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_DENUNCIA)
            ],
            [
                'label' => 'Focos',
                'icon' => 'screenshot',
                'url' => ['/foco-transmissor/'],
            ],
            [
                'label' => 'Localização',
                'icon' => 'globe',
                'items' => [
                    ['label' => 'Bairros e Quarteirões', 'url' => ['/bairro/']],
                    ['label' => 'Reconhecimento Geográfico', 'url' => ['/boletim-rg']],
                ]
            ],
            [
                'label' => 'Relatórios',
                'icon' => 'bar-chart',
                'items' => [
                    ['label' => 'Resumo de RG por Bairro', 'url' => ['/relatorio/resumo-rg-bairro'], 'visible' => $user->can('Gerente'),],
                    ['label' => 'Áreas de Tratamento', 'url' => ['/relatorio/area-tratamento'], 'visible' => $user->can('Gerente'),],
                    ['label' => 'Focos', 'url' => ['/relatorio/focos'], 'visible' => $user->can('Gerente'),],
                    ['label' => 'Focos por Bairro', 'url' => ['/relatorio/focos-bairro'], 'visible' => $user->can('Gerente'),],
                    ['label' => 'Exportação de Focos', 'url' => ['/relatorio/focos-export']],
                ],
            ],
            [
                'label' => 'Indicadores',
                'icon' => 'bar-chart',
                'items' => [
                    ['label' => 'Resumo de Focos por Ano', 'url' => ['/indicador/resumo-focos'], 'visible' => $user->can('Gerente'),],
                    ['label' => 'Evolução de Focos por Mês', 'url' => ['/indicador/evolucao-focos'], 'visible' => $user->can('Gerente'),],
                    ['label' => 'Focos por Bairros', 'url' => ['/indicador/focos-bairro'], 'visible' => $user->can('Gerente'),],
                    ['label' => 'Focos por Tipo de Depósito', 'url' => ['/indicador/focos-tipo-deposito'], 'visible' => $user->can('Gerente'),],
                ],
            ],
            [
                'label' => 'Cadastros',
                'icon' => 'edit',
                'items' => [
                    ['label' => 'Categoria de Bairros', 'url' => ['/bairro-categoria/']],
                    ['label' => 'Doenças', 'url' => ['/doenca/']],
                    ['label' => 'Tipos de Imóvel', 'url' => ['/imovel-tipo/']],
                    ['label' => 'Tipos de Depósitos', 'url' => ['/deposito-tipo/']],
                    ['label' => 'Espécies de Transmissores', 'url' => ['/especie-transmissor/']],
                    ['label' => 'Tipo de Problema em Denúncia', 'url' => ['/denuncia/denuncia-tipo-problema/'], 'visible' => $user->can('Gerente') && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_DENUNCIA)],
                ]
            ],
            [
                'label' => 'Sistema',
                'icon' => 'cog',
                'items' => [
                    ['label' => 'Clientes', 'url' => ['/cliente/'], 'visible' => $user->can('Root'),],
                    ['label' => 'Municípios', 'url' => ['/municipio/'], 'visible' => $user->can('Root'),],
                    ['label' => 'Módulos', 'url' => ['/modulo/'], 'visible' => $user->can('Root'),],
                    ['label' => 'Usuários', 'url' => ['/usuario/'], 'visible' => $user->can('Administrador'),],
                    ['label' => 'Alterar minha senha', 'url' => ['/usuario/change-password']],
                ]
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
