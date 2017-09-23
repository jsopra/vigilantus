<?php
namespace app\helpers;
use \yii\web\User;
use app\models\Modulo;

class VigilantusLayoutHelper
{
    /**
     * Menu de usuário logado
     * @param User $user
     * @return array
     */
    public static function getMenuUsuarioLogado(User $user)
    {
        return [
            [
                'label' => 'Ocorrências',
                'icon' => 'fa fa-bullhorn',
                'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA) && !$user->can('Analista'),
                'items' => [
                    ['label' => 'Abertas', 'url' => ['/ocorrencia/ocorrencia/abertas'], 'options' => ['id' => 'stepguide-ocorrencias-abertas']],
                    ['label' => 'Todas', 'url' => ['/ocorrencia/ocorrencia/index'], 'options' => ['id' => 'stepguide-ocorrencias-todas']],
                    ['label' => 'Indicadores', 'url' => ['/ocorrencia/indicador/ocorrencias-mes'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-ocorrencias']],
                    ['label' => 'Mapa de Ocorrências', 'url' => ['/mapa/ocorrencias'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-mapa-ocorrencias']],
                ],
                'options' => ['id' => 'stepguide-ocorrencias', 'class' => 'step-ocorrencias'],
            ],
            [
                'label' => 'Focos',
                'icon' => 'fa fa-crosshairs',
                'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS) && !$user->can('Analista'),
                'items' => [
                    ['label' => 'Gerir focos', 'url' => ['/foco-transmissor'], 'options' => ['id' => 'stepguide-ocorrencias']],
                    ['label' => 'Gerir casos de doenças', 'url' => ['/caso-doenca'], 'options' => ['id' => 'stepguide-casosdoencas']],
                    ['label' => 'Indicadores', 'url' => ['/indicador/resumo-focos'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-indicadores-focos'], 'related' => ['/indicador/evolucao-focos','/indicador/focos-bairro','/indicador/focos-tipo-deposito']],
                    ['label' => 'Mapa de Tratamento', 'url' => ['/mapa/tratamento-foco'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-mapa-tratamento-foco']],
                    ['label' => 'Mapa de Casos de Doenças', 'url' => ['/mapa/casos-doenca'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-mapa-casos-doenca']],
                    ['label' => 'Áreas de Tratamento', 'url' => ['/relatorio/area-tratamento'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-relatorio-areas-tratamento']],
                    ['label' => 'Rel. de Focos', 'url' => ['/relatorio/focos'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-relatorio-focos']],
                    ['label' => 'Amostra de Transmissores', 'url' => ['/amostra-transmissor'], 'visible' => $user->can('Gerente') || $user->can('Analista') || $user->can('Tecnico Laboratorial') || $user->can('Usuario')],
                    ['label' => 'Rel. de Focos por Bairro', 'url' => ['/relatorio/focos-bairro'], 'visible' => $user->can('Gerente') || $user->can('Analista'), 'options' => ['id' => 'stepguide-relatorio-focos-bairro']],
                ],
                'options' => ['id' => 'stepguide-focos', 'class' => 'stepguide-focos'],
            ],
            [
                'label' => 'Localização',
                'icon' => 'fa fa-globe',
                'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && !$user->can('Analista'),
                'items' => [
                    ['label' => 'Bairros e Quarteirões', 'url' => ['/bairro/'], 'options' => ['id' => 'step-cadastro-bairros']],
                    ['label' => 'Reconhecimento Geográfico', 'url' => ['/boletim-rg'], 'options' => ['id' => 'step-cadastro-rg']],
                ],
                'options' => ['class' => 'step-reconhecimento'],
            ],
            [
                'label' => 'Mapas',
                'icon' => 'fa fa-map',
                'visible' => ($user->can('Gerente') || $user->can('Analista')),
                'items' => [
                    ['label' => 'Armadilhas', 'url' => ['/mapa/armadilha'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS) && ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'step-mapa-armadilhas']],
                    ['label' => 'Áreas de Tratamento', 'url' => ['/relatorio/area-tratamento-mapa'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS) && ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'stepguide-mapa-area-tratamento']],
                    ['label' => 'Casos de Doenças', 'url' => ['/mapa/casos-doenca'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS) && ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'stepguide-mapa-casos-doenca']],
                    ['label' => 'Pontos Estratégicos', 'url' => ['/mapa/ponto-estrategico'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS) && ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'step-mapa-pes']],
                    ['label' => 'Tratamento de Foco', 'url' => ['/mapa/tratamento-foco'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS) && ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'stepguide-mapa-tratamento-foco']],
                    ['label' => 'Visão Geral', 'url' => ['/mapa/visao-geral'], 'visible' => ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'stepguide-mapa-visao-geral']],
                    ['label' => 'Ocorrências', 'url' => ['/mapa/ocorrencias'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA) && ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'stepguide-mapa-ocorrencias']],
                ],
                'options' => ['class' => 'step-mapas'],
            ],
            [
                'label' => 'Relatórios',
                'icon' => 'fa fa-bar-chart',
                'items' => [
                    ['label' => 'Resumo de RG por Bairro', 'url' => ['/relatorio/resumo-rg-bairro'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'stepguide-relatorio-resumo-rg']],
                    ['label' => 'Áreas de Tratamento', 'url' => ['/relatorio/area-tratamento'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS) && ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'stepguide-relatorio-areas-tratamento']],
                    ['label' => 'Ocorrências Abertas', 'url' => ['/relatorio/ocorrencias-abertas'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA) && ($user->can('Administrador'))],
                    ['label' => 'Focos', 'url' => ['/relatorio/focos'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS) && ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'stepguide-relatorio-focos']],
                    ['label' => 'Focos por Bairro', 'url' => ['/relatorio/focos-bairro'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS) && ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'stepguide-relatorio-focos-bairro']],
                    ['label' => 'Exportação de Focos', 'url' => ['/relatorio/focos-export'], 'options' => ['id' => 'stepguide-relatorio-focos-exportacao'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS)],
                ],
                'options' => ['class' => 'step-relatorios'],
            ],
            [
                'label' => 'Indicadores',
                'icon' => 'fa fa-dashboard',
                'visible' => $user->can('Gerente') || $user->can('Analista'),
                'items' => [
                    ['label' => 'Focos', 'url' => ['/indicador/resumo-focos'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO) && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS) && ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'stepguide-indicadores-focos'], 'related' => ['/indicador/evolucao-focos','/indicador/focos-bairro','/indicador/focos-tipo-deposito']],
                    ['label' => 'Ocorrências', 'url' => ['/ocorrencia/indicador/ocorrencias-mes'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA) && ($user->can('Gerente') || $user->can('Analista')), 'options' => ['id' => 'stepguide-indicadores-ocorrencias'], 'related' => ['/ocorrencia/indicador/ocorrencias-status', '/ocorrencia/indicador/ocorrencias-problema']],
                ],
                'options' => ['class' => 'step-indicadores'],
            ],
            [
                'label' => 'Cadastros',
                'icon' => 'fa fa-pencil-square-o',
                'visible' => !$user->can('Analista'),
                'items' => [
                    ['label' => 'Armadilhas', 'url' => ['/armadilha/'], 'options' => ['id' => 'step-armadilhas'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS)],
                    ['label' => 'Categoria de Bairros', 'url' => ['/bairro-categoria/'], 'options' => ['id' => 'step-cadastro-categoria-bairros'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA)],
                    ['label' => 'Doenças', 'url' => ['/doenca/'], 'options' => ['id' => 'step-cadastro-doencas'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS)],
                    ['label' => 'Equipes', 'url' => ['/equipe/'], 'visible' => $user->can('Gerente'), 'options' => ['id' => 'stepguide-equipe'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_VISITACAO)],
                    ['label' => 'Espécies de Transmissores', 'url' => ['/especie-transmissor/'], 'options' => ['id' => 'step-cadastro-especieis-transmissores'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS)],
                    ['label' => 'Pontos Estratégicos', 'url' => ['/ponto-estrategico/'], 'options' => ['id' => 'step-pontos-estrategicos'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS)],
                    ['label' => 'Tipos de Imóvel', 'url' => ['/imovel-tipo/'], 'options' => ['id' => 'step-cadastro-tipos-imoveis'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_LOCALIZACAO)],
                    ['label' => 'Termos de Rede Social', 'url' => ['/ocorrencia/social-hashtag/'], 'visible' => $user->can('Gerente') && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA), 'options' => ['id' => 'stepguide-ocorrencias-rs-hashtag']],
                    ['label' => 'Contas de Rede Social', 'url' => ['/ocorrencia/social-account/'], 'visible' => $user->can('Gerente') && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA), 'options' => ['id' => 'stepguide-ocorrencias-rs-contas']],
                    ['label' => 'Setores', 'url' => ['/setor/'], 'visible' => $user->can('Administrador')],
                    ['label' => 'Tipos de Depósitos', 'url' => ['/deposito-tipo/'], 'options' => ['id' => 'step-cadastro-tipos-deposito'], 'visible' => $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_FOCOS)],
                    ['label' => 'Tipo de Problema em Ocorrência', 'url' => ['/ocorrencia/ocorrencia-tipo-problema/'], 'visible' => $user->can('Gerente') && $user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA)],
                ],
                'options' => ['class' => 'step-cadastro'],
            ],
            [
                'label' => 'Sistema',
                'icon' => 'fa fa-gears',
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
                'icon' => 'fa fa-pencil',
                'url' => ['/blog-post/'],
                'visible' => $user->can('Root'),
            ],
            ['label' => 'Contato', 'url' => ['/site/contato'], 'icon' => 'fa fa-envelope-o'],
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
    public static function getMenuComum(User $user)
    {
        return [
            //['label' => 'Blog', 'url' => ['/blog']],
            ['label' => '', 'url' => ['/site/contato'], 'icon' => 'fa fa-envelope-o'],
            [
                'visible' => !$user->isGuest,
                'icon' => 'fa fa-gears',
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
                'icon' => 'fa fa-power-off',
            ],
            [
                'visible' => !$user->isGuest,
                'url' => ['#'],
                'label' => 'Guias' ,
                'icon' => 'fa fa-info',
                'linkOptions' => ['data-toggle' => 'modal', 'data-target' => '#stepguideModal']
            ],
            [
                'visible' => !$user->isGuest,
                'url' => ['/site/logout'],
                'label' => ' Logout (' . ($user->isGuest ? '' : $user->identity->login) . ')' ,
                'icon' => 'fa fa-power-off',
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
