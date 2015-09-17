$(document).ready(function(){

    var intro = introJs();
    intro.setOption("skipLabel", "Sair");
    intro.setOption("doneLabel", "Fechar");
    intro.setOption("nextLabel", "Próximo");
    intro.setOption("prevLabel", "Anterior");

    $('a[data-step-type="focos"]').click(function(){

        $('#stepguideModal').modal('hide');

        intro.setOptions({
            steps: [
                {
                    element: "#stepguide-focos",
                    intro: "Para cadastrar os focos, abra o cadastro de focos"
                },
            ],
            doneLabel: 'Próxima Página',
            tooltipPosition: 'auto'
        });

        intro.start().oncomplete(function() {
          window.location.href = stepFocosUrl;
        });
    });

    $('a[data-step-type="visao-geral"]').click(function(){

        $('#stepguideModal').modal('hide');

        intro.setOptions({
            steps: [
                {
                    element: ".navbar-brand",
                    intro: "Você pode acessar a visão geral clicando na logo do software"
                },
            ],
            doneLabel: 'Ir para página!',
            tooltipPosition: 'auto'
        });

        intro.start().oncomplete(function() {
          window.location.href = stepVisaoGeralUrl;
        });
    });

    $('a[data-step-type="armadilhas"]').click(function(){

        $('#stepguideModal').modal('hide');

        $('li.step-cadastro').children('a').trigger('click');

        intro.setOptions({
            steps: [
                {
                    element: "#step-armadilhas",
                    intro: "Para começar, você precisa mapear suas armadilhas"
                },
            ],
            doneLabel: 'Ir para página!',
            tooltipPosition: 'auto'
        });

        intro.start().oncomplete(function() {
          window.location.href = stepArmadilhasCadastroUrl;
        });
    });

    $('a[data-step-type="pontos-estrategicos"]').click(function(){

        $('#stepguideModal').modal('hide');

        $('li.step-cadastro').children('a').trigger('click');

        intro.setOptions({
            steps: [
                {
                    element: "#step-pontos-estrategicos",
                    intro: "Para começar, você precisa mapear seus pontos estratégicos"
                },
            ],
            doneLabel: 'Ir para ferramenta!',
            tooltipPosition: 'auto'
        });

        intro.start().oncomplete(function() {
          window.location.href = stepPECadastroUrl;
        });
    });

    $('a[data-step-type="cadastro-basico"]').click(function(){

        $('#stepguideModal').modal('hide');

        $('li.step-cadastro').children('a').trigger('click');

        intro.setOptions({
            steps: [
                {
                    element: "li.step-cadastro",
                    intro: "Para fazer uso do sistema você precisa cadastra algumas informações básicas"
                },
                {
                    element: "#step-cadastro-categoria-bairros",
                    intro: "Aqui você define categorias de bairros. Ex: Rural, Urbano"
                },
                {
                    element: "#step-cadastro-doencas",
                    intro: "Aqui você define as doenças em monitoramento. Ex: Dengue, Chikungunya"
                },
                {
                    element: "#step-cadastro-especieis-transmissores",
                    intro: "Aqui você define as espécies de transmissores em monitoramento. Ex: Aedes Aegypti"
                },
                {
                    element: "#step-cadastro-tipos-imoveis",
                    intro: "Aqui você define os tipos de imóveis em monitoramento. Ex: Terreno Baldio, PE's, Residencial"
                },
                {
                    element: "#step-cadastro-tipos-deposito",
                    intro: "Aqui você define os tipos de depósitos em monitoramento. Ex: Cisternas, Piscinas"
                },
                {
                    element: "#stepguide-equipe",
                    intro: "Aqui você cadastra equipes e agentes para estruturar visitação, ocorrências, etc"
                }
            ],
            tooltipPosition: 'auto'
        });

        intro.start();
    });

    $('a[data-step-type="cadastro-administrativo"]').click(function(){

        $('#stepguideModal').modal('hide');

        $('li.step-sistema').children('a').trigger('click');

        intro.setOptions({
            steps: [
                {
                    element: "li.step-sistema",
                    intro: "O administrador tem algumas funções adicionais"
                },
                {
                    element: "#step-sistema-configuracoes",
                    intro: "Aqui você define configurações do sistema, como visiblidade de informações, intervalos de atenção em ocorrências, entre outros"
                },
                {
                    element: "#step-sistema-usuarios",
                    intro: "Aqui você gerencia os usuários do sistema para seu Município"
                }
            ],
            tooltipPosition: 'auto'
        });

        intro.start();
    });

    $('a[data-step-type="cadastro-inicial"]').click(function(){

        $('#stepguideModal').modal('hide');

        var arrayOpcoes = [
            {
                element: "li.step-cadastro",
                intro: "Para começar o uso do sistema você precisa configurar o cadastro inicial, já preenchido com definições padrão"
            },
            {
                element: "li.step-sistema",
                intro: "Como também revisar configurações de sistema e cadastrar usuários"
            },
            {
                element: "li.step-reconhecimento",
                intro: "Em seguida você deve partir para geolicalização de seu ambiente e submeter seus boletins de RG"
            },
            {
                element: "#stepguide-focos",
                intro: "Com o Município mapeado, você pode iniciar a submissão dos focos dos transmissores ao sistema"
            },
            {
                element: "li.step-relatorios",
                intro: "E assim fazer uso dos mais diversos relatórios, que detalham RG e Focos"
            },
            {
                element: "li.step-mapas",
                intro: "Como também fazer uso de diferentes mapas, que guiarão visualmente sua análise"
            },
            {
                element: "li.step-indicadores",
                intro: "E de indicadores gerais, que permitirão uma análise rápida do cenário atual"
            }
        ];

        arrayOpcoes.push(
            {
                element: ".navbar-brand",
                intro: "Para sintetizar tudo isso, a página inicial te dará uma visão geral e principais alertas de indicadores."
            }
        );

        arrayOpcoes.push(
            {
                element: 'a[data-target="#stepguideModal"]',
                intro: "Nos demais guias passo-a-passo você pode acessar detalhes sobre cada uma destas etapas"
            }
        );

        intro.setOptions({
            steps: arrayOpcoes,
            tooltipPosition: 'auto'
        });

        intro.start();
    });

    $('a[data-step-type="reconhecimento-geografico"]').click(function(){

        $('#stepguideModal').modal('hide');

        $('li.step-reconhecimento').children('a').trigger('click');

        intro.setOptions({
            steps: [
                {
                    element: "li.step-reconhecimento",
                    intro: "O reconhecimento geográfico é dividido em duas partes: geolocalizar a cidade e gerir boletins de RG"
                },
                {
                    element: "#step-cadastro-bairros",
                    intro: "No cadastro de bairros e quarteirões você geolocalizará seu município, permitindo inúmeras relações entre dados ao sistema"
                },
                {
                    element: "#step-cadastro-rg",
                    intro: "No cadastro de Boletins de RG você poderá cadastrar Boletins, Fechamentos ou importar Fechamentos ao sistema"
                }
            ],
            tooltipPosition: 'auto'
        });

        intro.start();
    });

    $('a[data-step-type="rg-geolocalizacao"]').click(function(){

        $('#stepguideModal').modal('hide');

        $('li.step-reconhecimento').children('a').trigger('click');

        intro.setOptions({
            steps: [
                {
                    element: "#step-cadastro-bairros",
                    intro: "No cadastro de bairros e quarteirões você geolocalizará seu município, permitindo inúmeras relações entre dados ao sistema"
                },
            ],
            doneLabel: 'Ir para ferramenta!',
            tooltipPosition: 'auto'
        });

        intro.start().oncomplete(function() {
          window.location.href = stepGeolocalizacaoUrl;
        });
    });

    $('a[data-step-type="rg-boletim"]').click(function(){

        $('#stepguideModal').modal('hide');

        $('li.step-reconhecimento').children('a').trigger('click');

        intro.setOptions({
            steps: [
                {
                    element: "#step-cadastro-rg",
                    intro: "No cadastro de RG você submete Boletins de Reconhecimento aos bairros/quarteirões cadastrados"
                },
            ],
            doneLabel: 'Ir para ferramenta!',
            tooltipPosition: 'auto'
        });

        intro.start().oncomplete(function() {
          window.location.href = stepRGUrl;
        });
    });

    $('a[data-step-type="ocorrencias"]').click(function(){

        $('#stepguideModal').modal('hide');
        $('li.step-indicadores').children('a').trigger('click');
        $('li.step-ocorrencias').children('a').trigger('click');

        intro.setOptions({
            steps: [
                {
                    element: "li.step-indicadores",
                    intro: "Os indicadores permitem a você ter uma visão sintética sobre diferentes contextos"
                },
                {
                    element: "#stepguide-indicadores-ocorrencias",
                    intro: "Um conjunto de gráficos que detalham a situação de Ocorrências em seu Município"
                },
                {
                    element: "#stepguide-ocorrencias-todas",
                    intro: "Você pode gerenciar todas as ocorrências recebidas ou cadastradas no sistema"
                },
                {
                    element: "#stepguide-ocorrencias-abertas",
                    intro: "Ou mesmo controlar rapidamente as ocorrências abertas"
                },
            ],
            doneLabel: 'Ir para ferramenta!',
            tooltipPosition: 'auto'
        });

        intro.start().oncomplete(function() {
          window.location.href = stepOcorrenciasUrl;
        });
    });

    $('a[data-step-type="ocorrencias-social"]').click(function(){

        $('#stepguideModal').modal('hide');

        $('li.step-cadastro').children('a').trigger('click');

        intro.setOptions({
            steps: [
                {
                    element: "li.step-cadastro",
                    intro: "Para configurar o recebimento de ocorrências por redes sociais, você precisa fazer algumas configurações"
                },
                {
                    element: "#stepguide-ocorrencias-rs-contas",
                    intro: "Primeiramente você precisa associar as contas de rede social de seu Setor ao sistema"
                },
                {
                    element: "#stepguide-ocorrencias-rs-hashtag",
                    intro: "Em seguida você pode cadastrar termos de pesquisa (hashtags) para monitorar"
                },{
                    element: "#stepguide-ocorrencias",
                    intro: "Com isso, qualquer ocorrência feita via rede social será cadastrada como uma pré-ocorrência"
                }
            ],
            tooltipPosition: 'auto'
        });

        intro.start();
    });

    $('a[data-step-type="relatorios-focos"]').click(function(){

        $('#stepguideModal').modal('hide');

        $('li.step-mapas').children('a').trigger('click');
        $('li.step-relatorios').children('a').trigger('click');
        $('li.step-indicadores').children('a').trigger('click');

        var stepsOptions = [];

        if(isAnalista == '0') {
            stepsOptions.push(
                {
                    element: "#stepguide-focos",
                    intro: "Após inserir alguns focos pela ferramenta, você já pode visualizar diferentes relatórios, mapas e indicadores"
                }
            );
        }

        intro.setOptions({
            steps: stepsOptions.concat([

                {
                    element: "li.step-mapas",
                    intro: "Algumas das ferramentas mais interessantes de focos estão nos mapas. Vamos à elas!"
                },
                {
                    element: "#stepguide-mapa-area-tratamento",
                    intro: "No mapa de áreas de tratamento você tem uma visão geral das áreas de tratamento de toda a cidade ou de um bairro em específico"
                },
                {
                    element: "#stepguide-mapa-tratamento-foco",
                    intro: "No mapa de tratamento de foco você vê detalhadamente qual é área de tratamento gerada por um foco"
                },
                {
                    element: "#stepguide-mapa-visao-geral",
                    intro: "No mapa de visão geral você tem uma visão geral sobre focos, como também armadilhas, pontos estratégicos, bairros, entre outros"
                },
                {
                    element: "li.step-relatorios",
                    intro: "Nos relatórios de foco você tem uma visão analítica sobre focos"
                },
                {
                    element: "#stepguide-relatorio-areas-tratamento",
                    intro: "No relatório de áreas de tratamento você vê uma lista detalhada dos quarteirões em tratamento, tal como uma lista atualizada sobre os focos ativos para a data atual"
                },
                {
                    element: "#stepguide-relatorio-focos",
                    intro: "No relatório de focos você tem uma visão detalhada sobre cada foco lançado no sistema"
                },
                {
                    element: "#stepguide-relatorio-focos-bairro",
                    intro: "No relatório de focos por bairro você vê quantos e quais os focos de cada bairro em cada mês do ano"
                },
                {
                    element: "#stepguide-relatorio-focos-exportacao",
                    intro: "Por fim você tem um relatório de focos exportável para excel com identificação de seu município, que pode ser encaminhado à qualquer autoridade competente, de forma oficial"
                },
                {
                    element: "li.step-indicadores",
                    intro: "Os indicadores de foco permitem a você ter uma visão sintética sobre diferentes contextos"
                },
                {
                    element: "#stepguide-indicadores-focos",
                    intro: "Um conjunto de gráficos que detalham a situação de focos em seu Município"
                }
            ]),
            tooltipPosition: 'auto'
        });

        intro.start();
    });

    $('a[data-step-type="relatorios-rg"]').click(function(){

        $('#stepguideModal').modal('hide');

        $('li.step-relatorios').children('a').trigger('click');
        $('li.step-mapas').children('a').trigger('click');

        var stepsOptions = [];

        if(isAnalista == '0') {
            stepsOptions.push(
                {
                    element: "li.step-reconhecimento",
                    intro: "Após geolocalizar seu Município e cadastrar seus Boletins de RG, você já pode extrair informações diversas do sistema."
                }
            );
        }

        intro.setOptions({
            steps: stepsOptions.concat([
                {
                    element: "li.step-relatorios",
                    intro: "São os relatórios de RG que darão a você a possibilidade de extrair informações analíticas."
                },
                {
                    element: "#stepguide-relatorio-resumo-rg",
                    intro: "Com o resumo de RG você tem acesso à informação mais atualizada sobre imóveis de um bairro."
                },
                {
                    element: "li.step-mapas",
                    intro: "Algumas das ferramentas mais interessantes de focos estão nos mapas. Vamos à elas!"
                },
                {
                    element: "#stepguide-mapa-visao-geral",
                    intro: "No mapa de visão geral você tem uma visão geral sobre os bairros geolocalizados, como também focos, armadilhas, entre outros"
                }
            ]),
            tooltipPosition: 'auto'
        });

        intro.start();
    });
})
