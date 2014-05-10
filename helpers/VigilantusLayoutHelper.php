<?php
namespace app\helpers;
use \yii\web\User;

class VigilantusLayoutHelper {
    
    /**
     * Menu de usuário logado 
     * @param User $user
     * @return array
     */
    public static function getMenuUsuarioLogado(User $user) {
        
        return [
            [
                'label' => 'Cadastros',
                'icon' => 'edit',
                'items' => [
                    ['label' => 'Categoria de Bairros', 'url' => ['/bairro-categoria/']],
                    ['label' => 'Tipos de Imóvel', 'url' => ['/imovel-tipo/']],
                    ['label' => 'Tipos de Depósitos', 'url' => ['/deposito-tipo/']],
                    ['label' => 'Focos de Transmissores', 'url' => ['/foco-transmissor/']],
                    ['label' => 'Espécies de Transmissores', 'url' => ['/especie-transmissor/']],
                ]
            ],
            [
                'label' => 'Localização',
                'icon' => 'globe',
                'items' => [
                    ['label' => 'Bairros e Quarteirões', 'url' => ['/bairro/']],
                    ['label' => 'Boletim de RG', 'url' => ['/boletim-rg']],
                ]
            ],
            [
                'label' => 'Relatórios',
                'icon' => 'bar-chart',
                'visible' => $user->can('Administrador'),
                'items' => [
                    ['label' => 'Boletim de RG', 'url' => ['/relatorio/resumo-rg-bairro']],
                    ['label' => 'Áreas de Tratamento', 'url' => ['/relatorio/mapa-area-tratamento']],
                ],
            ],
            [
                'label' => 'Sistema',
                'icon' => 'cog',
                'items' => [
                    ['label' => 'Usuários', 'url' => ['/usuario/'], 'visible' => $user->can('Administrador'),],
                    ['label' => 'Alterar minha senha', 'url' => ['/usuario/change-password']],
                ]
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
        ];
    }
}