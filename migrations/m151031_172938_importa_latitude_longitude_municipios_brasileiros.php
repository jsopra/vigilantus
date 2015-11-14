<?php

use perspectivain\postgis\postgisTrait;
use yii\db\Expression;
use yii\db\Migration;
use yii\helpers\Inflector;

class m151031_172938_importa_latitude_longitude_municipios_brasileiros extends Migration
{
    use postgisTrait;

    public function safeUp()
    {
        $rows = explode("\n", $this->getCsv());
        array_shift($rows); // tira cabeçalho

        // Corrige municípios que mudaram de nome ou tem o nome incorreto
        $errados = [
            'SP' => [
                'Mogi-Mirim' => 'Mogi Mirim',
                'Ipauçu' => 'Ipaussu',
            ],
            'SC' => [
                'Piçarras' => 'Balneário Piçarras',
            ],
            'RS' => [
                'Chiapeta' => 'Chiapetta',
            ],
            'RN' => [
                'São Miguel de Touros' => 'São Miguel do Gostoso',
            ],
            'PE' => [
                'Itamaracá' => 'Ilha de Itamaracá',
            ],
            'PB' => [
                'São Domingos de Pombal' => 'São Domingos',
            ],
            'MS' => [
                'Bataiporã' => 'Batayporã',
            ],
            'MG' => [
                'Itabirinha de Mantena' => 'Itabirinha',
            ],
            'BA' => [
                'Governador Lomanto Júnior' => 'Barro Preto',
            ],
            'AM' => [
                'namã' => 'Anamã',
            ]
        ];

        foreach ($errados as $uf => $nomes) {
            foreach ($nomes as $errado => $correto) {
                echo "Corrigindo {$errado} - {$uf} para {$correto} - {$uf}\n";
                $command = $this->db->createCommand(
                    '
                        UPDATE municipios SET
                        nome = :correto,
                        slug = :slug
                        WHERE nome = :errado AND sigla_estado = :uf
                    ',
                    [
                        ':correto' => $correto,
                        ':slug' => Inflector::slug($correto . ' ' . $uf),
                        ':errado' => $errado,
                        ':uf' => $uf,
                    ]
                );
                $command->execute();
            }
        }

        $invalidos = [
            'SE' => ['São paulo'],
        ];

        foreach ($invalidos as $uf => $nomes) {
            foreach ($nomes as $nome) {
                echo "Removendo {$nome} - {$uf}\n";
                $command = $this->db->createCommand(
                    'DELETE FROM municipios WHERE nome = :errado AND sigla_estado = :uf',
                    [
                        ':errado' => $nome,
                        ':uf' => $uf,
                    ]
                );
                $command->execute();
            }
        }

        foreach ($rows as $row) {
            $row = explode(';', $row);
            list($nome, $uf, $longitude, $latitude) = $row;

            $nome = str_replace("'", '`', $nome);

            $municipio = $this->findMunicipio($nome, $uf);
            $coordenadas = $this->arrayToWkt('Point', [$longitude, $latitude]);

            if (!$municipio) {
                echo "Inserindo {$nome} - {$uf}\n";
                $this->insert(
                    'municipios',
                    [
                        'nome' => $nome,
                        'sigla_estado' => $uf,
                        'coordenadas_area' => new Expression($coordenadas),
                        'slug' => Inflector::slug($nome . ' ' . $uf),
                    ]
                );
                continue;
            }

            $this->db->createCommand()->update(
                'municipios',
                ['coordenadas_area' => new Expression($coordenadas)],
                'id = ' . $municipio['id']
            )->execute();
        }
    }

    public function safeDown()
    {
        echo "m151031_172938_importa_latitude_longitude_municipios_brasileiros cannot be reverted.\n";
        return false;
    }

    protected function findMunicipio($nome, $uf)
    {
        $command = $this->db->createCommand(
            // "SELECT * FROM municipios WHERE nome = :nome AND sigla_estado = :uf",
            // [':nome' => $nome, ':uf' => $uf]
            "SELECT * FROM municipios WHERE slug = :slug",
            [':slug' => Inflector::slug($nome . ' ' . $uf)]
        );
        $reader = $command->query();
        return $municipio = $reader->read();
    }

    protected function getCsv()
    {
        $csv = <<<EOT
Cidade;UF;Longitude;Latitude
Palmeiras do Tocantins;TO;-47.546429;-6.616584
Barro Preto;BA;-39.476029;-14.794773
Fernando de Noronha;PE;-32.410733;-3.839601
Campo de Santana;PB;-35.636707;-6.487585
Pracuúba;AP;-50.789248;1.745427
Amapá;AP;-50.795653;2.052669
Bujari;AC;-67.955029;-9.815277
Plácido de Castro;AC;-67.137133;-10.280640
Tarauacá;AC;-70.772154;-8.156975
Feijó;AC;-70.350973;-8.170536
Caracaraí;RR;-61.130378;1.827659
Iracema;RR;-61.041546;2.183046
Mucajaí;RR;-60.909619;2.439982
Alto Alegre;RR;-61.307167;2.988580
Amajari;RR;-61.369245;3.645714
Rorainópolis;RR;-60.438923;0.939956
São Luiz;RR;-60.041894;1.010193
São João da Baliza;RR;-59.913293;0.951659
Caroebe;RR;-59.695886;0.884203
Cantá;RR;-60.605827;2.609940
Bonfim;RR;-59.833300;3.361614
Normandia;RR;-59.620402;3.885305
Pacaraima;RR;-61.147695;4.479895
Uiramutã;RR;-60.181464;4.603144
Pedra Branca do Amapari;AP;-51.950306;0.777424
Serra do Navio;AP;-52.003636;0.901357
Porto Grande;AP;-51.415499;0.712430
Ferreira Gomes;AP;-51.179523;0.857256
Itaubal;AP;-50.670828;0.596137
Cutias;AP;-50.799169;0.987565
Tartarugalzinho;AP;-50.908740;1.506519
Oiapoque;AP;-51.833123;3.840740
Calçoene;AP;-50.951233;2.504747
Marechal Thaumaturgo;AC;-72.799704;-8.938983
Jordão;AC;-71.897391;-9.430912
Assis Brasil;AC;-69.573794;-10.929765
Santa Rosa do Purus;AC;-70.490250;-9.446515
Manoel Urbano;AC;-69.267862;-8.832911
Epitaciolândia;AC;-68.734109;-11.018771
Brasiléia;AC;-68.749696;-10.994994
Xapuri;AC;-68.496857;-10.651598
Capixaba;AC;-67.686006;-10.566031
Sena Madureira;AC;-68.671278;-9.063499
Senador Guiomard;AC;-67.736185;-10.149686
Envira;AM;-70.028143;-7.437889
Porto Acre;AC;-67.547815;-9.581378
Boca do Acre;AM;-67.391926;-8.742322
Mâncio Lima;AC;-72.899708;-7.616575
Porto Walter;AC;-72.753747;-8.263233
Rodrigues Alves;AC;-72.661015;-7.738638
Cruzeiro do Sul;AC;-72.675582;-7.627625
Guajará;AM;-72.590713;-7.537971
Ipixuna;AM;-71.693426;-7.047909
Eirunepé;AM;-69.866160;-6.656767
Amaturá;AM;-68.200456;-3.374547
Maçambara;RS;-56.067444;-29.144485
São Borja;RS;-56.003603;-28.657828
Garruchos;RS;-55.638284;-28.194414
Herval;RS;-53.394362;-32.023985
Arroio Grande;RS;-53.086155;-32.232657
Dom Feliciano;RS;-52.102645;-30.700354
Chuvisca;RS;-51.973659;-30.750410
Atalaia do Norte;AM;-70.196700;-4.370551
Benjamin Constant;AM;-70.034157;-4.377680
Tabatinga;AM;-69.938273;-4.241597
Itamarati;AM;-68.260027;-6.424591
São Paulo de Olivença;AM;-68.964581;-3.472919
Santo Antônio do Içá;AM;-67.946279;-3.095443
Tonantins;AM;-67.791904;-2.865825
Chuí;RS;-53.459369;-33.686630
Barra do Quaraí;RS;-57.549722;-30.202859
Uruguaiana;RS;-57.085287;-29.761436
Quaraí;RS;-56.448254;-30.384033
Alegrete;RS;-55.794862;-29.790166
Santana do Livramento;RS;-55.531467;-30.887720
Dom Pedrito;RS;-54.669360;-30.975590
Manoel Viana;RS;-55.484112;-29.585935
São Francisco de Assis;RS;-55.125310;-29.554689
Rosário do Sul;RS;-54.922106;-30.251483
Cacequi;RS;-54.821998;-29.888295
Nova Esperança do Sul;RS;-54.829302;-29.406643
São Vicente do Sul;RS;-54.682643;-29.688161
Jaguari;RS;-54.702993;-29.493559
Itaqui;RS;-56.551478;-29.131051
Pinheiro Machado;RS;-53.379789;-31.579440
Pedro Osório;RS;-52.818389;-31.864243
Cerrito;RS;-52.800442;-31.841917
Capão do Leão;RS;-52.488879;-31.756460
Morro Redondo;RS;-52.626064;-31.588737
Bagé;RS;-54.099920;-31.329650
Hulha Negra;RS;-53.866702;-31.406721
Unistalda;RS;-55.151731;-29.039953
Itacurubi;RS;-55.244670;-28.791281
Santo Antônio das Missões;RS;-55.225077;-28.513977
Santiago;RS;-54.866624;-29.189675
Bossoroca;RS;-54.903507;-28.729089
São Luiz Gonzaga;RS;-54.955942;-28.412032
São Miguel das Missões;RS;-54.555879;-28.556044
São Nicolau;RS;-55.265441;-28.183365
Dezesseis de Novembro;RS;-55.061709;-28.219036
Pirapó;RS;-55.200130;-28.043888
Porto Xavier;RS;-55.137910;-27.908182
Roque Gonzales;RS;-55.026609;-28.129681
São Pedro do Butiá;RS;-54.892603;-28.124250
Salvador das Missões;RS;-54.837318;-28.123337
São Paulo das Missões;RS;-54.940352;-28.019546
Porto Lucena;RS;-55.010028;-27.856880
Campina das Missões;RS;-54.841648;-27.988833
Sério;RS;-52.268500;-29.390428
Mato Leitão;RS;-52.127760;-29.528474
Santa Clara do Sul;RS;-52.084260;-29.474740
Cruzeiro do Sul;RS;-51.992778;-29.514835
Bom Retiro do Sul;RS;-51.945593;-29.607076
Estrela;RS;-51.949508;-29.500207
Lajeado;RS;-51.964427;-29.459086
Arroio do Meio;RS;-51.955735;-29.401353
Barão do Triunfo;RS;-51.738441;-30.389072
Arroio dos Ratos;RS;-51.727479;-30.087545
São Jerônimo;RS;-51.725070;-29.971580
General Câmara;RS;-51.761178;-29.903173
Triunfo;RS;-51.707471;-29.929129
Mariana Pimentel;RS;-51.580307;-30.353048
Eldorado do Sul;RS;-51.618702;-30.084676
Charqueadas;RS;-51.628872;-29.962478
Taquari;RS;-51.865346;-29.794330
Tabaí;RS;-51.682292;-29.643041
Fazenda Vilanova;RS;-51.821714;-29.588511
Colinas;RS;-51.855564;-29.394781
Teutônia;RS;-51.804375;-29.448205
Paverama;RS;-51.733925;-29.548554
Poço das Antas;RS;-51.671938;-29.448097
Montenegro;RS;-51.467903;-29.682414
Pareci Novo;RS;-51.397400;-29.636538
Brochier;RS;-51.594516;-29.550136
Maratá;RS;-51.557320;-29.545715
Mundo Novo;MS;-54.281012;-23.935540
Eldorado;MS;-54.283775;-23.786838
Guaíra;PR;-54.257327;-24.085005
Itaquiraí;MS;-54.186971;-23.477868
Altônia;PR;-53.895809;-23.875916
Francisco Alves;PR;-53.846124;-24.066725
São Jorge do Patrocínio;PR;-53.882275;-23.764744
Esperança Nova;PR;-53.810979;-23.723782
Sales Oliveira;SP;-47.836873;-20.769612
Glória de Dourados;MS;-54.233502;-22.413628
Deodápolis;MS;-54.168163;-22.276272
Novo Horizonte do Sul;MS;-53.860148;-22.669322
Ivinhema;MS;-53.818414;-22.304571
Angélica;MS;-53.770789;-22.152658
Nova Alvorada do Sul;MS;-54.382491;-21.465688
Santa Cruz de Monte Castelo;PR;-53.296342;-22.954225
São Pedro do Paraná;PR;-53.224141;-22.823882
Porto Rico;PR;-53.267731;-22.774742
São João do Pau d'Alho;SP;-51.667172;-21.266249
Dracena;SP;-51.535034;-21.484311
Tupi Paulista;SP;-51.575004;-21.382455
Monte Castelo;SP;-51.567889;-21.298092
Nova Independência;SP;-51.490531;-21.102630
Bela Vista do Paraíso;PR;-51.192657;-22.993653
Porecatu;PR;-51.379490;-22.753724
Alvorada do Sul;PR;-51.229709;-22.781282
Nantes;SP;-51.239977;-22.615559
Jaraguari;MS;-54.399626;-20.138615
Bandeirantes;MS;-54.358458;-19.927490
Ribas do Rio Pardo;MS;-53.758819;-20.444533
Camapuã;MS;-54.043065;-19.534672
Água Clara;MS;-52.878990;-20.445245
Alcinópolis;MS;-53.704223;-18.325546
Itiquira;MT;-54.142214;-17.214693
Alto Garças;MT;-53.527209;-16.946226
Costa Rica;MS;-53.128732;-18.543202
Cerro Largo;RS;-54.742791;-28.146251
Caibaté;RS;-54.645407;-28.290475
Guarani das Missões;RS;-54.562937;-28.149117
Cândido Godói;RS;-54.751722;-27.951509
Ubiretama;RS;-54.685952;-28.040385
Santo Cristo;RS;-54.661963;-27.826259
Senador Salgado Filho;RS;-54.550717;-28.025007
Porto Vera Cruz;RS;-54.899370;-27.740504
Alecrim;RS;-54.764849;-27.657856
Porto Mauá;RS;-54.665739;-27.579579
Foz do Iguaçu;PR;-54.582689;-25.542748
Candiota;RS;-53.677275;-31.551553
Santa Vitória do Palmar;RS;-53.371692;-33.524980
Jaguarão;RS;-53.377027;-32.560378
Lavras do Sul;RS;-53.893077;-30.807056
Caçapava do Sul;RS;-53.482684;-30.514360
São Gabriel;RS;-54.321657;-30.333688
Mata;RS;-54.464105;-29.564893
Dilermando de Aguiar;RS;-54.212241;-29.705393
São Pedro do Sul;RS;-54.185516;-29.620163
Toropi;RS;-54.224398;-29.478246
Vila Nova do Sul;RS;-53.876041;-30.346131
São Sepé;RS;-53.560296;-30.164289
Formigueiro;RS;-53.495928;-30.003518
Santa Maria;RS;-53.814946;-29.686817
São Martinho da Serra;RS;-53.859002;-29.539667
Itaara;RS;-53.772494;-29.601285
Silveira Martins;RS;-53.591018;-29.646741
Ivorá;RS;-53.584214;-29.523224
Piratini;RS;-53.097296;-31.447291
Santana da Boa Vista;RS;-53.109981;-30.869738
Canguçu;RS;-52.678254;-31.395980
Encruzilhada do Sul;RS;-52.520381;-30.542998
Restinga Seca;RS;-53.380657;-29.818778
Agudo;RS;-53.251458;-29.644743
Faxinal do Soturno;RS;-53.448390;-29.578836
São João do Polêsine;RS;-53.443920;-29.619435
Dona Francisca;RS;-53.361737;-29.619507
Nova Palma;RS;-53.468924;-29.470981
Nicolau Vergueiro;RS;-52.467629;-28.529842
Palmeira das Missões;RS;-53.313378;-27.900652
Chapada;RS;-53.066519;-28.055865
Novo Barreiro;RS;-53.110292;-27.907700
Barra Funda;RS;-53.039067;-27.920467
Nova Boa Vista;RS;-52.978377;-27.992600
Boa Vista das Missões;RS;-53.310168;-27.667123
Jaboticaba;RS;-53.276173;-27.634703
Taquaruçu do Sul;RS;-53.470250;-27.400452
Seberi;RS;-53.402634;-27.482861
Caiçara;RS;-53.425670;-27.279121
Frederico Westphalen;RS;-53.395824;-27.358644
Cristal do Sul;RS;-53.242227;-27.451995
Lajeado do Bugre;RS;-53.181811;-27.691310
São José das Missões;RS;-53.122595;-27.778908
Sagrada Família;RS;-53.135053;-27.708538
Cerro Grande;RS;-53.167233;-27.610621
Novo Tiradentes;RS;-53.183715;-27.564918
Constantina;RS;-52.993799;-27.731974
Liberato Salzano;RS;-53.075330;-27.600959
Centenário;RS;-51.998408;-27.761547
Carlos Gomes;RS;-51.912127;-27.716679
Viadutos;RS;-52.021144;-27.571566
Três Arroios;RS;-52.144753;-27.500273
Severiano de Almeida;RS;-52.121704;-27.436202
Mariano Moro;RS;-52.146661;-27.356753
Marcelino Ramos;RS;-51.909503;-27.467622
Ibiaçá;RS;-51.859851;-28.056556
Caseiros;RS;-51.686108;-28.258160
Sananduva;RS;-51.807949;-27.947028
São João da Urtiga;RS;-51.825664;-27.819507
Santo Expedito do Sul;RS;-51.643352;-27.907439
Lagoa Vermelha;RS;-51.524815;-28.209335
Rolante;RS;-50.581937;-29.646240
Caraá;RS;-50.431614;-29.786933
Riozinho;RS;-50.448781;-29.638986
São Francisco de Paula;RS;-50.582796;-29.440366
Jari;RS;-54.223711;-29.292234
Quevedos;RS;-54.078915;-29.350413
Vitória das Missões;RS;-54.504022;-28.351612
Entre-Ijuís;RS;-54.268588;-28.368556
Jóia;RS;-54.114131;-28.643484
Eugênio de Castro;RS;-54.150590;-28.531467
Coronel Barros;RS;-54.068621;-28.392148
Dois Vizinhos;PR;-53.057016;-25.740694
Nova Prata do Iguaçu;PR;-53.346862;-25.630908
Boa Vista da Aparecida;PR;-53.411694;-25.430755
Boa Esperança do Iguaçu;PR;-53.210818;-25.632435
Cruzeiro do Iguaçu;PR;-53.128484;-25.619214
Três Barras do Paraná;PR;-53.183350;-25.418517
Catanduvas;PR;-53.154826;-25.204356
Bom Sucesso do Sul;PR;-52.835297;-26.073113
Itapejara d'Oeste;PR;-52.813823;-25.962030
Verê;PR;-52.905108;-25.877201
São Jorge d'Oeste;PR;-52.913092;-25.699002
São João;PR;-52.725240;-25.821375
Sulina;PR;-52.729869;-25.706553
Coronel Vivida;PR;-52.564054;-25.976686
Saudade do Iguaçu;PR;-52.618391;-25.691686
Chopinzinho;PR;-52.517293;-25.851546
Quedas do Iguaçu;PR;-52.910231;-25.449161
Espigão Alto do Iguaçu;PR;-52.834798;-25.421635
Rio Bonito do Iguaçu;PR;-52.529206;-25.487384
Nova Laranjeiras;PR;-52.544682;-25.305376
Boqueirão do Leão;RS;-52.428432;-29.304620
Progresso;RS;-52.319721;-29.244147
Caçador;SC;-51.012004;-26.775670
General Carneiro;PR;-51.317224;-26.424974
Matos Costa;SC;-51.150095;-26.470870
Calmon;SC;-51.095017;-26.594241
União da Vitória;PR;-51.087313;-26.227319
Porto União;SC;-51.075858;-26.245075
Frei Rogério;SC;-50.807621;-27.175021
Lebon Régis;SC;-50.692109;-26.927987
Ponte Alta do Norte;SC;-50.465947;-27.159112
Santa Cecília;SC;-50.425170;-26.959182
Timbó Grande;SC;-50.660720;-26.612671
Nioaque;MS;-55.829618;-21.141882
Laguna Carapã;MS;-55.150210;-22.544818
Caarapó;MS;-54.820928;-22.636764
Juti;MS;-54.606120;-22.859637
Dourados;MS;-54.812043;-22.223099
Itaporã;MS;-54.793381;-22.080035
Douradina;MS;-54.615756;-22.040494
Maracaju;MS;-55.167763;-21.610544
Rio Brilhante;MS;-54.542744;-21.803258
Bodoquena;MS;-56.712716;-20.536983
Corumbá;MS;-57.651036;-19.007727
Ladário;MS;-57.597266;-19.008894
Miranda;MS;-56.374594;-20.235508
Anastácio;MS;-55.810353;-20.482292
Aquidauana;MS;-55.786788;-20.466552
Dois Irmãos do Buriti;MS;-55.291453;-20.684818
Sidrolândia;MS;-54.969219;-20.930212
Terenos;MS;-54.864742;-20.437845
Rochedo;MS;-54.884769;-19.956488
Corguinho;MS;-54.828074;-19.824281
Rio Negro;MS;-54.985873;-19.447036
Rio Verde de Mato Grosso;MS;-54.843357;-18.924945
São Gabriel do Oeste;MS;-54.550668;-19.388858
Tupanci do Sul;RS;-51.538283;-27.924085
Maximiliano de Almeida;RS;-51.802049;-27.632518
Paim Filho;RS;-51.762988;-27.707516
Cacique Doble;RS;-51.659671;-27.767044
Machadinho;RS;-51.666805;-27.566669
Alto Bela Vista;SC;-51.904435;-27.433346
Ipira;SC;-51.775790;-27.403818
Peritiba;SC;-51.901793;-27.375449
Piratuba;SC;-51.766834;-27.424228
São José do Ouro;RS;-51.596620;-27.770679
Barracão;RS;-51.458550;-27.673862
Zortéa;SC;-51.551993;-27.452065
Ouro;SC;-51.619373;-27.337905
Capinzal;SC;-51.605702;-27.347295
Erval Velho;SC;-51.442990;-27.274321
Farroupilha;RS;-51.341853;-29.222689
Caxias do Sul;RS;-51.179161;-29.162905
Presidente Prudente;SP;-51.392526;-22.120654
Bataguassu;MS;-52.422058;-21.715899
Presidente Epitácio;SP;-52.111140;-21.765074
Caiuá;SP;-51.996918;-21.832164
Junqueirópolis;SP;-51.434187;-21.510348
Panorama;SP;-51.856185;-21.353973
Paulicéia;SP;-51.832098;-21.315262
Ouro Verde;SP;-51.702398;-21.487150
Santa Mercedes;SP;-51.756353;-21.349521
Nova Guataporanga;SP;-51.644655;-21.331968
Paraíso do Sul;RS;-53.144034;-29.671705
Ibarama;RS;-53.129493;-29.420321
Sobradinho;RS;-53.032625;-29.419439
Passa Sete;RS;-52.959945;-29.457667
Cachoeira do Sul;RS;-52.892756;-30.032988
Novo Cabrais;RS;-52.948940;-29.733752
Cerro Branco;RS;-52.940610;-29.656986
Candelária;RS;-52.789495;-29.668391
Vera Cruz;RS;-52.515176;-29.718435
Santa Cruz do Sul;RS;-52.434318;-29.722021
Vale do Sol;RS;-52.683901;-29.596724
Herveiras;RS;-52.655314;-29.455239
Sinimbu;RS;-52.530449;-29.535659
Rio Grande;RS;-52.107050;-32.034875
São José do Norte;RS;-52.033085;-32.015129
Pelotas;RS;-52.337058;-31.764898
Turuçu;RS;-52.170590;-31.417284
São Lourenço do Sul;RS;-51.971531;-31.356374
Cristal;RS;-52.043618;-31.004634
Amaral Ferrador;RS;-52.250951;-30.875579
Camaquã;RS;-51.804310;-30.848897
Saldanha Marinho;RS;-53.097006;-28.394081
Colorado;RS;-52.992820;-28.525842
Lagoão;RS;-52.799698;-29.234796
Palma Sola;SC;-53.277091;-26.347069
Flor da Serra do Sul;PR;-53.309213;-26.252326
Tigrinhos;SC;-53.154529;-26.687610
Santa Terezinha do Progresso;SC;-53.199716;-26.624030
Bom Jesus do Oeste;SC;-53.096725;-26.692737
Serra Alta;SC;-53.040870;-26.722898
Sul Brasil;SC;-52.963965;-26.735148
Saltinho;SC;-53.057763;-26.604932
Campo Erê;SC;-53.085614;-26.393056
Nova Prata;RS;-51.611271;-28.779913
André da Rocha;RS;-51.579729;-28.628294
Protásio Alves;RS;-51.475700;-28.757189
Ibiraiaras;RS;-51.637670;-28.374095
Santa Mônica;PR;-53.110297;-23.108005
Tapira;PR;-53.068446;-23.319343
Tuneiras do Oeste;PR;-52.876874;-23.864798
Tapejara;PR;-52.873487;-23.731462
Farol;PR;-52.621693;-24.095842
Cerro Grande do Sul;RS;-51.741846;-30.590472
Arambaré;RS;-51.504586;-30.909248
Sentinela do Sul;RS;-51.586154;-30.610707
Sertão Santana;RS;-51.601694;-30.456183
Tapes;RS;-51.399140;-30.668308
Pantano Grande;RS;-52.372915;-30.190155
Rio Pardo;RS;-52.371130;-29.988030
Minas do Leão;RS;-52.042257;-30.134576
Butiá;RS;-51.960057;-30.117920
Vale Verde;RS;-52.185747;-29.786445
Passo do Sobrado;RS;-52.274758;-29.748041
Venâncio Aires;RS;-52.193159;-29.614306
São Pedro da Serra;RS;-51.513381;-29.419320
Harmonia;RS;-51.418502;-29.545556
Salvador do Sul;RS;-51.507699;-29.438561
Barão;RS;-51.494908;-29.372474
Tupandi;RS;-51.417358;-29.477185
Tavares;RS;-51.088040;-31.284266
Mostardas;RS;-50.916692;-31.105380
Barra do Ribeiro;RS;-51.301358;-30.293884
Guaíba;RS;-51.323314;-30.108592
Canoas;RS;-51.185681;-29.912758
Viamão;RS;-51.019435;-30.081899
Alvorada;RS;-51.080857;-29.991400
Cachoeirinha;RS;-51.101606;-29.947222
Gravataí;RS;-50.986891;-29.941319
Nova Santa Rita;RS;-51.283736;-29.852474
Capela de Santana;RS;-51.327998;-29.696088
Esteio;RS;-51.184065;-29.851963
Sapucaia do Sul;RS;-51.144975;-29.827575
São Leopoldo;RS;-51.149773;-29.754494
Portão;RS;-51.242895;-29.701533
Estância Velha;RS;-51.184339;-29.653540
Novo Hamburgo;RS;-51.132828;-29.687548
São Sebastião do Caí;RS;-51.374880;-29.588517
Bom Princípio;RS;-51.354763;-29.485634
São Vendelino;RS;-51.367515;-29.372873
Feliz;RS;-51.303186;-29.452703
Alto Feliz;RS;-51.312278;-29.391928
Vale Real;RS;-51.255859;-29.391944
Lindolfo Collor;RS;-51.214112;-29.585906
São José do Hortêncio;RS;-51.245048;-29.527962
Ivoti;RS;-51.153326;-29.599463
Presidente Lucena;RS;-51.179798;-29.517549
Linha Nova;RS;-51.200297;-29.467929
Picada Café;RS;-51.136725;-29.446421
Campo Bom;RS;-51.060601;-29.674694
Sapiranga;RS;-51.006446;-29.634885
Dois Irmãos;RS;-51.089776;-29.583560
Morro Reuter;RS;-51.081119;-29.537934
Nova Petrópolis;RS;-51.113552;-29.374112
Santa Maria do Herval;RS;-50.991917;-29.490163
Araricá;RS;-50.929128;-29.616832
Nova Hartz;RS;-50.905108;-29.580752
Gramado;RS;-50.876164;-29.373351
Palmares do Sul;RS;-50.510297;-30.253485
Capivari do Sul;RS;-50.515152;-30.138251
Glorinha;RS;-50.773363;-29.879798
Parobé;RS;-50.831180;-29.624257
Taquara;RS;-50.775278;-29.650471
Igrejinha;RS;-50.791894;-29.569318
Três Coroas;RS;-50.773869;-29.513719
Santo Antônio da Patrulha;RS;-50.517489;-29.826768
Tupanciretã;RS;-53.844469;-29.085775
Júlio de Castilhos;RS;-53.677168;-29.229855
Augusto Pestana;RS;-53.988292;-28.517249
Ijuí;RS;-53.919960;-28.388048
Cruz Alta;RS;-53.604831;-28.645001
Pejuçara;RS;-53.657854;-28.428256
Sete de Setembro;RS;-54.463682;-28.136169
Santo Ângelo;RS;-54.266781;-28.300128
Santa Rosa;RS;-54.479629;-27.870200
Giruá;RS;-54.351680;-28.029664
Catuípe;RS;-54.013215;-28.255356
Independência;RS;-54.188640;-27.835411
Inhacorá;RS;-54.014978;-27.875187
Alegria;RS;-54.055732;-27.834467
Tuparendi;RS;-54.481434;-27.759768
Tucunduva;RS;-54.443880;-27.657292
Novo Machado;RS;-54.503592;-27.576473
Horizontina;RS;-54.305336;-27.628238
Doutor Maurício Cardoso;RS;-54.357746;-27.510309
Três de Maio;RS;-54.235709;-27.780037
São José do Inhacorá;RS;-54.127462;-27.725055
Boa Vista do Buricá;RS;-54.108229;-27.669280
Nova Candelária;RS;-54.107401;-27.613677
Crissiumal;RS;-54.099372;-27.499890
Tiradentes do Sul;RS;-54.081367;-27.402229
Ajuricaba;RS;-53.775668;-28.234170
Chiapetta;RS;-53.941871;-27.923017
São Valério do Sul;RS;-53.936771;-27.790639
Santo Augusto;RS;-53.777605;-27.852577
Nova Ramada;RS;-53.699168;-28.066731
Panambi;RS;-53.502261;-28.283328
Condor;RS;-53.490545;-28.207533
São Martinho;RS;-53.969899;-27.711169
Sede Nova;RS;-53.949290;-27.636739
Humaitá;RS;-53.969525;-27.569065
Bom Progresso;RS;-53.871608;-27.539856
Campo Novo;RS;-53.805209;-27.679200
Braga;RS;-53.740514;-27.617348
Três Passos;RS;-53.929631;-27.455471
Esperança do Sul;RS;-53.989081;-27.360276
Derrubadas;RS;-53.864535;-27.264151
Tenente Portela;RS;-53.758484;-27.371058
Coronel Bicaco;RS;-53.702164;-27.719699
Redentora;RS;-53.640668;-27.663954
Dois Irmãos das Missões;RS;-53.530413;-27.662103
Erval Seco;RS;-53.500501;-27.544314
Miraguaí;RS;-53.689078;-27.496970
Vista Gaúcha;RS;-53.697382;-27.290217
Palmitinho;RS;-53.558029;-27.359574
Vista Alegre;RS;-53.491876;-27.368619
Pinhal Grande;RS;-53.320649;-29.345000
Arroio do Tigre;RS;-53.096627;-29.334829
Estrela Velha;RS;-53.163870;-29.171269
Segredo;RS;-52.976673;-29.352337
Tunas;RS;-52.953843;-29.103944
Salto do Jacuí;RS;-53.213326;-29.095065
Campos Borges;RS;-53.000812;-28.887148
Fortaleza dos Valos;RS;-53.224906;-28.798596
Santa Bárbara do Sul;RS;-53.251041;-28.365330
Quinze de Novembro;RS;-53.101096;-28.746552
Ibirubá;RS;-53.096124;-28.630161
Alto Alegre;RS;-52.989255;-28.776865
Gramado Xavier;RS;-52.579491;-29.270571
Barros Cassal;RS;-52.583571;-29.094686
Espumoso;RS;-52.846080;-28.728633
Selbach;RS;-52.949768;-28.629435
Tapera;RS;-52.861253;-28.627729
Mormaço;RS;-52.699916;-28.696814
Lagoa dos Três Cantos;RS;-52.861848;-28.567611
Não-Me-Toque;RS;-52.818156;-28.454776
Victor Graeff;RS;-52.749535;-28.563163
Santo Antônio do Planalto;RS;-52.699233;-28.403046
Soledade;RS;-52.513083;-28.830613
Ibirapuitã;RS;-52.515796;-28.624707
Ernestina;RS;-52.583566;-28.497674
Pinhal;RS;-53.208185;-27.508022
Rodeio Bonito;RS;-53.170583;-27.474203
Ametista do Sul;RS;-53.183005;-27.360656
Planalto;RS;-53.057530;-27.329695
Carazinho;RS;-52.793260;-28.295844
Coqueiros do Sul;RS;-52.784221;-28.119421
Sarandi;RS;-52.923085;-27.942000
Rondinha;RS;-52.908107;-27.831451
Pontão;RS;-52.679091;-28.058478
Engenho Velho;RS;-52.914540;-27.706026
Três Palmeiras;RS;-52.843718;-27.613889
Ronda Alta;RS;-52.805587;-27.775799
Entre Rios do Sul;RS;-52.734708;-27.529836
Trindade do Sul;RS;-52.895593;-27.523896
Gramado dos Loureiros;RS;-52.914861;-27.442895
Rio dos Índios;RS;-52.841749;-27.297280
Nonoai;RS;-52.775564;-27.368918
Campinas do Sul;RS;-52.624808;-27.717426
Jacutinga;RS;-52.537240;-27.729135
Ponte Preta;RS;-52.484817;-27.658692
São Valentim;RS;-52.523714;-27.558284
Faxinalzinho;RS;-52.678917;-27.423770
Benjamin Constant do Sul;RS;-52.599504;-27.508565
Erval Grande;RS;-52.574036;-27.392599
Itatiba do Sul;RS;-52.453776;-27.384631
Barra do Guarita;RS;-53.710867;-27.192672
Itapiranga;SC;-53.716603;-27.165856
Pinheirinho do Vale;RS;-53.608017;-27.210886
São João do Oeste;SC;-53.597657;-27.098353
Tunápolis;SC;-53.641725;-26.968103
Santa Helena;SC;-53.621439;-26.936966
Bandeirante;SC;-53.641326;-26.770499
Iporã do Oeste;SC;-53.535483;-26.985396
Belmonte;SC;-53.575810;-26.842994
Descanso;SC;-53.503395;-26.826971
Paraíso;SC;-53.671595;-26.620024
São Miguel do Oeste;SC;-53.516259;-26.724224
Guaraciaba;SC;-53.524317;-26.604199
Dionísio Cerqueira;SC;-53.635149;-26.264784
Barracão;PR;-53.632385;-26.250230
Princesa;SC;-53.599427;-26.444120
São José do Cedro;SC;-53.495456;-26.456135
Guarujá do Sul;SC;-53.529575;-26.385813
Santa Terezinha de Itaipu;PR;-54.400725;-25.434417
São Miguel do Iguaçu;PR;-54.240498;-25.349171
Medianeira;PR;-54.094308;-25.297665
Serranópolis do Iguaçu;PR;-54.051822;-25.379915
Pranchita;PR;-53.741112;-26.021968
Pérola d'Oeste;PR;-53.744742;-25.820958
Planalto;PR;-53.764234;-25.721135
Santo Antônio do Sudoeste;PR;-53.725100;-26.073708
Bom Jesus do Sul;PR;-53.595493;-26.195802
Pinhal de São Bento;PR;-53.481998;-26.032422
Bela Vista da Caroba;PR;-53.672505;-25.884179
Santa Izabel do Oeste;PR;-53.480146;-25.821652
Realeza;PR;-53.526030;-25.771093
Capanema;PR;-53.805508;-25.669099
Matelândia;PR;-53.993492;-25.249571
Capitão Leônidas Marques;PR;-53.611238;-25.481645
Santa Lúcia;PR;-53.563796;-25.410355
Lindoeste;PR;-53.573298;-25.259616
Vicente Dutra;RS;-53.402211;-27.160749
Mondaí;SC;-53.403177;-27.100785
Iraí;RS;-53.254322;-27.195070
Riqueza;SC;-53.326452;-27.065325
Caibi;SC;-53.245751;-27.074102
Flor do Sertão;SC;-53.350522;-26.781143
Iraceminha;SC;-53.276682;-26.821536
Palmitos;SC;-53.158612;-27.070219
Alpestre;RS;-53.034127;-27.250159
São Carlos;SC;-53.003704;-27.079757
Águas de Chapecó;SC;-52.980844;-27.075424
Cunha Porã;SC;-53.166185;-26.895019
Cunhataí;SC;-53.089474;-26.970924
Maravilha;SC;-53.173699;-26.766529
Saudades;SC;-53.002130;-26.931677
Modelo;SC;-53.039956;-26.772924
Pinhalzinho;SC;-52.991268;-26.849546
Barra Bonita;SC;-53.440000;-26.654000
Romelândia;SC;-53.317193;-26.680888
São Miguel da Boa Vista;SC;-53.251107;-26.686961
Anchieta;SC;-53.331908;-26.538216
São Bernardino;SC;-52.968669;-26.473926
Caxambu do Sul;SC;-52.880718;-27.162401
Planalto Alegre;SC;-52.866990;-27.070423
Guatambú;SC;-52.788650;-27.134102
Nova Erechim;SC;-52.906569;-26.898159
Águas Frias;SC;-52.856773;-26.879377
União do Oeste;SC;-52.854123;-26.762040
Nova Itaberaba;SC;-52.814076;-26.942805
Coronel Freitas;SC;-52.701122;-26.905713
Chapecó;SC;-52.615190;-27.100448
Paial;SC;-52.497517;-27.254105
Arvoredo;SC;-52.454260;-27.074816
Cordilheira Alta;SC;-52.605582;-26.984403
Marema;SC;-52.626445;-26.802361
Lajeado Grande;SC;-52.564782;-26.857614
Xaxim;SC;-52.537446;-26.959643
Irati;SC;-52.895513;-26.653862
Jardinópolis;SC;-52.862524;-26.719136
Formosa do Sul;SC;-52.794636;-26.645272
Quilombo;SC;-52.723960;-26.726432
Novo Horizonte;SC;-52.828104;-26.444218
São Lourenço do Oeste;SC;-52.849837;-26.355660
Jupiá;SC;-52.729766;-26.395038
Vitorino;PR;-52.784266;-26.268282
Santiago do Sul;SC;-52.679852;-26.638784
Coronel Martins;SC;-52.669361;-26.511043
Entre Rios;SC;-52.558504;-26.722474
Ipuaçu;SC;-52.456633;-26.632025
São Domingos;SC;-52.531333;-26.554811
Galvão;SC;-52.687528;-26.454934
Pato Branco;PR;-52.670630;-26.229237
Mariópolis;PR;-52.549736;-26.351072
Salgado Filho;PR;-53.363116;-26.177664
Manfrinópolis;PR;-53.315257;-26.147968
Ampére;PR;-53.468640;-25.916791
Nova Esperança do Sudoeste;PR;-53.265240;-25.906070
Salto do Lontra;PR;-53.313528;-25.781257
Marmeleiro;PR;-53.026741;-26.147200
Renascença;PR;-52.970349;-26.158837
Francisco Beltrão;PR;-53.053466;-26.081677
Enéas Marques;PR;-53.165860;-25.944525
Irineópolis;SC;-50.795687;-26.242006
Bela Vista do Toldo;SC;-50.466354;-26.274625
Cruz Machado;PR;-51.342965;-26.016640
Porto Vitória;PR;-51.230976;-26.167404
Paula Freitas;PR;-50.930983;-26.210513
Inácio Martins;PR;-51.076925;-25.570395
Prudentópolis;PR;-50.975396;-25.211083
Paulo Frontin;PR;-50.830423;-26.046565
Mallet;PR;-50.817346;-25.880588
Rio Azul;PR;-50.798543;-25.730580
Canoinhas;SC;-50.395001;-26.176591
São Mateus do Sul;PR;-50.383985;-25.867655
Rebouças;PR;-50.687727;-25.623198
Irati;PR;-50.649287;-25.469663
Guamiranga;PR;-50.802109;-25.191167
Imbituva;PR;-50.598856;-25.228538
Fernandes Pinheiro;PR;-50.545601;-25.410742
Teixeira Soares;PR;-50.457072;-25.370100
Porto Murtinho;MS;-57.883586;-21.698078
Caracol;MS;-57.027672;-22.011017
Paranhos;MS;-55.429018;-23.891067
Coronel Sapucaia;MS;-55.527811;-23.272407
Amambaí;MS;-55.225327;-23.105760
Sete Quedas;MS;-55.039840;-23.970508
Tacuru;MS;-55.014137;-23.635954
Iguatemi;MS;-54.563731;-23.673555
Bela Vista;MS;-56.526288;-22.107327
Aral Moreira;MS;-55.633451;-22.938467
Antônio João;MS;-55.951681;-22.192701
Ponta Porã;MS;-55.720326;-22.529617
Bonito;MS;-56.483565;-21.126096
Jardim;MS;-56.148902;-21.479941
Guia Lopes da Laguna;MS;-56.111711;-21.458318
Pouso Novo;RS;-52.213588;-29.173793
Fontoura Xavier;RS;-52.344455;-28.981655
São José do Herval;RS;-52.294991;-29.052035
Arvorezinha;RS;-52.178080;-28.873690
Marques de Souza;RS;-52.097268;-29.331113
Travesseiro;RS;-52.053228;-29.297662
Relvado;RS;-52.077831;-29.116376
Capitão;RS;-51.985280;-29.267386
Nova Bréscia;RS;-52.031862;-29.218186
Putinga;RS;-52.156904;-29.004494
Ilópolis;RS;-52.125816;-28.928250
Doutor Ricardo;RS;-51.997155;-29.083957
Anta Gorda;RS;-52.010191;-28.969772
Itapuca;RS;-52.169283;-28.776844
Camargo;RS;-52.200263;-28.587979
Marau;RS;-52.198625;-28.449754
Nova Alvorada;RS;-52.163080;-28.682231
Montauri;RS;-52.076728;-28.646161
União da Serra;RS;-52.023783;-28.783285
Serafina Corrêa;RS;-51.935250;-28.712621
Vila Maria;RS;-52.148648;-28.535853
Casca;RS;-51.981452;-28.560516
Santo Antônio do Palma;RS;-52.026701;-28.495554
Gentil;RS;-52.033695;-28.431641
Roca Sales;RS;-51.865840;-29.288398
Encantado;RS;-51.870282;-29.235141
Imigrante;RS;-51.774752;-29.350776
Muçum;RS;-51.871367;-29.163002
Boa Vista do Sul;RS;-51.668725;-29.354406
Santa Tereza;RS;-51.735066;-29.165467
Vespasiano Correa;RS;-51.862495;-29.065506
Dois Lajeados;RS;-51.839584;-28.983006
Guaporé;RS;-51.889542;-28.839917
São Valentim do Sul;RS;-51.768356;-29.045056
Cotiporã;RS;-51.697067;-28.989106
Fagundes Varela;RS;-51.701425;-28.879386
Garibaldi;RS;-51.535179;-29.258979
Monte Belo do Sul;RS;-51.633263;-29.160740
Bento Gonçalves;RS;-51.516476;-29.166212
Carlos Barbosa;RS;-51.502847;-29.296949
Veranópolis;RS;-51.551614;-28.931197
Vila Flores;RS;-51.550371;-28.859825
Nova Roma do Sul;RS;-51.409511;-28.988173
Vista Alegre do Prata;RS;-51.794650;-28.805218
Paraí;RS;-51.789602;-28.596384
Nova Bassano;RS;-51.707194;-28.729088
Nova Araçá;RS;-51.745802;-28.653741
São Domingos do Sul;RS;-51.885980;-28.531151
Vanini;RS;-51.844723;-28.475833
David Canabarro;RS;-51.848205;-28.384907
Ciríaco;RS;-51.874143;-28.341913
Guabiju;RS;-51.694830;-28.542083
São Jorge;RS;-51.706422;-28.498445
Muliterno;RS;-51.769675;-28.325299
Passo Fundo;RS;-52.409112;-28.257564
Coxilha;RS;-52.302343;-28.127970
Mato Castelhano;RS;-52.193231;-28.280019
Ipiranga do Sul;RS;-52.427124;-27.940366
Erebango;RS;-52.300486;-27.854386
Sertão;RS;-52.258780;-27.979782
Estação;RS;-52.263536;-27.913468
Getúlio Vargas;RS;-52.229357;-27.891093
Vila Lângaro;RS;-52.143795;-28.106184
Água Santa;RS;-52.031004;-28.167228
Tapejara;RS;-52.009665;-28.065237
Floriano Peixoto;RS;-52.083775;-27.861391
Charrua;RS;-52.015005;-27.949268
Barão de Cotegipe;RS;-52.379820;-27.620785
Erechim;RS;-52.269690;-27.636380
Barra do Rio Azul;RS;-52.408375;-27.406855
Itá;SC;-52.321187;-27.290666
Aratiba;RS;-52.297461;-27.397787
Áurea;RS;-52.050506;-27.693586
Gaurama;RS;-52.091529;-27.585587
Nova Pádua;RS;-51.309752;-29.027509
Antônio Prado;RS;-51.288263;-28.856544
Flores da Cunha;RS;-51.187457;-29.026147
São Marcos;RS;-51.069559;-28.967659
Ipê;RS;-51.285931;-28.817098
Muitos Capões;RS;-51.183593;-28.313183
Campestre da Serra;RS;-51.094148;-28.792585
Vacaria;RS;-50.941791;-28.507868
Canela;RS;-50.811921;-29.355993
Jaquirana;RS;-50.363681;-28.881098
Monte Alegre dos Campos;RS;-50.783385;-28.680495
Bom Jesus;RS;-50.429453;-28.669703
Esmeralda;RS;-51.193270;-28.051762
Cerro Negro;SC;-50.867264;-27.794205
Celso Ramos;SC;-51.335003;-27.632708
Anita Garibaldi;SC;-51.127143;-27.689743
Campos Novos;SC;-51.227589;-27.400184
Abdon Batista;SC;-51.023275;-27.612628
Vargem;SC;-50.972355;-27.486714
Brunópolis;SC;-50.868370;-27.305781
Campo Belo do Sul;SC;-50.759550;-27.897540
Capão Alto;SC;-50.509788;-27.938936
São José do Cerrito;SC;-50.573305;-27.660189
Correia Pinto;SC;-50.361448;-27.587688
Curitibanos;SC;-50.581638;-27.282373
Ponte Alta;SC;-50.376404;-27.483497
São Cristovão do Sul;SC;-50.438807;-27.266627
Seara;SC;-52.299045;-27.156432
Xavantina;SC;-52.342994;-27.066700
Xanxerê;SC;-52.403579;-26.874694
Faxinal dos Guedes;SC;-52.259624;-26.845104
Arabutã;SC;-52.142263;-27.158691
Ipumirim;SC;-52.128873;-27.077215
Lindóia do Sul;SC;-52.068955;-27.054472
Concórdia;SC;-52.025962;-27.233461
Vargeão;SC;-52.154907;-26.862088
Passos Maia;SC;-52.056768;-26.782945
Ponte Serrada;SC;-52.011250;-26.873278
Bom Jesus;SC;-52.391900;-26.732595
Ouro Verde;SC;-52.310845;-26.692027
Abelardo Luz;SC;-52.322881;-26.571585
Clevelândia;PR;-52.350794;-26.404288
Palmas;PR;-51.988812;-26.483868
Coronel Domingos Soares;PR;-52.035650;-26.227684
Presidente Castelo Branco;SC;-51.803342;-27.224778
Irani;SC;-51.901204;-27.028684
Jaborá;SC;-51.727899;-27.178233
Vargem Bonita;SC;-51.740238;-27.005456
Catanduvas;SC;-51.660158;-27.068972
Lacerdópolis;SC;-51.557660;-27.257941
Joaçaba;SC;-51.510788;-27.172105
Herval d'Oeste;SC;-51.491728;-27.190300
Luzerna;SC;-51.468221;-27.130363
Treze Tílias;SC;-51.408444;-27.002604
Água Doce;SC;-51.552812;-26.998452
Salto Veloso;SC;-51.404337;-26.902991
Honório Serpa;PR;-52.384839;-26.139021
Mangueirinha;PR;-52.174275;-25.942102
Reserva do Iguaçu;PR;-52.027215;-25.831905
Porto Barreiro;PR;-52.406744;-25.547696
Laranjeiras do Sul;PR;-52.410854;-25.407672
Virmond;PR;-52.198715;-25.382911
Foz do Jordão;PR;-52.118829;-25.737094
Candói;PR;-52.040876;-25.575847
Cantagalo;PR;-52.119818;-25.373359
Goioxim;PR;-51.991091;-25.192652
Pinhão;PR;-51.653595;-25.694388
Bituruna;PR;-51.551792;-26.160746
Guarapuava;PR;-51.462317;-25.390237
Ibicaré;SC;-51.368101;-27.088141
Ibiam;SC;-51.235154;-27.184658
Tangará;SC;-51.247253;-27.099576
Pinheiro Preto;SC;-51.224249;-27.048318
Iomerê;SC;-51.244227;-27.001920
Videira;SC;-51.154274;-27.008624
Arroio Trinta;SC;-51.340719;-26.925655
Macieira;SC;-51.370510;-26.855232
Monte Carlo;SC;-50.980785;-27.223916
Fraiburgo;SC;-50.919978;-27.023288
Rio das Antas;SC;-51.067414;-26.894615
Pérola;PR;-53.683353;-23.803898
Xambrê;PR;-53.488430;-23.736409
Vila Alta;PR;-53.728537;-23.508137
Icaraíma;PR;-53.614979;-23.394354
Querência do Norte;PR;-53.483022;-23.083758
Cascavel;PR;-53.459005;-24.957301
Corbélia;PR;-53.300576;-24.797122
Ibema;PR;-53.014616;-25.111335
Campo Bonito;PR;-52.993933;-25.029445
Braganey;PR;-53.121787;-24.817340
Anahy;PR;-53.133235;-24.644865
Iguatu;PR;-53.082724;-24.715277
Jesuítas;PR;-53.384920;-24.383918
Iracema do Oeste;PR;-53.352790;-24.426155
Cafelândia;PR;-53.320705;-24.618862
Nova Aurora;PR;-53.257449;-24.528859
Formosa do Oeste;PR;-53.311378;-24.295097
Ubiratã;PR;-52.986485;-24.539292
Quarto Centenário;PR;-53.075884;-24.277499
Goioerê;PR;-53.024842;-24.183481
Guaraniaçu;PR;-52.875531;-25.096822
Altamira do Paraná;PR;-52.712829;-24.798293
Diamante do Sul;PR;-52.676782;-25.035007
Nova Cantu;PR;-52.566078;-24.672324
Laranjal;PR;-52.470041;-24.886153
Juranda;PR;-52.841344;-24.420866
Campina da Lagoa;PR;-52.797590;-24.589323
Rancho Alegre D'Oeste;PR;-52.949883;-24.303548
Boa Esperança;PR;-52.787560;-24.246738
Janiópolis;PR;-52.778360;-24.140124
Mamborê;PR;-52.527119;-24.316951
Alto Piquiri;PR;-53.440045;-24.022427
Perobal;PR;-53.409838;-23.894861
Umuarama;PR;-53.320110;-23.765634
Mariluz;PR;-53.143242;-24.008888
Moreira Sales;PR;-53.005640;-24.052657
Maria Helena;PR;-53.205333;-23.615754
Cruzeiro do Oeste;PR;-53.077442;-23.779943
Ivaté;PR;-53.368705;-23.407206
Douradina;PR;-53.291794;-23.380720
Nova Olímpia;PR;-53.089799;-23.470252
Araruna;PR;-52.502101;-23.931500
Cianorte;PR;-52.605444;-23.659859
Terra Boa;PR;-52.447002;-23.768329
Jussara;PR;-52.469310;-23.621931
Coxim;MS;-54.750991;-18.501299
Pedro Gomes;MS;-54.550663;-18.099622
Sonora;MS;-54.755112;-17.569775
Itaipulândia;PR;-54.300102;-25.136623
Santa Helena;PR;-54.336010;-24.858494
Missal;PR;-54.247664;-25.091855
Ramilândia;PR;-54.023015;-25.119472
Diamante D'Oeste;PR;-54.105157;-24.941858
Entre Rios do Oeste;PR;-54.238507;-24.704210
São José das Palmeiras;PR;-54.057225;-24.836883
Pato Bragado;PR;-54.228115;-24.624625
Mercedes;PR;-54.161826;-24.453791
Marechal Cândido Rondon;PR;-54.056115;-24.557891
Terra Roxa;PR;-54.098816;-24.157455
Vera Cruz do Oeste;PR;-53.877096;-25.057688
Céu Azul;PR;-53.841473;-25.148876
São Pedro do Iguaçu;PR;-53.852141;-24.937311
Ouro Verde do Oeste;PR;-53.904303;-24.793258
Toledo;PR;-53.741177;-24.724641
Santa Tereza do Oeste;PR;-53.627417;-25.054316
Quatro Pontes;PR;-53.975885;-24.575152
Nova Santa Rosa;PR;-53.955249;-24.469293
Maripá;PR;-53.828641;-24.419978
Palotina;PR;-53.840422;-24.286784
Tupãssi;PR;-53.510510;-24.587887
Assis Chateaubriand;PR;-53.521327;-24.416826
Brasilândia do Sul;PR;-53.527527;-24.197762
Japorã;MS;-54.405946;-23.890325
Andrelândia;MG;-44.311690;-21.741088
Arantina;MG;-44.255519;-21.910171
Bom Jardim de Minas;MG;-44.188498;-21.947875
Santana do Garambéu;MG;-44.105044;-21.598344
Madre de Deus de Minas;MG;-44.328730;-21.483017
Conceição da Barra de Minas;MG;-44.472901;-21.131558
Ritápolis;MG;-44.320435;-21.027564
Piedade do Rio Grande;MG;-44.193834;-21.469021
São João del Rei;MG;-44.252646;-21.131054
Santa Cruz de Minas;MG;-44.220251;-21.124148
Tiradentes;MG;-44.174421;-21.110232
Coronel Xavier Chaves;MG;-44.220600;-21.027724
Prados;MG;-44.077798;-21.059748
Itaguaí;RJ;-43.779821;-22.863566
Piraí;RJ;-43.908071;-22.621497
Seropédica;RJ;-43.715482;-22.752584
Japeri;RJ;-43.660228;-22.643502
Jaupaci;GO;-50.950766;-16.177289
Santa Fé de Goiás;GO;-51.103706;-15.766426
Jussara;GO;-50.866818;-15.865877
Ivolândia;GO;-50.792076;-16.599526
Moiporá;GO;-50.739040;-16.543374
Cachoeira de Goiás;GO;-50.646041;-16.663516
Aurilândia;GO;-50.464123;-16.677260
São Luís de Montes Belos;GO;-50.372628;-16.521064
Córrego do Ouro;GO;-50.550301;-16.291774
Almeirim;PA;-52.581573;-1.520968
Laranjal do Jari;AP;-52.452962;-0.804911
Nova Veneza;SC;-49.505541;-28.633772
Siderópolis;SC;-49.431423;-28.595501
Itaiópolis;SC;-49.909200;-26.338975
Rio do Oeste;SC;-49.798896;-27.195155
Laurentino;SC;-49.733119;-27.217295
Rio do Sul;SC;-49.643016;-27.215596
Presidente Getúlio;SC;-49.624609;-27.047430
Wenceslau Braz;PR;-49.803201;-23.874217
Fartura;SP;-49.512403;-23.391643
Taguaí;SP;-49.402377;-23.445242
Tejupá;SP;-49.372244;-23.342458
Sarutaiá;SP;-49.476259;-23.272103
Piraju;SP;-49.380335;-23.198066
Tunas do Paraná;PR;-49.087889;-24.973084
Cerro Azul;PR;-49.253626;-24.822128
Adrianópolis;PR;-48.992164;-24.660607
Ribeira;SP;-49.004409;-24.651710
Itapirapuã Paulista;SP;-49.166151;-24.572033
Barra do Chapéu;SP;-49.023784;-24.472203
Bom Sucesso de Itararé;SP;-49.145104;-24.315511
Itaóca;SP;-48.841285;-24.639252
Apiaí;SP;-48.844271;-24.510819
Nova Campina;SP;-48.902179;-24.122367
Ribeirão Branco;SP;-48.763545;-24.220580
Barra do Turvo;SP;-48.501260;-24.759035
Estrela do Sul;MG;-47.695643;-18.739940
Monte Carmelo;MG;-47.491228;-18.730245
Douradoquara;MG;-47.599310;-18.433784
Abadia dos Dourados;MG;-47.391623;-18.483101
Coromandel;MG;-47.193348;-18.473364
Davinópolis;GO;-47.556795;-18.150084
Ipameri;GO;-48.158118;-17.721544
Urutaí;GO;-48.201481;-17.465097
Campo Alegre de Goiás;GO;-47.776815;-17.636346
Guimarânia;MG;-46.790145;-18.842460
Lagamar;MG;-46.806343;-18.175918
Vazante;MG;-46.908826;-17.982732
Lagoa Formosa;MG;-46.401236;-18.771489
Patos de Minas;MG;-46.501268;-18.569938
Presidente Olegário;MG;-46.416502;-18.409610
Lagoa Grande;MG;-46.516519;-17.832265
Guarda-Mor;MG;-47.099756;-17.767307
Paracatu;MG;-46.871064;-17.225166
São Sebastião da Vargem Alegre;MG;-42.634672;-21.073656
Rosário da Limeira;MG;-42.511196;-20.981227
Cantagalo;RJ;-42.366425;-21.979736
Macuco;RJ;-42.253288;-21.981345
Estrela Dalva;MG;-42.457430;-21.741231
Pirapetinga;MG;-42.343390;-21.655434
São Sebastião do Alto;RJ;-42.132816;-21.957792
Santa Maria Madalena;RJ;-42.009845;-21.954660
Itaocara;RJ;-42.075793;-21.674801
Santo Antônio de Pádua;RJ;-42.183157;-21.541038
Aperibé;RJ;-42.101661;-21.625231
Palma;MG;-42.312285;-21.374776
Barão de Monte Alto;MG;-42.237185;-21.244403
Muriaé;MG;-42.369304;-21.129965
Patrocínio do Muriaé;MG;-42.212491;-21.154387
Miracema;RJ;-42.193832;-21.414848
São José de Ubá;RJ;-41.951058;-21.366066
Laje do Muriaé;RJ;-42.127117;-21.209073
Santana do Riacho;MG;-43.721950;-19.166236
Bom Jesus do Amparo;MG;-43.478225;-19.705430
São Gonçalo do Rio Abaixo;MG;-43.365987;-19.822100
Itambé do Mato Dentro;MG;-43.318250;-19.415761
João Monlevade;MG;-43.173463;-19.812646
Bela Vista de Minas;MG;-43.092164;-19.830210
Nova Era;MG;-43.033267;-19.757738
Itabira;MG;-43.231230;-19.623936
Santa Maria de Itabira;MG;-43.106446;-19.443100
Morro do Pilar;MG;-43.379465;-19.223625
Santo Antônio do Rio Abaixo;MG;-43.260414;-19.237366
Conceição do Mato Dentro;MG;-43.422082;-19.034410
Dom Joaquim;MG;-43.254382;-18.961036
Passabém;MG;-43.138332;-19.350917
São Sebastião do Rio Preto;MG;-43.175670;-19.295906
Ferros;MG;-43.019232;-19.234309
Carmésia;MG;-43.138248;-19.087718
Nova Veneza;GO;-49.316789;-16.369518
Itaberaí;GO;-49.806034;-16.020551
Itauçu;GO;-49.610929;-16.202899
Taquaral de Goiás;GO;-49.603859;-16.052057
Itaguari;GO;-49.607084;-15.918044
Itaguaru;GO;-49.635375;-15.756470
Santa Rosa de Goiás;GO;-49.495326;-16.083983
Petrolina de Goiás;GO;-49.336371;-16.096849
Jesúpolis;GO;-49.373942;-15.948362
Uruana;GO;-49.686137;-15.499323
Carmo do Rio Verde;GO;-49.707975;-15.354914
Ceres;GO;-49.600006;-15.306109
Rialma;GO;-49.581399;-15.314482
Rianápolis;GO;-49.511399;-15.445649
Santa Isabel;GO;-49.425931;-15.295849
Rubiataba;GO;-49.804792;-15.161744
Nova Glória;GO;-49.573713;-15.144957
Itapaci;GO;-49.551122;-14.952235
Iporã;PR;-53.706031;-24.008349
Cafezal do Sul;PR;-53.512418;-23.900508
Cidade Gaúcha;PR;-52.943575;-23.377186
Indianópolis;PR;-52.698865;-23.476234
Rondon;PR;-52.765877;-23.411958
Guaporema;PR;-52.778605;-23.340218
Mirador;PR;-52.776081;-23.254951
Amaporã;PR;-52.786597;-23.094272
São Tomé;PR;-52.590125;-23.534946
São Manoel do Paraná;PR;-52.645376;-23.394137
Japurá;PR;-52.555720;-23.469273
Paraíso do Norte;PR;-52.605429;-23.282420
Nova Aliança do Ivaí;PR;-52.603244;-23.176271
São Carlos do Ivaí;PR;-52.476143;-23.315828
Tamboara;PR;-52.474272;-23.203558
Paranavaí;PR;-52.461724;-23.081650
Naviraí;MS;-54.199465;-23.061810
Fátima do Sul;MS;-54.513069;-22.378924
Vicentina;MS;-54.441507;-22.409801
Jateí;MS;-54.307934;-22.480633
Santa Isabel do Ivaí;PR;-53.198890;-23.002482
Loanda;PR;-53.136216;-22.923194
Marilena;PR;-53.040239;-22.733552
Nova Londrina;PR;-52.986770;-22.763931
Rosana;SP;-53.060296;-22.578173
Taquarussu;MS;-53.351891;-22.489797
Batayporã;MS;-53.270488;-22.294444
Nova Andradina;MS;-53.343715;-22.237971
Planaltina do Paraná;PR;-52.916242;-23.010146
Itaúna do Sul;PR;-52.887395;-22.728942
Diamante do Norte;PR;-52.861662;-22.654967
Guairaçá;PR;-52.690625;-22.931978
Terra Rica;PR;-52.618780;-22.711101
Euclides da Cunha Paulista;SP;-52.592779;-22.554520
Anaurilândia;MS;-52.719056;-22.185169
Santa Rita do Pardo;MS;-52.833338;-21.301604
Marquinho;PR;-52.249676;-25.112000
Palmital;PR;-52.202854;-24.885301
Mato Rico;PR;-52.145440;-24.699538
Roncador;PR;-52.271569;-24.595795
Luiziana;PR;-52.269022;-24.285284
Iretama;PR;-52.101161;-24.425290
Nova Tebas;PR;-51.945357;-24.437958
Godoy Moreira;PR;-51.924636;-24.172980
Campina do Simão;PR;-51.823710;-25.080171
Santa Maria do Oeste;PR;-51.869564;-24.937701
Boa Ventura de São Roque;PR;-51.627612;-24.868797
Pitanga;PR;-51.759645;-24.758773
Turvo;PR;-51.528223;-25.043671
Manoel Ribas;PR;-51.665822;-24.514363
Arapuã;PR;-51.785637;-24.313181
Ivaiporã;PR;-51.675426;-24.248533
Jardim Alegre;PR;-51.690197;-24.180913
Ariranha do Ivaí;PR;-51.583935;-24.385708
Grandes Rios;PR;-51.509378;-24.146606
Campo Mourão;PR;-52.378020;-24.046329
Peabiru;PR;-52.343107;-23.914013
Engenheiro Beltrão;PR;-52.265877;-23.797028
Ivatuba;PR;-52.220272;-23.618722
Corumbataí do Sul;PR;-52.117660;-24.100956
Barbosa Ferraz;PR;-52.003963;-24.033382
Fênix;PR;-51.980522;-23.913547
Quinta do Sol;PR;-52.130908;-23.853272
Floresta;PR;-52.080656;-23.603054
Itambé;PR;-51.991236;-23.660056
Doutor Camargo;PR;-52.217810;-23.558152
São Jorge do Ivaí;PR;-52.292907;-23.433592
Ourizona;PR;-52.196380;-23.405342
Floraí;PR;-52.302885;-23.317808
Alto Paraná;PR;-52.318893;-23.131247
Nova Esperança;PR;-52.203139;-23.182020
Paiçandu;PR;-52.046013;-23.455534
Mandaguaçu;PR;-52.094369;-23.345766
Maringá;PR;-51.933298;-23.420545
Presidente Castelo Branco;PR;-52.153563;-23.278233
Uniflor;PR;-52.157307;-23.086830
Brasilândia;MS;-52.036519;-21.254428
Presidente Venceslau;SP;-51.844744;-21.873233
Santo Anastácio;SP;-51.652662;-21.974662
Piquerobi;SP;-51.728164;-21.874671
Presidente Bernardes;SP;-51.556507;-22.008198
Ribeirão dos Índios;SP;-51.610343;-21.838201
Alfredo Marcondes;SP;-51.413956;-21.952700
Emilianópolis;SP;-51.483168;-21.831436
Santo Expedito;SP;-51.392894;-21.846657
Flora Rica;SP;-51.382107;-21.672738
Chapadão do Céu;GO;-52.548986;-18.407326
Alto Taquari;MT;-53.279220;-17.824050
Alto Araguaia;MT;-53.218125;-17.315324
Santa Rita do Araguaia;GO;-53.201236;-17.326910
Araguainha;MT;-53.031778;-16.857016
Mineiros;GO;-52.553672;-17.565388
Portelândia;GO;-52.679939;-17.355381
Uarini;AM;-65.113298;-2.996087
Atalaia;PR;-52.055096;-23.151721
Ângulo;PR;-51.915356;-23.194609
Flórida;PR;-51.954559;-23.084703
São Pedro do Ivaí;PR;-51.856775;-23.863394
São João do Ivaí;PR;-51.821544;-23.983281
Lunardelli;PR;-51.736783;-24.082055
Lidianópolis;PR;-51.650629;-24.109970
Kaloré;PR;-51.668709;-23.818835
Bom Sucesso;PR;-51.767056;-23.706313
Jandaia do Sul;PR;-51.644819;-23.601089
Borrazópolis;PR;-51.587507;-23.936607
Cruzmaltina;PR;-51.456331;-24.013218
Marumbi;PR;-51.640374;-23.705764
Novo Itacolomi;PR;-51.507890;-23.763080
Rio Bom;PR;-51.412240;-23.760583
Marialva;PR;-51.792802;-23.484316
Sarandi;PR;-51.876016;-23.444117
Mandaguari;PR;-51.671027;-23.544590
Iguaraçu;PR;-51.825552;-23.194896
Astorga;PR;-51.666777;-23.231827
Munhoz de Melo;PR;-51.773162;-23.146010
Cambira;PR;-51.579152;-23.589018
Apucarana;PR;-51.463486;-23.549961
Arapongas;PR;-51.425920;-23.415296
Pitangueiras;PR;-51.587277;-23.228134
Sabáudia;PR;-51.555005;-23.315512
Jaguapitã;PR;-51.534195;-23.110388
Ivaí;PR;-50.857041;-25.006734
Cândido de Abreu;PR;-51.337190;-24.564895
Rio Branco do Ivaí;PR;-51.318684;-24.324449
Rosário do Ivaí;PR;-51.272007;-24.268240
Ortigueira;PR;-50.918508;-24.205811
Reserva;PR;-50.846604;-24.649169
Ipiranga;PR;-50.579378;-25.023769
Imbaú;PR;-50.753269;-24.448010
Telêmaco Borba;PR;-50.617585;-24.324520
Tibagi;PR;-50.417587;-24.515316
Faxinal;PR;-51.322665;-24.007671
Mauá da Serra;PR;-51.227732;-23.898761
Marilândia do Sul;PR;-51.313737;-23.742534
Califórnia;PR;-51.357367;-23.656587
Tamarana;PR;-51.099054;-23.720365
Rolândia;PR;-51.365928;-23.310120
Cambé;PR;-51.279832;-23.276575
Londrina;PR;-51.169100;-23.303975
Ibiporã;PR;-51.052243;-23.265941
Jataizinho;PR;-50.977748;-23.257815
São Jerônimo da Serra;PR;-50.747536;-23.721765
Sapopema;PR;-50.580062;-23.907832
Curiúva;PR;-50.457638;-24.036168
Figueira;PR;-50.403068;-23.845542
Nova Santa Bárbara;PR;-50.759798;-23.586534
Santa Cecília do Pavão;PR;-50.783528;-23.520081
São Sebastião da Amoreira;PR;-50.762536;-23.465640
Assaí;PR;-50.845925;-23.369679
Santo Antônio do Paraíso;PR;-50.645541;-23.496903
Uraí;PR;-50.793873;-23.199997
Leópolis;PR;-50.751085;-23.081781
Nova América da Colina;PR;-50.716821;-23.330772
Cornélio Procópio;PR;-50.649770;-23.182911
Congonhinhas;PR;-50.556872;-23.549310
Nova Fátima;PR;-50.566507;-23.432447
Ribeirão do Pinhal;PR;-50.360145;-23.409068
Santa Mariana;PR;-50.516728;-23.146520
Santa Amélia;PR;-50.428765;-23.265421
Bandeirantes;PR;-50.370407;-23.107788
São João do Caiuá;PR;-52.341065;-22.853502
Santo Antônio do Caiuá;PR;-52.344022;-22.735084
Inajá;PR;-52.199499;-22.750929
Cruzeiro do Sul;PR;-52.162210;-22.962440
Paranacity;PR;-52.154946;-22.929713
Lobato;PR;-51.952419;-23.005782
Colorado;PR;-51.974301;-22.837422
Paranapoema;PR;-52.090520;-22.641240
Jardim Olinda;PR;-52.050298;-22.552255
Itaguajé;PR;-51.967351;-22.618282
Teodoro Sampaio;SP;-52.168175;-22.529936
Mirante do Paranapanema;SP;-51.908350;-22.290427
Marabá Paulista;SP;-51.961670;-22.106766
Santa Fé;PR;-51.808014;-23.039993
Nossa Senhora das Graças;PR;-51.797764;-22.912926
Guaraci;PR;-51.650406;-22.969426
Santo Inácio;PR;-51.796937;-22.695666
Santa Inês;PR;-51.902385;-22.637595
Cafeara;PR;-51.714195;-22.789050
Lupionópolis;PR;-51.660085;-22.754987
Centenário do Sul;PR;-51.597268;-22.818764
Miraselva;PR;-51.484606;-22.965663
Prado Ferreira;PR;-51.442883;-23.035698
Florestópolis;PR;-51.388157;-22.862263
Sandovalina;SP;-51.764822;-22.455099
Estrela do Norte;SP;-51.663203;-22.485935
Narandiba;SP;-51.527357;-22.405737
Tarabai;SP;-51.562133;-22.301612
Anhumas;SP;-51.389542;-22.293414
Pirapozinho;SP;-51.497635;-22.271125
Álvares Machado;SP;-51.472240;-22.076415
Sertanópolis;PR;-51.039948;-23.057057
São Gabriel da Cachoeira;AM;-67.084042;-0.119090
Araputanga;MT;-58.342461;-15.464096
Reserva do Cabaçal;MT;-58.458533;-15.074259
São José dos Quatro Marcos;MT;-58.177217;-15.627584
Mirassol d'Oeste;MT;-58.090996;-15.667801
Rio Branco;MT;-58.125892;-15.248331
Lambari D'Oeste;MT;-58.002856;-15.317213
Nova Brasilândia;MT;-54.968527;-14.961215
Novo Mundo;MT;-55.202950;-9.956159
Pacajá;PA;-50.638625;-3.837314
Porto de Moz;PA;-52.236071;-1.746906
Vitória do Jari;AP;-52.424001;-0.938000
Gurupá;PA;-51.633841;-1.414123
Portel;PA;-50.819420;-1.936388
Melgaço;PA;-50.714927;-1.803202
Breves;PA;-50.479085;-1.680360
Mazagão;AP;-51.289130;-0.113360
Santana;AP;-51.172924;-0.045434
Afuá;PA;-50.386130;-0.154874
Balneário Pinhal;RS;-50.233655;-30.241938
Taquarivaí;SP;-48.694769;-23.921116
Buri;SP;-48.595811;-23.797689
Ribeirão Grande;SP;-48.367931;-24.101140
Capão Bonito;SP;-48.348222;-24.011265
Paranapanema;SP;-48.721435;-23.386196
Itatinga;SP;-48.615712;-23.104707
Campina do Monte Alegre;SP;-48.475800;-23.589480
Angatuba;SP;-48.413872;-23.491733
Pardinho;SP;-48.367942;-23.084056
Bofete;SP;-48.258203;-23.105543
Andirá;PR;-50.230359;-23.053267
Barão de Melgaço;MT;-55.962337;-16.206690
Santo Antônio do Leverger;MT;-56.078752;-15.862409
Jangada;MT;-56.491693;-15.235044
Várzea Grande;MT;-56.132218;-15.645816
Acorizal;MT;-56.363226;-15.194025
Rosário Oeste;MT;-56.423570;-14.825938
Nobres;MT;-56.328358;-14.719167
Chapada dos Guimarães;MT;-55.749857;-15.464343
Rondonópolis;MT;-54.637173;-16.467251
Juscimeira;MT;-54.885894;-16.063307
São Pedro da Cipa;MT;-54.917580;-16.010905
Anori;AM;-61.657485;-3.746027
Beruri;AM;-61.361620;-3.898736
Anamã;AM;-61.396337;-3.566966
Caapiranga;AM;-61.220557;-3.315372
Novo Airão;AM;-60.943404;-2.636371
Careiro;AM;-60.369046;-3.768027
Manacapuru;AM;-60.621581;-3.290657
Manaquiri;AM;-60.461239;-3.440784
Iranduba;AM;-60.189953;-3.274792
Careiro da Várzea;AM;-59.796390;-3.196979
Nova Olinda do Norte;AM;-59.094040;-3.900367
Autazes;AM;-59.125589;-3.585743
Rio Preto da Eva;AM;-59.685793;-2.704497
Presidente Figueiredo;AM;-60.023380;-2.029813
Porto Esperidião;MT;-58.461925;-15.857026
Glória D'Oeste;MT;-58.306121;-15.767592
Cáceres;MT;-57.681798;-16.076414
Indiavaí;MT;-58.580158;-15.492115
Salto do Céu;MT;-58.131721;-15.130346
Poconé;MT;-56.626099;-16.266037
Porto Estrela;MT;-57.220403;-15.323517
Barra do Bugres;MT;-57.187784;-15.070160
Biguaçu;SC;-48.659790;-27.496004
Governador Celso Ramos;SC;-48.557608;-27.317161
Mirim Doce;SC;-50.078623;-27.197002
Rio do Campo;SC;-50.136021;-26.945181
Taió;SC;-49.994184;-27.121001
Pouso Redondo;SC;-49.930098;-27.256725
Salete;SC;-49.998811;-26.979780
Santa Terezinha;SC;-50.009017;-26.781300
Vitor Meireles;SC;-49.832787;-26.878152
Major Vieira;SC;-50.326592;-26.370901
Monte Castelo;SC;-50.232724;-26.460989
Papanduva;SC;-50.141938;-26.377709
Benedito Novo;SC;-49.359314;-26.780974
Doutor Pedrinho;SC;-49.479495;-26.717370
Rio Negrinho;SC;-49.517746;-26.259075
São Bento do Sul;SC;-49.383077;-26.249542
Três Barras;SC;-50.319692;-26.105632
Antônio Olinto;PR;-50.197183;-25.980380
Mafra;SC;-49.808624;-26.115935
São João do Triunfo;PR;-50.294920;-25.682971
Porto Amazonas;PR;-49.894591;-25.540002
Palmeira;PR;-50.007027;-25.425716
Barra Bonita;SP;-48.558349;-22.490859
Jaú;SP;-48.559193;-22.293585
Itapuí;SP;-48.719734;-22.232445
Bariri;SP;-48.743828;-22.072955
Bocaina;SP;-48.523044;-22.136538
Mineiros do Tietê;SP;-48.450998;-22.412007
Dois Córregos;SP;-48.381924;-22.367291
Dourado;SP;-48.317823;-22.104436
Trabiju;SP;-48.334168;-22.038783
Reginópolis;SP;-49.226782;-21.891372
Iacanga;SP;-49.030959;-21.889646
Borborema;SP;-49.074089;-21.621368
Hortolândia;SP;-47.214259;-22.852854
Santa Barbara d'Oeste;SP;-47.414308;-22.755256
Americana;SP;-47.333119;-22.737360
Limeira;SP;-47.396987;-22.566050
Nova Odessa;SP;-47.294059;-22.783186
Cosmópolis;SP;-47.192578;-22.641906
Santa Gertrudes;SP;-47.527160;-22.457229
Cordeirópolis;SP;-47.451886;-22.477778
Rio Claro;SP;-47.554632;-22.398378
Corumbataí;SP;-47.621451;-22.221334
Analândia;SP;-47.661939;-22.128917
Caldas Novas;GO;-48.624579;-17.744060
Santa Cruz de Goiás;GO;-48.480950;-17.315512
Palmelo;GO;-48.426030;-17.325819
Pires do Rio;GO;-48.276756;-17.301901
Cristianópolis;GO;-48.703447;-17.198663
São Miguel do Passa Quatro;GO;-48.661981;-17.058201
Orizona;GO;-48.296410;-17.033356
Morro Agudo;SP;-48.058084;-20.728828
Orlândia;SP;-47.885205;-20.716925
Nova Monte Verde;MT;-57.526052;-9.999982
Apiacás;MT;-57.458677;-9.539809
Sorriso;MT;-55.721051;-12.542527
Nova Canaã do Norte;MT;-55.953001;-10.558000
Vera;MT;-55.304497;-12.301659
Sinop;MT;-55.509062;-11.860430
Santa Carmem;MT;-55.226297;-11.912542
Feliz Natal;MT;-54.922738;-12.384985
Itaúba;MT;-55.276574;-11.061423
Colíder;MT;-55.460981;-10.813453
Terra Nova do Norte;MT;-55.231000;-10.517000
Cláudia;MT;-54.883476;-11.507500
Paranaíta;MT;-56.478623;-9.658348
Alta Floresta;MT;-56.086704;-9.866745
Carlinda;MT;-55.841671;-9.949118
Nova Guarita;MT;-55.406062;-10.311992
Fazenda Nova;GO;-50.778111;-16.183370
Novo Brasil;GO;-50.711279;-16.031326
Itapirapuã;GO;-50.609434;-15.820500
Buriti de Goiás;GO;-50.430223;-16.179199
Britânia;GO;-51.160206;-15.242760
Aruanã;GO;-51.075008;-14.916604
Matrinchã;GO;-50.745644;-15.434171
Faina;GO;-50.362205;-15.447297
Araguapaz;GO;-50.631511;-15.090853
Mozarlândia;GO;-50.571321;-14.745705
Nova Xavantina;MT;-52.350234;-14.677063
Água Boa;MT;-52.160133;-14.051019
Guiratinga;MT;-53.757490;-16.345979
Tesouro;MT;-53.559032;-16.080932
Primavera do Leste;MT;-54.281056;-15.543971
Ponte Branca;MT;-52.836864;-16.758407
Ribeirãozinho;MT;-52.692439;-16.485630
Torixoréu;MT;-52.557097;-16.200591
Baliza;GO;-52.539253;-16.196606
Novo São Joaquim;MT;-53.019362;-14.905406
General Carneiro;MT;-52.757375;-15.709447
Paranatinga;MT;-54.052426;-14.426475
Campinápolis;MT;-52.892979;-14.516182
Gaúcha do Norte;MT;-53.080914;-13.244284
Doverlândia;GO;-52.318860;-16.718819
Bom Jardim de Goiás;GO;-52.172793;-16.206266
Pontal do Araguaia;MT;-52.327296;-15.927383
Aragarças;GO;-52.237231;-15.895460
Barra do Garças;MT;-52.263953;-15.880412
Piranhas;GO;-51.823472;-16.425807
Palestina de Goiás;GO;-51.530885;-16.739221
Arenópolis;GO;-51.556280;-16.383726
Araguaiana;MT;-51.834112;-15.729144
Montes Claros de Goiás;GO;-51.397878;-16.005946
Amorinópolis;GO;-51.091930;-16.615094
Iporá;GO;-51.118049;-16.439764
Israelândia;GO;-50.908722;-16.314389
Diorama;GO;-51.254332;-16.232936
Primeiro de Maio;PR;-51.029251;-22.851714
Rancho Alegre;PR;-50.914453;-23.067594
Iepê;SP;-51.077881;-22.660165
Taciba;SP;-51.288221;-22.386577
Regente Feijó;SP;-51.305450;-22.218131
Indiana;SP;-51.255467;-22.173779
Martinópolis;SP;-51.170949;-22.146153
Rancharia;SP;-50.893041;-22.226910
Sertaneja;PR;-50.831656;-23.036095
Pedrinhas Paulista;SP;-50.793287;-22.817378
Florínia;SP;-50.732976;-22.902483
Cruzália;SP;-50.790909;-22.737309
Maracaí;SP;-50.671288;-22.614921
Itambaracá;PR;-50.409654;-23.018113
Tarumã;SP;-50.578620;-22.742873
Cândido Mota;SP;-50.387326;-22.747093
Assis;SP;-50.418307;-22.659957
João Ramalho;SP;-50.769359;-22.247339
Quatá;SP;-50.696605;-22.245590
Paraguaçu Paulista;SP;-50.573160;-22.411369
Lutécia;SP;-50.394007;-22.338378
Borá;SP;-50.540919;-22.269627
Caiabu;SP;-51.239408;-22.012719
Mariápolis;SP;-51.182434;-21.795920
Irapuru;SP;-51.347171;-21.568390
Pacaembu;SP;-51.265366;-21.562661
Flórida Paulista;SP;-51.172445;-21.612711
Pracinha;SP;-51.086791;-21.849563
Sagres;SP;-50.959381;-21.882320
Inúbia Paulista;SP;-50.963268;-21.769471
Osvaldo Cruz;SP;-50.879288;-21.796753
Adamantina;SP;-51.073695;-21.682012
Lucélia;SP;-51.021511;-21.718203
Salmourão;SP;-50.861366;-21.626729
Murutinga do Sul;SP;-51.277389;-20.990803
Guaraçaí;SP;-51.211854;-21.029173
Mirandópolis;SP;-51.103500;-21.131345
Lavínia;SP;-51.041172;-21.163913
Valparaíso;SP;-50.869874;-21.222929
Bastos;SP;-50.735695;-21.920984
Parapuã;SP;-50.794882;-21.779205
Iacri;SP;-50.693152;-21.857199
Rinópolis;SP;-50.723908;-21.728402
Piacatu;SP;-50.600343;-21.592142
Tupã;SP;-50.519113;-21.933528
Arco-Íris;SP;-50.466018;-21.772797
Herculândia;SP;-50.390734;-22.003802
Santópolis do Aguapeí;SP;-50.504372;-21.637574
Gabriel Monteiro;SP;-50.557321;-21.529410
Clementina;SP;-50.452476;-21.560447
Bento de Abreu;SP;-50.813959;-21.268629
Rubiácea;SP;-50.729564;-21.300568
Guararapes;SP;-50.645338;-21.254431
Bilac;SP;-50.474640;-21.403962
Birigui;SP;-50.343162;-21.291034
Araçatuba;SP;-50.440106;-21.207648
Chapadão do Sul;MS;-52.626337;-18.788035
Três Lagoas;MS;-51.700731;-20.784853
Castilho;SP;-51.488394;-20.868924
Itapura;SP;-51.506322;-20.641911
Selvíria;MS;-51.419246;-20.363733
Inocência;MS;-51.928098;-19.727716
Aporé;GO;-51.923184;-18.960729
Cassilândia;MS;-51.731309;-19.117867
Itajá;GO;-51.549514;-19.067338
Arenápolis;MT;-56.843724;-14.447159
Nortelândia;MT;-56.794516;-14.454022
São José do Rio Claro;MT;-56.721841;-13.439818
Nova Maringá;MT;-57.090769;-13.013614
Nossa Senhora do Livramento;MT;-56.343242;-15.772024
Jaciara;MT;-54.973320;-15.954778
Dom Aquino;MT;-54.922298;-15.809899
Ourinhos;SP;-49.869702;-22.979695
Campos Novos Paulista;SP;-49.998726;-22.601952
Ribeirão do Sul;SP;-49.933037;-22.789013
Echaporã;SP;-50.203787;-22.432560
Oscar Bressane;SP;-50.281118;-22.314874
Quintana;SP;-50.307028;-22.069172
Oriente;SP;-50.097105;-22.154939
Pompéia;SP;-50.175964;-22.107040
Ocauçu;SP;-49.922005;-22.437979
Lupércio;SP;-49.818019;-22.414590
Marília;SP;-49.950060;-22.217108
Vera Cruz;SP;-49.820735;-22.218308
Canitar;SP;-49.783947;-23.004014
Chavantes;SP;-49.709616;-23.036632
Ipaussu;SP;-49.627911;-23.057530
Santa Cruz do Rio Pardo;SP;-49.635444;-22.898750
São Pedro do Turvo;SP;-49.742829;-22.745286
Bernardino de Campos;SP;-49.467897;-23.016439
Carmo de Minas;MG;-45.130690;-22.120366
São João da Mata;MG;-45.929727;-21.927998
Poço Fundo;MG;-45.965750;-21.780002
Serrania;MG;-46.041701;-21.544145
Machado;MG;-45.921855;-21.677757
Carvalhópolis;MG;-45.842127;-21.773534
Turvolândia;MG;-45.785948;-21.873342
Cordislândia;MG;-45.699879;-21.789099
Paraguaçu;MG;-45.737419;-21.546507
Trajano de Morais;RJ;-42.070645;-22.062664
Sapucaia;RJ;-42.914194;-21.994867
Senador Cortes;MG;-42.942375;-21.798562
Santo Antônio do Aventureiro;MG;-42.811457;-21.760572
Maripá de Minas;MG;-42.954589;-21.697919
Argirita;MG;-42.829162;-21.608270
Carmo;RJ;-42.604636;-21.930957
Além Paraíba;MG;-42.717588;-21.879703
Volta Grande;MG;-42.537501;-21.767086
Cachoeira da Prata;MG;-44.454383;-19.520967
Inhaúma;MG;-44.393394;-19.489843
Contagem;MG;-44.053920;-19.932079
Ribeirão das Neves;MG;-44.084450;-19.762076
Sete Lagoas;MG;-44.241255;-19.456902
Capim Branco;MG;-44.130351;-19.547067
Matozinhos;MG;-44.086786;-19.554318
Prudente de Morais;MG;-44.159074;-19.474213
Caetanópolis;MG;-44.418871;-19.297075
Andradina;SP;-51.378568;-20.894761
Pereira Barreto;SP;-51.112268;-20.636767
Suzanápolis;SP;-51.026761;-20.498090
Sud Mennucci;SP;-50.923783;-20.687225
Aparecida d'Oeste;SP;-50.883506;-20.448734
Ilha Solteira;SP;-51.342552;-20.432629
Nova Canaã Paulista;SP;-50.948264;-20.383625
Santa Fé do Sul;SP;-50.931958;-20.208252
Três Fronteiras;SP;-50.890528;-20.234355
Aparecida do Taboado;MS;-51.096135;-20.087275
Rubinéia;SP;-51.007034;-20.175906
Santa Clara d'Oeste;SP;-50.949109;-20.090041
Guzolândia;SP;-50.664505;-20.646679
Dirce Reis;SP;-50.607335;-20.464207
Santo Antônio do Aracanguá;SP;-50.497979;-20.933114
Nova Castilho;SP;-50.347741;-20.761492
Auriflama;SP;-50.557190;-20.683621
General Salgado;SP;-50.363970;-20.648458
São João de Iracema;SP;-50.356069;-20.511059
Marinópolis;SP;-50.825384;-20.438900
Palmeira d'Oeste;SP;-50.763199;-20.414776
Santana da Ponte Pensa;SP;-50.801357;-20.252311
São Francisco;SP;-50.695151;-20.362291
Santa Salete;SP;-50.688717;-20.242918
Urânia;SP;-50.645498;-20.245461
Santa Rita d'Oeste;SP;-50.835781;-20.141431
Aspásia;SP;-50.728046;-20.160028
Santa Albertina;SP;-50.729713;-20.031131
Mesópolis;SP;-50.632641;-19.968351
Pontalinda;SP;-50.525754;-20.439624
Jales;SP;-50.549442;-20.267227
Vitória Brasil;SP;-50.487452;-20.195582
São João das Duas Pontes;SP;-50.379149;-20.387857
Estrela d'Oeste;SP;-50.404872;-20.287550
Paranapuã;SP;-50.588634;-20.104774
Dolcinópolis;SP;-50.514898;-20.124047
Populina;SP;-50.538035;-19.945297
Turmalina;SP;-50.479198;-20.048588
Guarani d'Oeste;SP;-50.341123;-20.074646
Ouroeste;SP;-50.376807;-20.006052
Paranaíba;MS;-51.190863;-19.674648
Carneirinho;MG;-50.689441;-19.698701
Limeira do Oeste;MG;-50.581489;-19.551212
União de Minas;MG;-50.338019;-19.529861
Paranaiguara;GO;-50.653928;-18.914055
São Simão;GO;-50.546968;-18.996030
Serranópolis;GO;-51.958637;-18.306722
Jataí;GO;-51.720424;-17.878383
Perolândia;GO;-52.064987;-17.525835
Caiapônia;GO;-51.809076;-16.953878
Itarumã;GO;-51.348516;-18.764553
Caçu;GO;-51.132798;-18.559382
Cachoeira Alta;GO;-50.943243;-18.761827
Aparecida do Rio Doce;GO;-51.151606;-18.294071
Quirinópolis;GO;-50.454698;-18.447186
Maurilândia;GO;-50.338836;-17.971859
Montividiu;GO;-51.172815;-17.443934
Rio Verde;GO;-50.919195;-17.792266
Santa Helena de Goiás;GO;-50.597664;-17.811520
Santo Antônio da Barra;GO;-50.634543;-17.558499
Acreúna;GO;-50.374903;-17.395990
Paraúna;GO;-50.448357;-16.946259
São João da Paraúna;GO;-50.409243;-16.812622
Guajará-Mirim;RO;-65.329566;-10.788927
Acrelândia;AC;-66.897166;-9.825808
Nova Mamoré;RO;-65.334596;-10.407697
Costa Marques;RO;-64.227970;-12.436715
São Francisco do Guaporé;RO;-63.567968;-12.051977
Seringueiras;RO;-63.018234;-11.805454
Campo Novo de Rondônia;RO;-63.626551;-10.571250
Buritis;RO;-63.832438;-10.194272
Monte Negro;RO;-63.289988;-10.245823
Alto Paraíso;RO;-63.318834;-9.714294
Ariquemes;RO;-63.032516;-9.905711
Candeias do Jamari;RO;-63.700502;-8.790696
Itapuã do Oeste;RO;-63.180878;-9.196871
Pimenteiras do Oeste;RO;-61.047052;-13.482271
Corumbiara;RO;-60.894732;-12.955111
Vila Bela da Santíssima Trindade;MT;-59.947256;-15.003938
Pontes e Lacerda;MT;-59.343544;-15.221868
Jauru;MT;-58.872267;-15.334170
Figueirópolis D'Oeste;MT;-58.739149;-15.443918
Comodoro;MT;-59.784751;-13.661436
Cerejeiras;RO;-60.816836;-13.186987
Cabixi;RO;-60.552032;-13.494479
Colorado do Oeste;RO;-60.545428;-13.117431
Vilhena;RO;-60.148847;-12.750183
Nova Lacerda;MT;-59.600115;-14.472726
Campos de Júlio;MT;-59.285807;-13.724224
Sapezal;MT;-58.764473;-12.989214
São Miguel do Guaporé;RO;-62.719222;-11.695270
Nova Brasilândia D'Oeste;RO;-62.312665;-11.724680
Alta Floresta D'Oeste;RO;-61.995342;-11.928325
Novo Horizonte do Oeste;RO;-61.995137;-11.696145
Mirante da Serra;RO;-62.669593;-11.028967
Nova União;RO;-62.556368;-10.906810
Governador Jorge Teixeira;RO;-62.737071;-10.610031
Alvorada D'Oeste;RO;-62.284697;-11.346271
Urupá;RO;-62.363895;-11.126123
Castanheiras;RO;-61.948228;-11.425304
Presidente Médici;RO;-61.898617;-11.168974
Teixeirópolis;RO;-62.241993;-10.905601
Ouro Preto do Oeste;RO;-62.256532;-10.716717
Ji-Paraná;RO;-61.932152;-10.877741
Alto Alegre dos Parecis;RO;-61.834979;-12.131955
Parecis;RO;-61.603225;-12.175416
Santa Luzia D'Oeste;RO;-61.777685;-11.907362
Rolim de Moura;RO;-61.771411;-11.727071
São Felipe D'Oeste;RO;-61.502623;-11.902258
Chupinguaia;RO;-60.887694;-12.561115
Primavera de Rondônia;RO;-61.315336;-11.829456
Pimenta Bueno;RO;-61.197963;-11.671959
Cacoal;RO;-61.456167;-11.434271
Ministro Andreazza;RO;-61.517437;-11.195960
Espigão D'Oeste;RO;-61.025187;-11.526574
Cacaulândia;RO;-62.904328;-10.348981
Jaru;RO;-62.478769;-10.431826
Rio Crespo;RO;-62.901127;-9.699648
Theobroma;RO;-62.353796;-10.248340
Vale do Paraíso;RO;-62.135182;-10.446543
Vale do Anari;RO;-62.187649;-9.862155
Machadinho D'Oeste;RO;-61.981801;-9.443629
Cujubim;RO;-62.584579;-9.360655
Juína;MT;-58.748314;-11.372752
Aripuanã;MT;-59.456815;-10.172262
Pauini;AM;-66.992039;-7.713109
Carauari;AM;-66.908559;-4.881605
Lábrea;AM;-64.794765;-7.264133
Canutama;AM;-64.395280;-6.525820
Humaitá;AM;-63.032743;-7.511712
Tapauá;AM;-63.180800;-5.620855
Juruá;AM;-66.071795;-3.484384
Jutaí;AM;-66.759470;-2.758144
Fonte Boa;AM;-66.094212;-2.523424
Japurá;AM;-66.929051;-1.882366
Peixoto de Azevedo;MT;-54.979376;-10.226168
Matupá;MT;-54.946670;-10.182065
Guarantã do Norte;MT;-54.912126;-9.962182
Pedra Preta;MT;-54.472180;-16.624505
São José do Povo;MT;-54.248716;-16.454946
Poxoréo;MT;-54.388276;-15.841012
Maraã;AM;-65.572995;-1.853129
Alvarães;AM;-64.800654;-3.227270
Tefé;AM;-64.719343;-3.368219
Coari;AM;-63.144091;-4.094121
Santa Isabel do Rio Negro;AM;-65.009205;-0.410824
Barcelos;AM;-62.931053;-0.983373
Manicoré;AM;-61.289483;-5.804618
Apuí;AM;-59.895956;-7.194091
Novo Aripuanã;AM;-60.373179;-5.125929
Borba;AM;-59.587380;-4.391539
Codajás;AM;-62.065787;-3.830528
Nova Olímpia;MT;-57.288613;-14.788947
Denise;MT;-57.058262;-14.732397
Campo Novo do Parecis;MT;-57.890723;-13.658735
Tangará da Serra;MT;-57.493278;-14.622910
Santo Afonso;MT;-57.009099;-14.494474
Nova Marilândia;MT;-56.969572;-14.356757
Campo Verde;MT;-55.162582;-15.544978
Alto Paraguai;MT;-56.477630;-14.513712
Diamantino;MT;-56.436599;-14.403673
Nova Mutum;MT;-56.074316;-13.837389
Tapurah;MT;-56.517821;-12.695014
Lucas do Rio Verde;MT;-55.904202;-13.058796
Planalto da Serra;MT;-54.781857;-14.651773
Nova Ubiratã;MT;-55.255558;-12.983411
Brasnorte;MT;-57.983341;-12.147402
Castanheira;MT;-58.608076;-11.125060
Porto dos Gaúchos;MT;-57.413233;-11.532999
Juara;MT;-57.524443;-11.263925
Novo Horizonte do Norte;MT;-57.348763;-11.408889
Tabaporã;MT;-56.831237;-11.300739
Juruena;MT;-58.359179;-10.317805
Cotriguaçu;MT;-58.419231;-9.856563
Nova Bandeirantes;MT;-57.864687;-9.808959
Witmarsum;SC;-49.794675;-26.927487
Dona Emma;SC;-49.726142;-26.980975
José Boiteux;SC;-49.628630;-26.956568
Lontras;SC;-49.535049;-27.168358
Ibirama;SC;-49.519314;-27.054665
Apiúna;SC;-49.388506;-27.037493
Ascurra;SC;-49.378280;-26.954821
Rodeio;SC;-49.364910;-26.924349
São José da Boa Vista;PR;-49.657667;-23.912195
Santana do Itararé;PR;-49.629314;-23.758692
Salto do Itararé;PR;-49.635384;-23.607356
Barão de Antonina;SP;-49.563366;-23.628419
Sengés;PR;-49.461568;-24.112884
Itararé;SP;-49.335215;-24.108451
Riversul;SP;-49.428965;-23.829003
Itaporanga;SP;-49.481854;-23.704273
Coronel Macedo;SP;-49.309966;-23.626143
Carlópolis;PR;-49.723486;-23.426897
Ribeirão Claro;PR;-49.759742;-23.194132
Timburi;SP;-49.609583;-23.205674
Canarana;MT;-52.270493;-13.551502
Querência;MT;-52.182102;-12.609294
Ribeirão Cascalheira;MT;-51.824421;-12.936738
Cocalinho;MT;-51.000150;-14.390339
União do Sul;MT;-54.361637;-11.530776
Marcelândia;MT;-54.437681;-11.046259
São José do Xingu;MT;-52.748589;-10.798193
Alto Boa Vista;MT;-51.388326;-11.673199
Canabrava do Norte;MT;-51.820883;-11.055561
Porto Alegre do Norte;MT;-51.635731;-10.876126
Confresa;MT;-51.569873;-10.643695
São Félix do Araguaia;MT;-50.670624;-11.614968
Luciára;MT;-50.667592;-11.221926
Vila Rica;MT;-51.118599;-10.013666
Santa Terezinha;MT;-50.513986;-10.470375
Santana do Araguaia;PA;-50.350036;-9.328099
Jacareacanga;PA;-57.754352;-6.214690
Novo Progresso;PA;-55.378591;-7.143465
Trairão;PA;-55.942859;-4.573474
Itaituba;PA;-55.992640;-4.266696
Maués;AM;-57.706731;-3.392891
Itacoatiara;AM;-58.444874;-3.138607
Silves;AM;-58.248031;-2.817480
Urucurituba;AM;-58.149637;-3.128412
Itapiranga;AM;-58.029257;-2.740810
São Sebastião do Uatumã;AM;-57.873049;-2.559147
Urucará;AM;-57.753797;-2.529364
Boa Vista do Ramos;AM;-57.587333;-2.974087
Barreirinha;AM;-57.067897;-2.798863
Parintins;AM;-56.729045;-2.637412
Faro;PA;-56.740498;-2.168047
Nhamundá;AM;-56.711151;-2.207932
Terra Santa;PA;-56.487653;-2.104435
Juruti;PA;-56.088903;-2.163472
Aveiro;PA;-55.319940;-3.608415
Rurópolis;PA;-54.909167;-4.100279
Belterra;PA;-54.937392;-2.636091
Santarém;PA;-54.699611;-2.438489
Oriximiná;PA;-55.857937;-1.759888
Óbidos;PA;-55.520812;-1.901072
Curuá;PA;-55.116842;-1.887754
Alenquer;PA;-54.738355;-1.946229
São Félix do Xingu;PA;-51.990382;-6.642535
Cumaru do Norte;PA;-50.769772;-7.810968
Bannach;PA;-50.395853;-7.347786
Tucumã;PA;-51.162613;-6.746874
Ourilândia do Norte;PA;-51.085792;-6.752905
Água Azul do Norte;PA;-50.479068;-6.790534
Placas;PA;-54.212371;-3.868131
Uruará;PA;-53.739644;-3.715189
Medicilândia;PA;-52.887480;-3.446375
Brasil Novo;PA;-52.534021;-3.297922
Monte Alegre;PA;-54.072386;-1.997679
Prainha;PA;-53.477903;-1.797999
Altamira;PA;-52.209961;-3.204065
Vitória do Xingu;PA;-52.008764;-2.879219
Senador José Porfírio;PA;-51.948701;-2.585981
Anapu;PA;-51.200264;-3.469847
Cidreira;RS;-50.233747;-30.160386
Osório;RS;-50.266713;-29.888089
Tramandaí;RS;-50.132187;-29.984140
Imbé;RS;-50.128068;-29.975269
Maquiné;RS;-50.207859;-29.679830
Três Forquilhas;RS;-50.070771;-29.538420
Xangri-lá;RS;-50.051932;-29.806493
Capão da Canoa;RS;-50.028243;-29.764242
Terra de Areia;RS;-50.064390;-29.578199
Arroio do Sal;RS;-49.889537;-29.543872
Três Cachoeiras;RS;-49.927514;-29.448661
Dom Pedro de Alcântara;RS;-49.853007;-29.363872
Cambará do Sul;RS;-50.146507;-29.047408
Praia Grande;SC;-49.952503;-29.191846
Morrinhos do Sul;RS;-49.932816;-29.357756
Mampituba;RS;-49.931105;-29.213628
São João do Sul;SC;-49.809357;-29.215415
São José dos Ausentes;RS;-50.067720;-28.747589
Timbé do Sul;SC;-49.842048;-28.828749
Torres;RS;-49.733289;-29.333438
Passo de Torres;SC;-49.722034;-29.309889
Santa Rosa do Sul;SC;-49.710944;-29.131273
Sombrio;SC;-49.632776;-29.107985
Balneário Gaivota;SC;-49.584092;-29.152720
Jacinto Machado;SC;-49.762316;-28.996094
Turvo;SC;-49.683067;-28.927169
Ermo;SC;-49.643013;-28.986869
Balneário Arroio do Silva;SC;-49.423689;-28.980644
Araranguá;SC;-49.491809;-28.935615
Maracajá;SC;-49.460546;-28.846337
Morro Grande;SC;-49.721362;-28.800570
Meleiro;SC;-49.637821;-28.824433
Bom Jardim da Serra;SC;-49.637276;-28.337666
Forquilhinha;SC;-49.478466;-28.745359
Içara;SC;-49.308654;-28.713206
Criciúma;SC;-49.372887;-28.672267
Cocal do Sul;SC;-49.333536;-28.598569
Treviso;SC;-49.463367;-28.509661
Urussanga;SC;-49.323814;-28.518049
Lauro Muller;SC;-49.403480;-28.385899
Orleans;SC;-49.298616;-28.348674
Lages;SC;-50.325862;-27.814966
Painel;SC;-50.097175;-27.923413
São Joaquim;SC;-49.945715;-28.288709
Urupema;SC;-49.872882;-27.955674
Palmeira;SC;-50.157717;-27.583013
Otacílio Costa;SC;-50.123113;-27.478917
Bocaina do Sul;SC;-49.942279;-27.745494
Agrolândia;SC;-49.822049;-27.408685
Braço do Trombudo;SC;-49.882145;-27.358578
Rio Rufino;SC;-49.775373;-27.859191
Urubici;SC;-49.592547;-28.015663
Bom Retiro;SC;-49.486994;-27.798993
Petrolândia;SC;-49.693747;-27.534630
Chapadão do Lageado;SC;-49.553879;-27.590507
Atalanta;SC;-49.778863;-27.421916
Trombudo Central;SC;-49.792953;-27.303296
Agronômica;SC;-49.707996;-27.266154
Ituporanga;SC;-49.596253;-27.410063
Aurora;SC;-49.629525;-27.309786
Alfredo Wagner;SC;-49.327346;-27.700096
Imbuia;SC;-49.421766;-27.490820
Vidal Ramos;SC;-49.359339;-27.388643
Presidente Nereu;SC;-49.388938;-27.276830
Morro da Fumaça;SC;-49.216937;-28.651117
Sangão;SC;-49.132181;-28.632638
Jaguaruna;SC;-49.029580;-28.614598
Treze de Maio;SC;-49.156535;-28.553662
Pedras Grandes;SC;-49.194910;-28.433862
São Ludgero;SC;-49.180631;-28.314375
Gravatal;SC;-49.042683;-28.320763
Tubarão;SC;-49.014371;-28.471310
Capivari de Baixo;SC;-48.963144;-28.449773
Laguna;SC;-48.777234;-28.484287
Imaruí;SC;-48.817010;-28.333938
Grão Pará;SC;-49.225194;-28.180903
Braço do Norte;SC;-49.170056;-28.268082
Armazém;SC;-49.021506;-28.244812
Rio Fortuna;SC;-49.106816;-28.124383
Santa Rosa de Lima;SC;-49.133043;-28.033124
Anitápolis;SC;-49.131633;-27.901183
São Martinho;SC;-48.986724;-28.160883
São Bonifácio;SC;-48.932608;-27.900907
Leoberto Leal;SC;-49.278865;-27.508123
Rancho Queimado;SC;-49.019145;-27.672733
Angelina;SC;-48.987867;-27.570410
Águas Mornas;SC;-48.824347;-27.696325
Santo Amaro da Imperatriz;SC;-48.781305;-27.685182
São Pedro de Alcântara;SC;-48.804768;-27.566515
Major Gercino;SC;-48.948780;-27.419236
Nova Trento;SC;-48.929836;-27.277976
Antônio Carlos;SC;-48.766007;-27.519070
São João Batista;SC;-48.847395;-27.277186
Imbituba;SC;-48.665884;-28.228367
Paulo Lopes;SC;-48.686358;-27.960698
Garopaba;SC;-48.619896;-28.018560
Palhoça;SC;-48.669661;-27.645518
São José;SC;-48.636607;-27.613577
Rio Negro;PR;-49.798230;-26.095021
Campo do Tenente;PR;-49.684364;-25.980014
Lapa;PR;-49.716777;-25.767090
Piên;PR;-49.433565;-26.096470
Agudos do Sul;PR;-49.334293;-25.989900
Quitandinha;PR;-49.497327;-25.873438
Mandirituba;PR;-49.328225;-25.777036
Balsa Nova;PR;-49.629079;-25.580370
Contenda;PR;-49.535044;-25.678811
Campo Largo;PR;-49.529026;-25.452535
Araucária;PR;-49.404748;-25.585939
Fazenda Rio Grande;PR;-49.307310;-25.662354
Campo Magro;PR;-49.450092;-25.368685
Almirante Tamandaré;PR;-49.303733;-25.318819
Itaperuçu;PR;-49.345427;-25.219260
Rio Branco do Sul;PR;-49.311546;-25.189189
Botuverá;SC;-49.068892;-27.200749
Indaial;SC;-49.235417;-26.899247
Timbó;SC;-49.269039;-26.824640
Rio dos Cedros;SC;-49.271828;-26.739777
Blumenau;SC;-49.070904;-26.915501
Guabiruba;SC;-48.980445;-27.080811
Brusque;SC;-48.910663;-27.097706
Canelinha;SC;-48.765791;-27.261609
Gaspar;SC;-48.953428;-26.933597
Ilhota;SC;-48.825116;-26.902284
Pomerode;SC;-49.178456;-26.738355
Jaraguá do Sul;SC;-49.071250;-26.485083
Corupá;SC;-49.245968;-26.424640
Schroeder;SC;-49.074041;-26.411571
Massaranduba;SC;-49.005351;-26.610868
Luiz Alves;SC;-48.932240;-26.715136
São João do Itaperiú;SC;-48.768277;-26.621276
Guaramirim;SC;-49.002628;-26.468753
Joinville;SC;-48.848675;-26.304497
Tijucas;SC;-48.632221;-27.235425
Camboriú;SC;-48.650338;-27.024078
Porto Belo;SC;-48.546865;-27.158569
Bombinhas;SC;-48.514561;-27.138245
Itapema;SC;-48.616038;-27.086070
Balneário Camboriú;SC;-48.635152;-26.992594
Itajaí;SC;-48.670475;-26.910097
Navegantes;SC;-48.654593;-26.894293
Balneário Piçarras;SC;-48.676290;-26.769304
Penha;SC;-48.646525;-26.775418
Barra Velha;SC;-48.693338;-26.636961
Araquari;SC;-48.718825;-26.375408
São Francisco do Sul;SC;-48.634372;-26.257917
Balneário Barra do Sul;SC;-48.612317;-26.459708
Campo Alegre;SC;-49.267588;-26.195027
Tijucas do Sul;PR;-49.195017;-25.931054
Garuva;SC;-48.852026;-26.029185
São José dos Pinhais;PR;-49.203097;-25.531343
Pinhais;PR;-49.192670;-25.442949
Piraquara;PR;-49.062411;-25.442171
Colombo;PR;-49.226160;-25.292487
Quatro Barras;PR;-49.076306;-25.367317
Campina Grande do Sul;PR;-49.055096;-25.304390
Bocaiúva do Sul;PR;-49.114131;-25.206591
Morretes;PR;-48.834533;-25.474365
Itapoá;SC;-48.618188;-26.115760
Guaratuba;PR;-48.575223;-25.881672
Matinhos;PR;-48.549012;-25.823655
Antonina;PR;-48.719113;-25.438597
Pontal do Paraná;PR;-48.511062;-25.673533
Paranaguá;PR;-48.522528;-25.516078
Guaraqueçaba;PR;-48.320426;-25.307056
Ponta Grossa;PR;-50.166787;-25.091622
Carambeí;PR;-50.098624;-24.915237
Castro;PR;-50.010767;-24.789086
Ventania;PR;-50.237577;-24.245820
Piraí do Sul;PR;-49.943312;-24.530637
Arapoti;PR;-49.828453;-24.154798
Jaguariaíva;PR;-49.706642;-24.243890
Doutor Ulysses;PR;-49.421931;-24.566546
Ibaiti;PR;-50.193208;-23.847822
Japira;PR;-50.142211;-23.814227
Jaboti;PR;-50.072904;-23.743502
Conselheiro Mairinck;PR;-50.170803;-23.626382
Pinhalão;PR;-50.053571;-23.798176
Tomazina;PR;-49.949948;-23.779620
Siqueira Campos;PR;-49.830416;-23.687481
Jundiaí do Sul;PR;-50.249579;-23.435671
Abatiá;PR;-50.313281;-23.304863
Santo Antônio da Platina;PR;-50.081478;-23.295889
Barra do Jacaré;PR;-50.184162;-23.116041
Guapirama;PR;-50.040699;-23.520350
Quatiguá;PR;-49.916010;-23.567056
Joaquim Távora;PR;-49.908998;-23.498654
Jacarezinho;PR;-49.973889;-23.159115
Iporanga;SP;-48.597119;-24.584718
Guapiara;SP;-48.529549;-24.189210
Itaberá;SP;-49.139987;-23.863827
Itapeva;SP;-48.876433;-23.978823
Taquarituba;SP;-49.240954;-23.530709
Itaí;SP;-49.092005;-23.421290
Arandu;SP;-49.048661;-23.138559
Avaré;SP;-48.925052;-23.106732
São Sebastião do Paraíso;MG;-46.983703;-20.916734
Capetinga;MG;-47.057141;-20.616314
Ibiraci;MG;-47.122150;-20.461058
Pratápolis;MG;-46.862402;-20.741050
Fortaleza de Minas;MG;-46.712029;-20.850765
Cambuí;MG;-46.057228;-22.611537
Bertioga;SP;-46.139586;-23.848568
Guarulhos;SP;-46.533347;-23.453758
Mairiporã;SP;-46.589695;-23.317130
Atibaia;SP;-46.556262;-23.117059
Bom Jesus dos Perdões;SP;-46.467474;-23.135579
Ferraz de Vasconcelos;SP;-46.370970;-23.541056
Poá;SP;-46.347292;-23.533285
Itaquaquecetuba;SP;-46.345724;-23.483481
Suzano;SP;-46.311181;-23.544828
Arujá;SP;-46.319983;-23.396476
Mogi das Cruzes;SP;-46.185410;-23.520820
Nazaré Paulista;SP;-46.398295;-23.174731
Santa Isabel;SP;-46.223668;-23.317194
Igaratá;SP;-46.156985;-23.203660
Conchas;SP;-48.013391;-23.015430
Anhembi;SP;-48.133595;-22.792959
Santa Maria da Serra;SP;-48.159288;-22.566088
Laranjal Paulista;SP;-47.837549;-23.050564
São Pedro;SP;-47.909579;-22.548256
Águas de São Pedro;SP;-47.873395;-22.597749
Torrinha;SP;-48.173140;-22.423721
Brotas;SP;-48.125115;-22.279476
Ribeirão Bonito;SP;-48.182041;-22.068536
Charqueada;SP;-47.775468;-22.509580
Ipeúna;SP;-47.715086;-22.435513
Itirapina;SP;-47.816594;-22.256239
Saltinho;SP;-47.675371;-22.844167
Rio das Pedras;SP;-47.604657;-22.841723
Rafard;SP;-47.531795;-23.010508
Capivari;SP;-47.507150;-22.995144
Mombuca;SP;-47.558957;-22.928541
Piracicaba;SP;-47.647612;-22.733801
Iracemápolis;SP;-47.522963;-22.583234
Elias Fausto;SP;-47.368153;-23.042822
Monte Mor;SP;-47.312182;-22.945043
Sumaré;SP;-47.272823;-22.820416
Cambará;PR;-50.075281;-23.042273
Ibirarema;SP;-50.073901;-22.818537
Palmital;SP;-50.217964;-22.785849
Platina;SP;-50.210422;-22.637142
Salto Grande;SP;-49.983134;-22.889389
Manduri;SP;-49.320164;-23.005569
Óleo;SP;-49.341924;-22.943461
Espírito Santo do Turvo;SP;-49.434119;-22.692540
Paulistânia;SP;-49.400845;-22.576831
Alvinlândia;SP;-49.762323;-22.443481
Ubirajara;SP;-49.661260;-22.527208
Gália;SP;-49.550356;-22.291846
Álvaro de Carvalho;SP;-49.719044;-22.084132
Garça;SP;-49.654596;-22.212516
Lucianópolis;SP;-49.522002;-22.429418
Fernão;SP;-49.518735;-22.360660
Duartina;SP;-49.408355;-22.414562
Cabrália Paulista;SP;-49.339337;-22.457560
Presidente Alves;SP;-49.438133;-22.099910
Avaí;SP;-49.335596;-22.151409
Queiroz;SP;-50.241461;-21.796865
Luiziânia;SP;-50.329438;-21.673748
Braúna;SP;-50.317489;-21.499039
Alto Alegre;SP;-50.167980;-21.581103
Guaimbê;SP;-49.898610;-21.909072
Getulina;SP;-49.931205;-21.796117
Promissão;SP;-49.859890;-21.535572
Glicério;SP;-50.212269;-21.381193
Coroados;SP;-50.285937;-21.352082
Penápolis;SP;-50.076855;-21.414765
Brejo Alegre;SP;-50.186076;-21.165147
Buritama;SP;-50.147494;-21.066064
Avanhandava;SP;-49.950893;-21.458416
Barbosa;SP;-49.951816;-21.265661
Zacarias;SP;-50.055222;-21.050554
Planalto;SP;-49.933034;-21.034249
Júlio Mesquita;SP;-49.787294;-22.011174
Guarantã;SP;-49.591351;-21.894195
Cafelândia;SP;-49.609213;-21.803112
Lins;SP;-49.752555;-21.671820
Guaiçara;SP;-49.801287;-21.619537
Pirajuí;SP;-49.460777;-21.998986
Uru;SP;-49.284846;-21.786641
Pongaí;SP;-49.360421;-21.739560
Sabino;SP;-49.575546;-21.459340
Ubarana;SP;-49.719765;-21.164965
José Bonifácio;SP;-49.689233;-21.055062
Adolfo;SP;-49.645128;-21.232494
Mendonça;SP;-49.579059;-21.175724
Sales;SP;-49.489658;-21.342738
Irapuã;SP;-49.416398;-21.276832
Nova Aliança;SP;-49.498642;-21.015649
Urupês;SP;-49.293103;-21.203207
Potirendaba;SP;-49.381509;-21.042789
Cerqueira César;SP;-49.165524;-23.037999
Águas de Santa Bárbara;SP;-49.242133;-22.881186
Iaras;SP;-49.163377;-22.868199
Borebi;SP;-48.970749;-22.572763
Lençóis Paulista;SP;-48.803683;-22.602693
Piratininga;SP;-49.133869;-22.414160
Bauru;SP;-49.087142;-22.324569
Agudos;SP;-48.986320;-22.469433
Pederneiras;SP;-48.778113;-22.351086
Arealva;SP;-48.913511;-22.031026
Boracéia;SP;-48.780751;-22.192645
Pratânia;SP;-48.663618;-22.811183
Areiópolis;SP;-48.668052;-22.667216
São Manuel;SP;-48.572316;-22.732081
Botucatu;SP;-48.443706;-22.883697
Macatuba;SP;-48.710229;-22.500220
Igaraçu do Tietê;SP;-48.559672;-22.509044
Itaju;SP;-48.811549;-21.985691
Ibitinga;SP;-48.831903;-21.756230
Itápolis;SP;-48.814910;-21.594224
Novo Horizonte;SP;-49.223409;-21.465145
Marapoama;SP;-49.129968;-21.258719
Itajobi;SP;-49.062863;-21.312294
Ibirá;SP;-49.244833;-21.083032
Elisiário;SP;-49.114594;-21.167754
Catiguá;SP;-49.061620;-21.051861
Santa Adélia;SP;-48.806340;-21.242662
Catanduva;SP;-48.977015;-21.131381
Pindorama;SP;-48.908610;-21.185300
Novais;SP;-48.914091;-20.989280
Ariranha;SP;-48.790418;-21.187160
Embaúba;SP;-48.832452;-20.979595
Palmares Paulista;SP;-48.803656;-21.085369
Paraíso;SP;-48.776105;-21.015911
Nova Europa;SP;-48.570468;-21.776470
Tabatinga;SP;-48.689577;-21.723863
Boa Esperança do Sul;SP;-48.390622;-21.991832
Gavião Peixoto;SP;-48.495660;-21.836658
Dobrada;SP;-48.393476;-21.515533
Matão;SP;-48.363968;-21.602511
Cândido Rodrigues;SP;-48.632719;-21.327531
Fernando Prestes;SP;-48.687384;-21.266061
Taquaritinga;SP;-48.510345;-21.404861
Monte Alto;SP;-48.497066;-21.265545
Vista Alegre do Alto;SP;-48.628383;-21.169222
Pirangi;SP;-48.660733;-21.088600
Taiaçu;SP;-48.511203;-21.143144
Santa Ernestina;SP;-48.395317;-21.461769
Jaboticabal;SP;-48.325223;-21.252037
Taiúva;SP;-48.452816;-21.122333
Taquaral;SP;-48.412553;-21.073730
Cajati;SP;-48.122347;-24.732390
Jacupiranga;SP;-48.006402;-24.696326
Cananéia;SP;-47.934074;-25.014428
Pariquera-Açu;SP;-47.874152;-24.714652
Eldorado;SP;-48.114085;-24.528090
Sete Barras;SP;-47.927943;-24.382048
Registro;SP;-47.844895;-24.497942
Ilha Comprida;SP;-47.538275;-24.730743
Iguape;SP;-47.553659;-24.699015
Juquiá;SP;-47.642640;-24.310084
Miracatu;SP;-47.462458;-24.276639
Pedro de Toledo;SP;-47.235370;-24.276391
São Miguel Arcanjo;SP;-47.993549;-23.878197
Pilar do Sul;SP;-47.722151;-23.807716
Sarapuí;SP;-47.824863;-23.639740
Guareí;SP;-48.183715;-23.371359
Itapetininga;SP;-48.048326;-23.588607
Torre de Pedra;SP;-48.195507;-23.246173
Porangaba;SP;-48.119464;-23.176150
Quadra;SP;-48.054657;-23.299332
Alambari;SP;-47.897971;-23.550338
Tatuí;SP;-47.846120;-23.348731
Capela do Alto;SP;-47.738834;-23.468471
Cesário Lange;SP;-47.954453;-23.226035
Pereiras;SP;-47.972005;-23.080370
Jumirim;SP;-47.786821;-23.088361
Cerquilho;SP;-47.745862;-23.166487
Tietê;SP;-47.716378;-23.110098
Tapiraí;SP;-47.506248;-23.961157
Salto de Pirapora;SP;-47.574322;-23.647387
Piedade;SP;-47.425572;-23.713941
Ibiúna;SP;-47.223037;-23.659610
Araçoiaba da Serra;SP;-47.616605;-23.502901
Iperó;SP;-47.692717;-23.351296
Boituva;SP;-47.678574;-23.285531
Porto Feliz;SP;-47.525120;-23.209318
Votorantim;SP;-47.438753;-23.544590
Sorocaba;SP;-47.445073;-23.496886
Alumínio;SP;-47.254571;-23.530553
Itu;SP;-47.292688;-23.254397
Salto;SP;-47.293087;-23.199592
Indaiatuba;SP;-47.210093;-23.081591
Itariri;SP;-47.173619;-24.283404
Peruíbe;SP;-47.001200;-24.311974
Itanhaém;SP;-46.787986;-24.173633
Juquitiba;SP;-47.065332;-23.924381
São Lourenço da Serra;SP;-46.943160;-23.849134
Vargem Grande Paulista;SP;-47.022038;-23.599338
Embu-Guaçu;SP;-46.813593;-23.829717
Cotia;SP;-46.919020;-23.602177
Itapecerica da Serra;SP;-46.857193;-23.716135
Embu;SP;-46.857854;-23.643717
Taboão da Serra;SP;-46.752637;-23.601867
Mairinque;SP;-47.184995;-23.539822
São Roque;SP;-47.135670;-23.522598
Araçariguama;SP;-47.060825;-23.436597
Itapevi;SP;-46.932747;-23.548774
Pirapora do Bom Jesus;SP;-46.999103;-23.396487
Cabreúva;SP;-47.136251;-23.305330
Itupeva;SP;-47.059262;-23.152635
Louveira;SP;-46.948369;-23.085572
Jandira;SP;-46.902314;-23.527537
Barueri;SP;-46.879042;-23.505689
Carapicuíba;SP;-46.840676;-23.523471
Santana de Parnaíba;SP;-46.922215;-23.442514
Cajamar;SP;-46.878146;-23.355014
Osasco;SP;-46.791555;-23.532390
Caieiras;SP;-46.739740;-23.360729
Várzea Paulista;SP;-46.823444;-23.213582
Jundiaí;SP;-46.897358;-23.185218
Franco da Rocha;SP;-46.729011;-23.322882
Francisco Morato;SP;-46.744781;-23.279248
Campo Limpo Paulista;SP;-46.788919;-23.207791
Jarinu;SP;-46.728015;-23.103855
Mongaguá;SP;-46.626526;-24.080901
Praia Grande;SP;-46.412057;-24.008378
Cubatão;SP;-46.423968;-23.891121
Diadema;SP;-46.620520;-23.681347
São Bernardo do Campo;SP;-46.564617;-23.691412
Santo André;SP;-46.543154;-23.673730
São Caetano do Sul;SP;-46.554797;-23.622870
Mauá;SP;-46.461263;-23.667670
Ribeirão Pires;SP;-46.405805;-23.706669
São Vicente;SP;-46.388333;-23.957353
Santos;SP;-46.335042;-23.953543
Guarujá;SP;-46.257959;-23.988798
Rio Grande da Serra;SP;-46.397084;-23.743724
Paulínia;SP;-47.148776;-22.754178
Artur Nogueira;SP;-47.172679;-22.572737
Jaguariúna;SP;-46.985062;-22.703740
Holambra;SP;-47.048694;-22.640513
Itatiba;SP;-46.846353;-23.003497
Ituiutaba;MG;-49.463945;-18.977191
Cedral;SP;-49.266426;-20.900949
Uchoa;SP;-49.171295;-20.951106
Guapiaçu;SP;-49.217201;-20.795885
Tabapuã;SP;-49.030740;-20.960171
Altair;SP;-49.057073;-20.524207
Olímpia;SP;-48.910625;-20.736634
Cajobi;SP;-48.806336;-20.877287
Severínia;SP;-48.805380;-20.810753
Guaraci;SP;-48.939107;-20.497660
Icém;SP;-49.191546;-20.339067
Nova Ponte;MG;-47.677947;-19.146096
Santa Juliana;MG;-47.532251;-19.310772
Pedrinópolis;MG;-47.457888;-19.224098
Romaria;MG;-47.578221;-18.883807
Iraí de Minas;MG;-47.460972;-18.981948
Perdizes;MG;-47.296299;-19.343379
São Tomás de Aquino;MG;-47.096195;-20.779063
Santa Cruz da Conceição;SP;-47.451196;-22.140542
Araras;SP;-47.384235;-22.357238
Engenheiro Coelho;SP;-47.211045;-22.483573
Leme;SP;-47.384109;-22.180857
Araraquara;SP;-48.178014;-21.784511
Ibaté;SP;-47.988211;-21.958358
Américo Brasiliense;SP;-48.114670;-21.728771
Motuca;SP;-48.153764;-21.510287
Santa Lúcia;SP;-48.088469;-21.684982
Rincão;SP;-48.072838;-21.589429
São Carlos;SP;-47.885971;-22.017395
Guariba;SP;-48.231580;-21.359380
Guatapará;SP;-48.035588;-21.494444
Pradópolis;SP;-48.067869;-21.362578
Barrinha;SP;-48.163614;-21.186374
Pitangueiras;SP;-48.220951;-21.013203
Dumont;SP;-47.975622;-21.232401
Sertãozinho;SP;-47.987496;-21.131596
Pontal;SP;-48.042261;-21.021565
Cravinhos;SP;-47.732427;-21.337959
Ribeirão Preto;SP;-47.809875;-21.169923
Jardinópolis;SP;-47.760583;-21.017612
Descalvado;SP;-47.618105;-21.900211
Porto Ferreira;SP;-47.487034;-21.849814
Luís Antônio;SP;-47.700838;-21.550625
Santa Rita do Passa Quatro;SP;-47.478000;-21.708323
Pirassununga;SP;-47.425746;-21.995982
Santa Cruz das Palmeiras;SP;-47.248023;-21.823475
Tambaú;SP;-47.270344;-21.702920
São Simão;SP;-47.551755;-21.473171
Serra Azul;SP;-47.560243;-21.307381
Serrana;SP;-47.595239;-21.204336
Brodowski;SP;-47.657217;-20.984532
Santa Rosa de Viterbo;SP;-47.362213;-21.477568
Santa Cruz da Esperança;SP;-47.430381;-21.295065
Cajuru;SP;-47.303022;-21.274934
Altinópolis;SP;-47.371200;-21.021364
Campinas;SP;-47.065950;-22.905346
Valinhos;SP;-46.997367;-22.969805
Vinhedo;SP;-46.983312;-23.030184
Morungaba;SP;-46.789601;-22.881071
Tuiuti;SP;-46.693746;-22.819324
Pedreira;SP;-46.894846;-22.741347
Santo Antônio de Posse;SP;-46.919190;-22.602873
Amparo;SP;-46.772022;-22.708790
Monte Alegre do Sul;SP;-46.680980;-22.681699
Serra Negra;SP;-46.703271;-22.613941
Conchal;SP;-47.172927;-22.337512
Mogi Mirim;SP;-46.950514;-22.431878
Mogi Guaçu;SP;-46.942800;-22.367453
Estiva Gerbi;SP;-46.948111;-22.271314
Aguaí;SP;-46.973502;-22.057207
Itapira;SP;-46.822434;-22.435731
Espírito Santo do Pinhal;SP;-46.747670;-22.190871
Santo Antônio do Jardim;SP;-46.684487;-22.112076
Bragança Paulista;SP;-46.541880;-22.952681
Vargem;SP;-46.412447;-22.887046
Gurinhatã;MG;-49.787631;-19.214281
Fronteira;MG;-49.198423;-20.274848
Frutal;MG;-48.935497;-20.025875
Monte Azul Paulista;SP;-48.638717;-20.906490
Colina;SP;-48.538670;-20.711443
Barretos;SP;-48.569832;-20.553146
Bebedouro;SP;-48.479083;-20.949077
Viradouro;SP;-48.292974;-20.873382
Terra Roxa;SP;-48.331442;-20.787015
Jaborandi;SP;-48.411167;-20.688422
Planura;MG;-48.700001;-20.137601
Cana Verde;MG;-45.180062;-21.023207
Perdões;MG;-45.089582;-21.093225
Canas;SP;-45.052117;-22.700344
Cachoeira Paulista;SP;-45.015384;-22.666498
Cruzeiro;SP;-44.968960;-22.572764
Lavrinhas;SP;-44.902359;-22.570047
Silveiras;SP;-44.852157;-22.663840
Areias;SP;-44.699240;-22.578597
São José do Barreiro;SP;-44.577437;-22.641353
Passa Quatro;MG;-44.970926;-22.387076
Itanhandu;MG;-44.938243;-22.294191
Itamonte;MG;-44.868021;-22.285912
São Sebastião do Rio Verde;MG;-44.976116;-22.218293
Pouso Alto;MG;-44.974813;-22.196370
São Lourenço;MG;-45.050597;-22.116588
Soledade de Minas;MG;-45.046417;-22.055432
Queluz;SP;-44.778105;-22.531171
Itatiaia;RJ;-44.567485;-22.489710
Alagoa;MG;-44.641344;-22.170976
Angra dos Reis;RJ;-44.319560;-23.001074
Arapeí;SP;-44.444127;-22.671744
Bananal;SP;-44.328114;-22.681932
Jaíba;MG;-43.668802;-15.343224
Pedro Canário;ES;-39.957365;-18.300418
Carlos Chagas;MG;-40.772276;-17.697335
Machacalis;MG;-40.724476;-17.072273
Santa Helena de Minas;MG;-40.672720;-16.970719
Umburatiba;MG;-40.577937;-17.254804
Bertópolis;MG;-40.579973;-17.059009
Serra dos Aimorés;MG;-40.245252;-17.787172
Lajedão;BA;-40.338307;-17.605641
Ibirapuã;BA;-40.112907;-17.683151
Medeiros Neto;BA;-40.223776;-17.370676
Altamira do Maranhão;MA;-45.470616;-4.165980
Vitorino Freire;MA;-45.250524;-4.281836
Olho d'Água das Cunhãs;MA;-45.116312;-4.134174
Satubinha;MA;-45.245716;-4.049131
Bela Vista do Maranhão;MA;-45.307487;-3.726175
Pio XII;MA;-45.175942;-3.893151
Bom Jardim;MA;-45.606004;-3.541290
Santa Inês;MA;-45.377450;-3.651118
Pindaré-Mirim;MA;-45.341988;-3.609851
Monção;MA;-45.249600;-3.481254
Igarapé do Meio;MA;-45.211372;-3.657714
Porto Firme;MG;-43.083410;-20.664191
Guaraciaba;MG;-43.009389;-20.571571
Ouro Preto;MG;-43.512045;-20.379570
Mariana;MG;-43.414034;-20.376514
Caputira;MG;-42.268320;-20.170342
Vermelho Novo;MG;-42.268797;-20.040618
São João do Manhuaçu;MG;-42.153283;-20.393315
Luisburgo;MG;-42.097642;-20.446753
Alto Jequitibá;MG;-41.966988;-20.420845
Manhumirim;MG;-41.958933;-20.359116
Manhuaçu;MG;-42.027987;-20.257226
Reduto;MG;-41.984835;-20.240089
Santa Bárbara do Leste;MG;-42.145746;-19.975295
Simonésia;MG;-42.009052;-20.134097
São Domingos do Prata;MG;-42.971031;-19.867822
Dionísio;MG;-42.770087;-19.843252
Marliéria;MG;-42.732693;-19.709601
Antônio Dias;MG;-42.873243;-19.649096
Jaguaraçu;MG;-42.749820;-19.647001
São José do Goiabal;MG;-42.703550;-19.921431
Timóteo;MG;-42.647130;-19.581086
Coronel Fabriciano;MG;-42.627585;-19.517879
Pinhalzinho;SP;-46.589679;-22.781115
Pedra Bela;SP;-46.445550;-22.790174
Socorro;SP;-46.525118;-22.590264
Piracaia;SP;-46.359374;-23.052545
Joanópolis;SP;-46.274118;-22.926999
Extrema;MG;-46.317816;-22.853991
Toledo;MG;-46.372772;-22.742133
Munhoz;MG;-46.361970;-22.609172
Itapeva;MG;-46.224053;-22.766488
Camanducaia;MG;-46.149444;-22.751534
Senador Amaral;MG;-46.176329;-22.586897
Lindóia;SP;-46.650035;-22.522562
Águas de Lindóia;SP;-46.631441;-22.473305
Monte Sião;MG;-46.572980;-22.433538
Jacutinga;MG;-46.616575;-22.285970
Albertina;MG;-46.613912;-22.201775
Andradas;MG;-46.572372;-22.069487
Ibitiúra de Minas;MG;-46.436810;-22.060402
Bueno Brandão;MG;-46.349102;-22.438321
Inconfidentes;MG;-46.326412;-22.313561
Bom Repouso;MG;-46.144036;-22.467494
Ouro Fino;MG;-46.371599;-22.277868
Santa Rita de Caldas;MG;-46.338501;-22.029192
Borda da Mata;MG;-46.165349;-22.270701
Senador José Bento;MG;-46.179226;-22.163327
Ipuiúna;MG;-46.191495;-22.101264
Casa Branca;SP;-47.085245;-21.770810
Itobi;SP;-46.974300;-21.730853
São João da Boa Vista;SP;-46.794356;-21.970747
Vargem Grande do Sul;SP;-46.891286;-21.832194
Águas da Prata;SP;-46.717622;-21.931935
São Sebastião da Grama;SP;-46.820753;-21.704109
São José do Rio Pardo;SP;-46.887303;-21.595288
Divinolândia;SP;-46.736128;-21.663709
Cássia dos Coqueiros;SP;-47.164327;-21.280082
Mococa;SP;-47.002405;-21.464731
Arceburgo;MG;-46.940056;-21.358984
Santo Antônio da Alegria;SP;-47.146372;-21.086449
Monte Santo de Minas;MG;-46.975314;-21.187310
Itamogi;MG;-47.045962;-21.075824
Guaranésia;MG;-46.796352;-21.300921
Tapiratiba;SP;-46.744825;-21.471324
Guaxupé;MG;-46.708053;-21.305007
Jacuí;MG;-46.735880;-21.013662
Poços de Caldas;MG;-46.569184;-21.779975
Caconde;SP;-46.643659;-21.527995
Caldas;MG;-46.384314;-21.918309
Bandeira do Sul;MG;-46.383347;-21.730798
Botelhos;MG;-46.391006;-21.641157
Campestre;MG;-46.238080;-21.707874
Divisa Nova;MG;-46.190385;-21.509160
Juruaia;MG;-46.573541;-21.249310
Muzambinho;MG;-46.521300;-21.369155
São Pedro da União;MG;-46.612300;-21.130975
Nova Resende;MG;-46.415732;-21.128612
Bom Jesus da Penha;MG;-46.517369;-21.014786
Cabo Verde;MG;-46.391857;-21.469945
Monte Belo;MG;-46.363462;-21.327084
Areado;MG;-46.142071;-21.357186
Alterosa;MG;-46.138666;-21.248752
Conceição da Aparecida;MG;-46.204910;-21.096024
Lourdes;SP;-50.226274;-20.966025
Nova Luzitânia;SP;-50.261683;-20.855967
Turiúba;SP;-50.113490;-20.942758
Monções;SP;-50.097537;-20.850949
Gastão Vidigal;SP;-50.191183;-20.794776
Magda;SP;-50.230542;-20.644497
Floreal;SP;-50.151282;-20.675193
Macaubal;SP;-49.968732;-20.802153
União Paulista;SP;-49.902509;-20.886227
Poloni;SP;-49.825756;-20.782909
Nhandeara;SP;-50.043596;-20.694550
Sebastianópolis do Sul;SP;-49.924997;-20.652323
Fernandópolis;SP;-50.247115;-20.280556
Meridiano;SP;-50.181055;-20.357874
Valentim Gentil;SP;-50.088928;-20.421681
Pedranópolis;SP;-50.112937;-20.247378
Indiaporã;SP;-50.290856;-19.978952
Macedônia;SP;-50.197269;-20.144375
Mira Estrela;SP;-50.139039;-19.978925
Votuporanga;SP;-49.978112;-20.423659
Parisi;SP;-50.016277;-20.303397
Congonhal;MG;-46.043031;-22.148753
Pouso Alegre;MG;-45.938935;-22.226590
Espírito Santo do Dourado;MG;-45.954770;-22.045368
Conceição dos Ouros;MG;-45.799599;-22.407845
Cachoeira de Minas;MG;-45.780866;-22.351107
São Sebastião da Bela Vista;MG;-45.754639;-22.158327
Silvianópolis;MG;-45.838517;-22.027410
Santa Rita do Sapucaí;MG;-45.703405;-22.246141
Careaçu;MG;-45.696011;-22.042379
Taubaté;SP;-45.559260;-23.010414
Tremembé;SP;-45.547526;-22.957140
Pindamonhangaba;SP;-45.461264;-22.924630
Campos do Jordão;SP;-45.583337;-22.729618
Roseira;SP;-45.306993;-22.893834
Potim;SP;-45.255205;-22.834327
Aparecida;SP;-45.232496;-22.849509
Guaratinguetá;SP;-45.193788;-22.807534
Lorena;SP;-45.119680;-22.733380
Piquete;SP;-45.186919;-22.606881
Brasópolis;MG;-45.612995;-22.470019
Piranguçu;MG;-45.494528;-22.524932
Piranguinho;MG;-45.532405;-22.394961
São José do Alegre;MG;-45.525837;-22.324269
Itajubá;MG;-45.459818;-22.422481
Wenceslau Braz;MG;-45.362636;-22.536836
Maria da Fé;MG;-45.377277;-22.304417
Natércia;MG;-45.512284;-22.115754
Heliodora;MG;-45.545325;-22.064401
Pedralva;MG;-45.465386;-22.238645
Conceição das Pedras;MG;-45.456238;-22.157647
Delfim Moreira;MG;-45.279231;-22.503632
Marmelópolis;MG;-45.164541;-22.447004
Virgínia;MG;-45.096463;-22.326424
Cristina;MG;-45.267266;-22.207982
Olímpio Noronha;MG;-45.265726;-22.068548
Dom Viçoso;MG;-45.164317;-22.251110
Álvares Florence;SP;-49.914126;-20.320341
Cardoso;SP;-49.918322;-20.080031
Nipoã;SP;-49.783278;-20.911397
Monte Aprazível;SP;-49.718420;-20.768015
Neves Paulista;SP;-49.635767;-20.842992
Jaci;SP;-49.579732;-20.880468
Bálsamo;SP;-49.586487;-20.734843
Cosmorama;SP;-49.782747;-20.475472
Tanabi;SP;-49.656319;-20.622790
Bady Bassitt;SP;-49.438500;-20.919689
Mirassol;SP;-49.520610;-20.816874
São José do Rio Preto;SP;-49.375767;-20.811289
Mirassolândia;SP;-49.461726;-20.617859
Ipiguá;SP;-49.384161;-20.655734
Onda Verde;SP;-49.292925;-20.604230
Nova Granada;SP;-49.312268;-20.532074
Américo de Campos;SP;-49.735904;-20.298476
Pontes Gestal;SP;-49.706355;-20.172692
Riolândia;SP;-49.683582;-19.986789
Palestina;SP;-49.430854;-20.390046
Orindiúva;SP;-49.346395;-20.186070
Paulo de Faria;SP;-49.400013;-20.029612
Iturama;MG;-50.196560;-19.727566
São Francisco de Sales;MG;-49.772693;-19.861122
Itapagipe;MG;-49.378137;-19.906163
Campina Verde;MG;-49.486247;-19.538150
Colômbia;SP;-48.686468;-20.176771
Guaíra;SP;-48.311970;-20.319591
Comendador Gomes;MG;-49.078894;-19.697342
Prata;MG;-48.927625;-19.308607
Pirajuba;MG;-48.702690;-19.909206
Campo Florido;MG;-48.571623;-19.763085
Conceição das Alagoas;MG;-48.383940;-19.917155
Veríssimo;MG;-48.311845;-19.665740
Uberlândia;MG;-48.274934;-18.914142
Santa Vitória;MG;-50.120759;-18.841398
Gouvelândia;GO;-50.080499;-18.623801
Ipiaçu;MG;-49.943614;-18.692716
Inaciolândia;GO;-49.988758;-18.486896
Castelândia;GO;-50.203036;-18.092103
Turvelândia;GO;-50.302376;-17.850215
Capinópolis;MG;-49.570618;-18.686221
Cachoeira Dourada;MG;-49.503876;-18.516147
Cachoeira Dourada;GO;-49.476603;-18.485893
Bom Jesus de Goiás;GO;-49.739990;-18.217254
Panamá;GO;-49.354994;-18.178264
Goiatuba;GO;-49.365807;-18.010531
Porteirão;GO;-50.165322;-17.814280
Edéia;GO;-49.929544;-17.340601
Jandaia;GO;-50.145322;-17.048144
Palminópolis;GO;-50.165240;-16.792430
Indiara;GO;-49.986162;-17.138739
Palmeiras de Goiás;GO;-49.924029;-16.804446
Vicentinópolis;GO;-49.804747;-17.732204
Joviânia;GO;-49.619687;-17.801983
Edealina;GO;-49.664401;-17.423942
Aloândia;GO;-49.476895;-17.729197
Pontalina;GO;-49.448940;-17.522513
Varjão;GO;-49.631229;-17.047062
Cezarina;GO;-49.775814;-16.971829
Mairipotaba;GO;-49.489819;-17.297538
Cromínia;GO;-49.379778;-17.288322
Aragoiânia;GO;-49.447649;-16.908699
Guapó;GO;-49.534531;-16.829656
Canápolis;MG;-49.203550;-18.721229
Centralina;MG;-49.201397;-18.585217
Araporã;MG;-49.184746;-18.435727
Itumbiara;GO;-49.215845;-18.409267
Monte Alegre de Minas;MG;-48.881023;-18.869048
Buriti Alegre;GO;-49.040441;-18.137788
Água Limpa;GO;-48.760304;-18.077082
Tupaciguara;MG;-48.698540;-18.586591
Corumbaíba;GO;-48.562607;-18.141487
Marzagão;GO;-48.641519;-17.982978
Nova Aurora;GO;-48.255172;-18.059652
Morrinhos;GO;-49.105908;-17.733369
Rio Quente;GO;-48.772462;-17.773988
Piracanjuba;GO;-49.017010;-17.302038
Professor Jamil;GO;-49.244004;-17.249665
Hidrolândia;GO;-49.226535;-16.962587
Aparecida de Goiânia;GO;-49.246856;-16.819804
Bela Vista de Goiás;GO;-48.951307;-16.969295
Nuporanga;SP;-47.742942;-20.729619
São Joaquim da Barra;SP;-47.859316;-20.581158
Ipuã;SP;-48.012881;-20.443785
Água Comprida;MG;-48.106879;-20.057570
Miguelópolis;SP;-48.030997;-20.179612
Guará;SP;-47.823626;-20.430162
Ituverava;SP;-47.790207;-20.335465
Aramina;SP;-47.787251;-20.088152
Delta;MG;-47.784080;-19.972143
Igarapava;SP;-47.746576;-20.040687
Batatais;SP;-47.592149;-20.892867
São José da Bela Vista;SP;-47.642413;-20.593548
Ribeirão Corrente;SP;-47.590394;-20.457872
Restinga;SP;-47.483254;-20.605593
Franca;SP;-47.403861;-20.535230
Patrocínio Paulista;SP;-47.280124;-20.638361
Itirapuã;SP;-47.219446;-20.641585
Buritizal;SP;-47.709636;-20.191084
Jeriquara;SP;-47.591835;-20.311567
Pedregulho;SP;-47.477450;-20.253468
Conquista;MG;-47.549196;-19.931182
Cristais Paulista;SP;-47.420915;-20.403569
Claraval;MG;-47.276828;-20.397011
Rifaina;SP;-47.429109;-20.080256
Uberaba;MG;-47.938073;-19.747205
Indianópolis;MG;-47.915457;-19.034141
Sacramento;MG;-47.450823;-19.862172
Itaú de Minas;MG;-46.752512;-20.737499
Cássia;MG;-46.920122;-20.583061
Delfinópolis;MG;-46.845554;-20.346770
Passos;MG;-46.609008;-20.719271
São João Batista do Glória;MG;-46.507966;-20.634996
Alpinópolis;MG;-46.387797;-20.863059
São José da Barra;MG;-46.312968;-20.717808
Vargem Bonita;MG;-46.368824;-20.333265
São Roque de Minas;MG;-46.363873;-20.249014
Medeiros;MG;-46.218053;-19.986510
Araxá;MG;-46.943804;-19.590176
Tapira;MG;-46.826397;-19.916619
Patrocínio;MG;-46.993419;-18.937867
Serra do Salitre;MG;-46.696053;-19.108334
Cruzeiro da Fortaleza;MG;-46.666891;-18.944029
Ibiá;MG;-46.547406;-19.474948
Pratinha;MG;-46.375537;-19.738975
Campos Altos;MG;-46.172545;-19.691378
Rio Paranaíba;MG;-46.245482;-19.186138
Carmo do Paranaíba;MG;-46.316735;-18.990952
Arapuá;MG;-46.148403;-19.026816
Araguari;MG;-48.193392;-18.645575
Cascalho Rico;MG;-47.871643;-18.577169
Grupiara;MG;-47.731787;-18.500302
Três Ranchos;GO;-47.775977;-18.353897
Anhanguera;GO;-48.220363;-18.333875
Cumari;GO;-48.151134;-18.264437
Goiandira;GO;-48.087453;-18.135158
Catalão;GO;-47.944043;-18.165633
Ouvidor;GO;-47.835480;-18.227669
João Pinheiro;MG;-46.171540;-17.739768
Biritiba-Mirim;SP;-46.040700;-23.569774
Guararema;SP;-46.036946;-23.411195
Santa Branca;SP;-45.887521;-23.393311
Jacareí;SP;-45.965814;-23.298290
São José dos Campos;SP;-45.884115;-23.189554
Salesópolis;SP;-45.846530;-23.528805
Paraibuna;SP;-45.663943;-23.387213
Jambeiro;SP;-45.694214;-23.252217
Caçapava;SP;-45.707645;-23.099204
São Sebastião;SP;-45.414314;-23.795059
Ilhabela;SP;-45.355226;-23.778533
Caraguatatuba;SP;-45.412533;-23.612535
Natividade da Serra;SP;-45.446764;-23.370665
Redenção da Serra;SP;-45.542220;-23.263763
São Luís do Paraitinga;SP;-45.308990;-23.220521
Lagoinha;SP;-45.194415;-23.084621
Ubatuba;SP;-45.083415;-23.433162
Cunha;SP;-44.957559;-23.073100
Parati;RJ;-44.716591;-23.222406
Córrego do Bom Jesus;MG;-46.024113;-22.626882
Consolação;MG;-45.925459;-22.549290
Monteiro Lobato;SP;-45.840661;-22.954413
Santo Antônio do Pinhal;SP;-45.663000;-22.827000
Gonçalves;MG;-45.855566;-22.654535
Paraisópolis;MG;-45.780295;-22.553912
Sapucaí-Mirim;MG;-45.737958;-22.740899
São Bento do Sapucaí;SP;-45.728677;-22.683744
Estiva;MG;-46.019050;-22.457694
Tocos do Moji;MG;-46.097067;-22.369797
Leopoldina;MG;-42.642123;-21.529630
Recreio;MG;-42.467600;-21.528893
Descoberto;MG;-42.961766;-21.459990
Itamarati de Minas;MG;-42.812969;-21.417935
Astolfo Dutra;MG;-42.857204;-21.318439
Dona Eusébia;MG;-42.807048;-21.319000
Ubá;MG;-42.935931;-21.120444
Rodeiro;MG;-42.858622;-21.203467
Guidoval;MG;-42.788746;-21.155034
Visconde do Rio Branco;MG;-42.836129;-21.012725
Cataguases;MG;-42.689647;-21.392390
Laranjal;MG;-42.473151;-21.371478
Santana de Cataguases;MG;-42.552357;-21.289277
Miraí;MG;-42.612209;-21.202140
Guiricema;MG;-42.720659;-21.009850
Eugenópolis;MG;-42.187834;-21.100153
Antônio Prado de Minas;MG;-42.110937;-21.019183
Natividade;RJ;-41.969663;-21.038974
Guapé;MG;-45.915233;-20.763079
Capitólio;MG;-46.049257;-20.616373
Piumhi;MG;-45.958910;-20.476230
Ilicínea;MG;-45.830849;-20.940222
Pimenta;MG;-45.804877;-20.482722
Doresópolis;MG;-45.900725;-20.286828
Bambuí;MG;-45.975370;-20.016618
Pains;MG;-45.662685;-20.370467
Iguatama;MG;-45.711114;-20.177630
Cristais;MG;-45.516727;-20.873280
Aguanil;MG;-45.391456;-20.943942
Formiga;MG;-45.426793;-20.461819
Campo Belo;MG;-45.269892;-20.893182
Candeias;MG;-45.276465;-20.769222
São José da Varginha;MG;-44.555960;-19.700566
Florestal;MG;-44.431758;-19.888043
Esmeraldas;MG;-44.306485;-19.764020
Fortuna de Minas;MG;-44.447177;-19.557755
Alfenas;MG;-45.947727;-21.425629
Carmo do Rio Claro;MG;-46.114925;-20.973609
Fama;MG;-45.828617;-21.408898
Campos Gerais;MG;-45.756900;-21.237025
Campo do Meio;MG;-45.827333;-21.112735
São Gonçalo do Sapucaí;MG;-45.589344;-21.893223
Campanha;MG;-45.400394;-21.835977
Monsenhor Paulo;MG;-45.539055;-21.757885
Elói Mendes;MG;-45.569064;-21.608806
Varginha;MG;-45.436424;-21.555581
Lambari;MG;-45.349822;-21.967088
Jesuânia;MG;-45.291122;-21.988655
Cambuquira;MG;-45.289572;-21.853985
Três Corações;MG;-45.251117;-21.692091
Três Pontas;MG;-45.510886;-21.369440
Santana da Vargem;MG;-45.500526;-21.244903
Boa Esperança;MG;-45.561203;-21.092723
Coqueiral;MG;-45.436568;-21.185757
Carmo da Cachoeira;MG;-45.220065;-21.463291
Nepomuceno;MG;-45.234956;-21.232415
São Bernardo;MA;-42.419120;-3.372229
Magalhães de Almeida;MA;-42.211743;-3.392319
Joaquim Pires;PI;-42.186462;-3.501636
Murici dos Portelas;PI;-42.094000;-3.319000
Barreirinhas;MA;-42.823241;-2.758630
Paulino Neves;MA;-42.525848;-2.720941
Santana do Maranhão;MA;-42.406388;-3.109002
Tutóia;MA;-42.275531;-2.761413
Água Doce do Maranhão;MA;-42.118903;-2.840479
Sebastião Laranjeiras;BA;-42.943438;-14.571002
Candiba;BA;-42.866694;-14.409738
Guanambi;BA;-42.779943;-14.223066
Pindaí;BA;-42.685984;-14.492128
Matina;BA;-42.843914;-13.910917
Caetité;BA;-42.486133;-14.068422
Igaporã;BA;-42.715508;-13.774025
Caculé;BA;-42.222872;-14.500281
Ibiassucê;BA;-42.257026;-14.271126
Alto Alegre do Pindaré;MA;-45.842111;-3.666886
Governador Newton Bello;MA;-45.661939;-3.432447
Zé Doca;MA;-45.655344;-3.270145
Brejo de Areia;MA;-45.581000;-4.334000
Mangaratiba;RJ;-44.040892;-22.959402
Barra Mansa;RJ;-44.175241;-22.548084
Rio Claro;RJ;-44.141874;-22.720001
Resende;RJ;-44.450910;-22.470473
Bocaina de Minas;MG;-44.397181;-22.169687
Liberdade;MG;-44.320752;-22.027491
Porto Real;RJ;-44.295167;-22.417472
Quatis;RJ;-44.259729;-22.404484
Volta Redonda;RJ;-44.099555;-22.520212
Passa-Vinte;MG;-44.236570;-22.206583
Santa Rita de Jacutinga;MG;-44.097700;-22.147386
Conceição do Rio Verde;MG;-45.087030;-21.877836
Caxambu;MG;-44.931855;-21.975253
Baependi;MG;-44.887426;-21.956994
São Thomé das Letras;MG;-44.984830;-21.725804
São Bento Abade;MG;-45.069931;-21.583927
Luminárias;MG;-44.903384;-21.514485
Cruzília;MG;-44.806678;-21.839985
Aiuruoca;MG;-44.604212;-21.973608
Minduri;MG;-44.605096;-21.679714
Lavras;MG;-45.000949;-21.248002
Ingaí;MG;-44.915158;-21.402428
Itumirim;MG;-44.872364;-21.317054
Ribeirão Vermelho;MG;-45.063680;-21.187861
Ijaci;MG;-44.923265;-21.173785
Carrancas;MG;-44.644570;-21.489765
Itutinga;MG;-44.656731;-21.300017
Ibituruna;MG;-44.747913;-21.154140
Bom Sucesso;MG;-44.753745;-21.032875
Nazareno;MG;-44.613800;-21.216779
Seritinga;MG;-44.517965;-21.913367
Carvalhos;MG;-44.463164;-22.000003
Serranos;MG;-44.512487;-21.885730
São Vicente de Minas;MG;-44.443064;-21.704217
Paracambi;RJ;-43.710836;-22.607811
Engenheiro Paulo de Frontin;RJ;-43.682738;-22.549781
Queimados;RJ;-43.551808;-22.710172
Pinheiral;RJ;-44.002151;-22.517211
Barra do Piraí;RJ;-43.826918;-22.471531
Rio Preto;MG;-43.829325;-22.086092
Mendes;RJ;-43.731215;-22.524548
Vassouras;RJ;-43.668555;-22.405889
Valença;RJ;-43.712880;-22.244496
Rio das Flores;RJ;-43.585632;-22.169184
Nova Iguaçu;RJ;-43.460325;-22.755635
Nilópolis;RJ;-43.423344;-22.805658
Belford Roxo;RJ;-43.399210;-22.764042
São João de Meriti;RJ;-43.372920;-22.805776
Duque de Caxias;RJ;-43.304895;-22.785801
Niterói;RJ;-43.103367;-22.883210
São Gonçalo;RJ;-43.063351;-22.826790
Magé;RJ;-43.031483;-22.663151
Miguel Pereira;RJ;-43.480275;-22.457242
Paty do Alferes;RJ;-43.428534;-22.430896
Paraíba do Sul;RJ;-43.304004;-22.158462
Petrópolis;RJ;-43.192613;-22.519963
Três Rios;RJ;-43.218533;-22.116510
Comendador Levy Gasparian;RJ;-43.214018;-22.040425
Areal;RJ;-43.111784;-22.228308
Olaria;MG;-43.935558;-21.859789
Lima Duarte;MG;-43.793412;-21.838597
Santa Rita de Ibitipoca;MG;-43.916278;-21.565827
Santa Bárbara do Monte Verde;MG;-43.702727;-21.959179
Pedro Teixeira;MG;-43.742949;-21.707630
Bias Fortes;MG;-43.757366;-21.601961
Ibertioga;MG;-43.963896;-21.433032
Dores de Campos;MG;-44.020680;-21.113944
Barroso;MG;-43.972039;-21.190659
Antônio Carlos;MG;-43.745130;-21.321031
Santos Dumont;MG;-43.549876;-21.463377
Santa Bárbara do Tugúrio;MG;-43.560746;-21.243118
Barbacena;MG;-43.770266;-21.221446
Alfredo Vasconcelos;MG;-43.771767;-21.153521
Ressaquinha;MG;-43.759830;-21.064190
Desterro do Melo;MG;-43.517834;-21.142995
Senhora dos Remédios;MG;-43.581213;-21.035080
Belmiro Braga;MG;-43.408419;-21.943992
Simão Pereira;MG;-43.308794;-21.963960
Juiz de Fora;MG;-43.339759;-21.759520
Matias Barbosa;MG;-43.313475;-21.868953
Ewbank da Câmara;MG;-43.506803;-21.549819
Piau;MG;-43.312992;-21.509645
Santana do Deserto;MG;-43.158323;-21.951208
Chiador;MG;-43.061671;-21.999572
Pequeri;MG;-43.114487;-21.834113
Mar de Espanha;MG;-43.006206;-21.870728
Chácara;MG;-43.214990;-21.673256
Coronel Pacheco;MG;-43.255989;-21.589751
Goianá;MG;-43.195664;-21.536045
Guarará;MG;-43.033434;-21.730352
Bicas;MG;-43.055958;-21.723164
Rochedo de Minas;MG;-43.016547;-21.628397
São João Nepomuceno;MG;-43.006876;-21.538133
Oliveira Fortes;MG;-43.449893;-21.340054
Paiva;MG;-43.408756;-21.291287
Aracitaba;MG;-43.373620;-21.344619
Alto Rio Doce;MG;-43.406683;-21.028122
Mercês;MG;-43.333662;-21.197585
Tabuleiro;MG;-43.238055;-21.363248
Rio Pomba;MG;-43.169625;-21.271152
Rio Novo;MG;-43.116805;-21.464899
Guarani;MG;-43.032777;-21.356334
Piraúba;MG;-43.017218;-21.282492
Silveirânia;MG;-43.212843;-21.161480
Dores do Turvo;MG;-43.183430;-20.978477
Tocantins;MG;-43.012702;-21.177411
Divinésia;MG;-43.000263;-20.991709
Maricá;RJ;-42.824587;-22.935434
Itaboraí;RJ;-42.863905;-22.756504
Saquarema;RJ;-42.509936;-22.929181
Tanguá;RJ;-42.720189;-22.742305
Rio Bonito;RJ;-42.627574;-22.718110
Guapimirim;RJ;-42.989537;-22.534702
Teresópolis;RJ;-42.975190;-22.416464
São José do Vale do Rio Preto;RJ;-42.932727;-22.152497
Cachoeiras de Macacu;RJ;-42.652346;-22.465802
Nova Friburgo;RJ;-42.537692;-22.293224
Sumidouro;RJ;-42.676148;-22.048461
Duas Barras;RJ;-42.523229;-22.053638
Araruama;RJ;-42.332625;-22.869651
Iguaba Grande;RJ;-42.229916;-22.849462
Silva Jardim;RJ;-42.396119;-22.657389
São Pedro da Aldeia;RJ;-42.102596;-22.842859
Arraial do Cabo;RJ;-42.026715;-22.977448
Cabo Frio;RJ;-42.028595;-22.889430
Casimiro de Abreu;RJ;-42.206589;-22.481235
Bom Jardim;RJ;-42.425095;-22.154526
Cordeiro;RJ;-42.364834;-22.026726
Rio das Ostras;RJ;-41.947509;-22.517378
Paraopeba;MG;-44.404367;-19.273192
Cordisburgo;MG;-44.322415;-19.122393
Araçaí;MG;-44.249309;-19.195507
Catas Altas;MG;-43.406115;-20.073403
Barão de Cocais;MG;-43.475524;-19.938943
Belo Oriente;MG;-42.482795;-19.219906
Braúnas;MG;-42.709896;-19.056160
Açucena;MG;-42.541881;-19.067105
Córrego Novo;MG;-42.398816;-19.836096
Posse;GO;-46.370407;-14.085943
Monte Alegre de Goiás;GO;-46.892813;-13.255206
Arraias;TO;-46.935891;-12.928670
Campos Belos;GO;-46.768112;-13.034984
Divinópolis de Goiás;GO;-46.399879;-13.285314
São Domingos;GO;-46.318968;-13.403749
Novo Alegre;TO;-46.571252;-12.921687
Combinado;TO;-46.538775;-12.791717
Lavandeira;TO;-46.509894;-12.784730
Trombas;GO;-48.741710;-13.507890
Montividiu do Norte;GO;-48.685316;-13.348471
Lontra;MG;-44.306037;-15.901295
Patis;MG;-44.078685;-16.077253
Japonvar;MG;-44.275829;-15.989139
Varzelândia;MG;-44.027839;-15.699154
Verdelândia;MG;-43.612137;-15.584522
Penalva;MA;-45.176808;-3.276744
Araguanã;MA;-45.658946;-2.946441
Nova Olinda do Maranhão;MA;-45.695274;-2.842272
Marilac;MG;-42.082207;-18.507897
Santa Maria do Suaçuí;MG;-42.413899;-18.189581
Água Boa;MG;-42.380648;-17.991373
São José da Safira;MG;-42.143061;-18.324266
Malacacheta;MG;-42.076907;-17.845647
Franciscópolis;MG;-42.009382;-17.957766
Santana do Jacaré;MG;-45.128506;-20.900714
Camacho;MG;-45.159327;-20.629367
Itapecerica;MG;-45.126994;-20.470372
Arcos;MG;-45.537345;-20.286333
Japaraíba;MG;-45.501510;-20.144184
Lagoa da Prata;MG;-45.540149;-20.023728
Pedra do Indaiá;MG;-45.210653;-20.256346
Santo Antônio do Monte;MG;-45.294724;-20.085003
Araújos;MG;-45.167115;-19.940519
Tapiraí;MG;-46.022072;-19.893587
Córrego Danta;MG;-45.903228;-19.819775
Santa Rosa da Serra;MG;-45.961081;-19.518610
Luz;MG;-45.679444;-19.791085
Estrela do Indaiá;MG;-45.785950;-19.516888
Serra da Saudade;MG;-45.794976;-19.444669
São Gotardo;MG;-46.046475;-19.308727
Matutina;MG;-45.966350;-19.217912
Tiros;MG;-45.962648;-19.003730
Cedro do Abaeté;MG;-45.711994;-19.145767
Moema;MG;-45.412687;-19.838731
Dores do Indaiá;MG;-45.592741;-19.462789
Bom Despacho;MG;-45.262167;-19.738621
Quartel Geral;MG;-45.556915;-19.270328
Abaeté;MG;-45.444413;-19.155125
Paineiras;MG;-45.532064;-18.899345
Martinho Campos;MG;-45.243411;-19.330573
Santo Antônio do Amparo;MG;-44.917608;-20.943044
São Francisco de Paula;MG;-44.983763;-20.703625
Oliveira;MG;-44.829037;-20.698218
Carmo da Mata;MG;-44.873500;-20.557532
Carmópolis de Minas;MG;-44.633558;-20.539562
São Sebastião do Oeste;MG;-45.006332;-20.275843
Perdigão;MG;-45.077988;-19.941058
Divinópolis;MG;-44.891223;-20.144646
São Gonçalo do Pará;MG;-44.859310;-19.982173
Cláudio;MG;-44.767332;-20.443672
Carmo do Cajuru;MG;-44.766430;-20.191170
Igaratinga;MG;-44.706324;-19.947597
Itaúna;MG;-44.580112;-20.081798
São Tiago;MG;-44.509837;-20.907471
Passa Tempo;MG;-44.492635;-20.653918
Piracema;MG;-44.478323;-20.508866
Desterro de Entre Rios;MG;-44.333388;-20.665040
Resende Costa;MG;-44.240710;-20.917072
Lagoa Dourada;MG;-44.079717;-20.913858
Piedade dos Gerais;MG;-44.224335;-20.471513
Entre Rios de Minas;MG;-44.065392;-20.670630
Itaguara;MG;-44.487507;-20.394695
Crucilândia;MG;-44.333425;-20.392314
Sarzedo;MG;-44.144600;-20.036667
Ibirité;MG;-44.056882;-20.025188
Nova Serrana;MG;-44.984661;-19.871299
Leandro Ferreira;MG;-45.027855;-19.719307
Conceição do Pará;MG;-44.894511;-19.745599
Pitangui;MG;-44.896362;-19.674112
Onça de Pitangui;MG;-44.805771;-19.727551
Pará de Minas;MG;-44.611355;-19.853441
Papagaios;MG;-44.746799;-19.441922
Pequi;MG;-44.660434;-19.628365
Maravilhas;MG;-44.677875;-19.507627
Pompéu;MG;-45.014107;-19.225660
Funilândia;MG;-44.060976;-19.366130
Santana de Pirapama;MG;-44.040913;-18.996242
Varjão de Minas;MG;-46.031318;-18.374102
São Gonçalo do Abaeté;MG;-45.826475;-18.331470
Biquinhas;MG;-45.497372;-18.775410
Morada Nova de Minas;MG;-45.358403;-18.599774
Três Marias;MG;-45.247289;-18.204795
Brasilândia de Minas;MG;-46.008110;-16.999868
Felixlândia;MG;-44.900376;-18.750669
Morro da Garça;MG;-44.601043;-18.535590
Lassance;MG;-44.573491;-17.886980
Curvelo;MG;-44.430259;-18.752731
Inimutaba;MG;-44.358373;-18.727128
Corinto;MG;-44.454181;-18.368967
Presidente Juscelino;MG;-44.060032;-18.640106
Santo Hipólito;MG;-44.222857;-18.296768
Augusto de Lima;MG;-44.265523;-18.099714
Monjolos;MG;-44.117976;-18.324472
Buenópolis;MG;-44.177504;-17.874432
Buritizeiro;MG;-44.960581;-17.365588
Pirapora;MG;-44.934009;-17.339170
Várzea da Palma;MG;-44.722557;-17.594444
Ibiaí;MG;-44.904622;-16.859077
Lagoa dos Patos;MG;-44.575374;-16.977954
Joaquim Felício;MG;-44.164274;-17.758022
Francisco Dumont;MG;-44.231710;-17.310678
Jequitaí;MG;-44.437588;-17.229025
São João da Lagoa;MG;-44.350726;-16.845535
Claro dos Poções;MG;-44.206097;-17.082030
Casa Grande;MG;-43.934306;-20.792521
Carandaí;MG;-43.811002;-20.956604
Queluzito;MG;-43.885084;-20.741562
Cristiano Otoni;MG;-43.816552;-20.832421
São Brás do Suaçuí;MG;-43.951465;-20.624166
Jeceaba;MG;-43.989394;-20.533852
Conselheiro Lafaiete;MG;-43.784609;-20.663445
Congonhas;MG;-43.851012;-20.495844
Itatiaiuçu;MG;-44.421081;-20.198257
Rio Manso;MG;-44.306867;-20.266570
Mateus Leme;MG;-44.431831;-19.979424
Juatuba;MG;-44.345050;-19.944840
Bonfim;MG;-44.236565;-20.330215
Moeda;MG;-44.050947;-20.339868
Igarapé;MG;-44.299449;-20.070671
Brumadinho;MG;-44.200675;-20.150989
Mário Campos;MG;-44.188324;-20.058155
São Joaquim de Bicas;MG;-44.274949;-20.047971
Betim;MG;-44.200775;-19.966827
São Francisco do Glória;MG;-42.267275;-20.792297
Pedra Bonita;MG;-42.330439;-20.521879
Pedra Dourada;MG;-42.151502;-20.826627
Porciúncula;RJ;-42.046477;-20.963210
Tombos;MG;-42.022774;-20.908628
Faria Lemos;MG;-42.021317;-20.809745
Carangola;MG;-42.031348;-20.734348
Divino;MG;-42.143801;-20.613405
Orizânia;MG;-42.199124;-20.514162
Matipó;MG;-42.340145;-20.287264
Santa Margarida;MG;-42.251914;-20.383855
Raul Soares;MG;-42.450197;-20.106110
Pingo-d'Água;MG;-42.407788;-19.727339
Bom Jesus do Galho;MG;-42.316472;-19.836006
Ipaba;MG;-42.413923;-19.415819
Vargem Alegre;MG;-42.294927;-19.598762
Entre Folhas;MG;-42.230555;-19.621811
Bugre;MG;-42.255184;-19.423063
Iapu;MG;-42.214725;-19.438704
Santa Rita de Minas;MG;-42.136277;-19.876046
Caratinga;MG;-42.129217;-19.786805
Piedade de Caratinga;MG;-42.075557;-19.759282
Ubaporanga;MG;-42.105925;-19.635051
Inhapim;MG;-42.114721;-19.547550
Imbé de Minas;MG;-41.969455;-19.601721
São Domingos das Dores;MG;-42.010638;-19.524595
São Sebastião do Anta;MG;-41.985034;-19.506407
Naque;MG;-42.331190;-19.229132
São Gabriel da Palha;ES;-40.536487;-19.018190
Vila Valério;ES;-40.384913;-18.995766
Aracruz;ES;-40.276441;-19.820045
Rio Bananal;ES;-40.336605;-19.271889
Linhares;ES;-40.064277;-19.394642
Sooretama;ES;-40.097351;-19.189695
Jaguaré;ES;-40.075900;-18.907006
Mathias Lobato;MG;-41.916564;-18.590035
Frei Inocêncio;MG;-41.912087;-18.555602
Jampruca;MG;-41.809049;-18.461016
Divino das Laranjeiras;MG;-41.478081;-18.775491
Pescador;MG;-41.600578;-18.356962
São Félix de Minas;MG;-41.488861;-18.595902
Nova Módica;MG;-41.498365;-18.441693
Campanário;MG;-41.735506;-18.242710
Itambacuri;MG;-41.682979;-18.034960
Frei Gaspar;MG;-41.432522;-18.070898
Teófilo Otoni;MG;-41.508717;-17.859534
Central de Minas;MG;-41.314331;-18.761195
Mendes Pimentel;MG;-41.405228;-18.663116
Flores de Goiás;GO;-47.041694;-14.445115
Nova Roma;GO;-46.873369;-13.738781
Alvorada do Norte;GO;-46.491036;-14.479688
Simolândia;GO;-46.484681;-14.464402
Buritinópolis;GO;-46.407588;-14.477184
Damianópolis;GO;-46.178024;-14.560381
Iaciara;GO;-46.633452;-14.101101
Guarani de Goiás;GO;-46.486784;-13.942141
Aurora do Tocantins;TO;-46.407604;-12.710450
Sandolândia;TO;-49.924216;-12.537983
Formoso do Araguaia;TO;-49.531571;-11.797573
Lagoa da Confusão;TO;-49.619948;-10.790637
Caranaíba;MG;-43.741716;-20.870663
Santana dos Montes;MG;-43.694885;-20.786799
Capela Nova;MG;-43.621961;-20.917906
Ouro Branco;MG;-43.696217;-20.526290
Itaverava;MG;-43.614106;-20.676853
Belo Vale;MG;-44.027462;-20.407725
Itabirito;MG;-43.803846;-20.250132
Rio Acima;MG;-43.787786;-20.087553
Nova Lima;MG;-43.850854;-19.975763
Raposos;MG;-43.807941;-19.963580
Rio Espera;MG;-43.472076;-20.855001
Lamim;MG;-43.470615;-20.789999
Cipotânea;MG;-43.362904;-20.902598
Senhora de Oliveira;MG;-43.339432;-20.797221
Catas Altas da Noruega;MG;-43.493876;-20.690073
Piranga;MG;-43.296701;-20.683407
Brás Pires;MG;-43.240556;-20.841912
Presidente Bernardes;MG;-43.189532;-20.765570
Senador Firmino;MG;-43.090431;-20.915762
Diogo de Vasconcelos;MG;-43.195253;-20.487903
Ipatinga;MG;-42.547612;-19.470275
Dores de Guanhães;MG;-42.925373;-19.051607
Joanésia;MG;-42.677481;-19.172897
Mesquita;MG;-42.607877;-19.224022
Santana do Paraíso;MG;-42.544573;-19.366070
Santa Bárbara;MG;-43.410145;-19.960358
Acaiaca;MG;-43.143867;-20.359034
Barra Longa;MG;-43.040168;-20.286866
Rio Piracicaba;MG;-43.182919;-19.928360
Alvinópolis;MG;-43.053487;-20.109771
São José da Lapa;MG;-43.958637;-19.697106
Vespasiano;MG;-43.923900;-19.688326
Sabará;MG;-43.826310;-19.884045
Santa Luzia;MG;-43.849658;-19.754824
Pedro Leopoldo;MG;-44.038258;-19.630811
Confins;MG;-43.993148;-19.628183
Lagoa Santa;MG;-43.893216;-19.639670
Caeté;MG;-43.670353;-19.882610
Taquaraçu de Minas;MG;-43.692226;-19.665186
Nova União;MG;-43.583048;-19.687584
Jaboticatubas;MG;-43.737257;-19.511938
Baldim;MG;-43.961331;-19.283197
Jequitibá;MG;-44.030400;-19.234491
Vianópolis;GO;-48.515914;-16.740532
Abadiânia;GO;-48.705672;-16.196973
Alexânia;GO;-48.507609;-16.083405
Santo Antônio do Descoberto;GO;-48.257761;-15.941247
Águas Lindas de Goiás;GO;-48.281611;-15.761746
Goianésia;GO;-49.116200;-15.311827
Vila Propício;GO;-48.881910;-15.454161
Santa Rita do Novo Destino;GO;-49.120254;-15.135072
Barro Alto;GO;-48.908586;-14.965817
Padre Bernardo;GO;-48.283303;-15.160492
Crixás;GO;-49.973977;-14.541194
Uirapuru;GO;-49.920061;-14.283538
Nova Crixás;GO;-50.329960;-14.095676
Mundo Novo;GO;-50.281378;-13.772943
Bonópolis;GO;-49.810639;-13.632893
Santa Terezinha de Goiás;GO;-49.709099;-14.432649
Campos Verdes;GO;-49.652795;-14.244197
Nova Iguaçu de Goiás;GO;-49.387191;-14.286829
Alto Horizonte;GO;-49.337836;-14.197811
Amaralina;GO;-49.296241;-13.923620
São Miguel do Araguaia;GO;-50.163402;-13.273050
Araguaçu;TO;-49.823134;-12.928934
Novo Planalto;GO;-49.506025;-13.242382
Uruaçu;GO;-49.139633;-14.523840
Campinorte;GO;-49.151093;-14.313670
Mara Rosa;GO;-49.177652;-14.014816
Mutunópolis;GO;-49.274549;-13.730338
Estrela do Norte;GO;-49.071592;-13.866451
Santa Tereza de Goiás;GO;-49.014405;-13.713812
Formoso;GO;-48.877548;-13.649906
Niquelândia;GO;-48.459887;-14.466192
Campinaçu;GO;-48.570384;-13.786997
Porangatu;GO;-49.150282;-13.439102
Jaú do Tocantins;TO;-48.588989;-12.650908
Palmeirópolis;TO;-48.402589;-13.044718
São Salvador do Tocantins;TO;-48.235157;-12.745754
Novo Gama;GO;-48.041662;-16.059185
Valparaíso de Goiás;GO;-47.975675;-16.065078
Luziânia;GO;-47.950000;-16.253000
Cidade Ocidental;GO;-47.925211;-16.076469
Cristalina;GO;-47.613128;-16.767608
Mimoso de Goiás;GO;-48.161149;-15.051489
Água Fria de Goiás;GO;-47.782290;-14.977837
Planaltina;GO;-47.608885;-15.452033
Formosa;GO;-47.336999;-15.539991
São João d'Aliança;GO;-47.522783;-14.704831
Unaí;MG;-46.902157;-16.359212
Cabeceira Grande;MG;-47.086153;-16.033510
Cabeceiras;GO;-46.926499;-15.799546
Natalândia;MG;-46.487365;-16.502150
Dom Bosco;MG;-46.259725;-16.651957
Uruana de Minas;MG;-46.244306;-16.063384
Vila Boa;GO;-47.052029;-15.038705
Buritis;MG;-46.422078;-15.621802
Senhora do Porto;MG;-43.079875;-18.890894
Paula Cândido;MG;-42.975184;-20.875372
Viçosa;MG;-42.874170;-20.755916
São Geraldo;MG;-42.836358;-20.925231
Coimbra;MG;-42.800807;-20.853505
Cajuri;MG;-42.792504;-20.790301
Teixeiras;MG;-42.856395;-20.656083
Amparo do Serra;MG;-42.800868;-20.505068
Ervália;MG;-42.654422;-20.840258
São Miguel do Anta;MG;-42.717389;-20.706665
Pedra do Anta;MG;-42.712291;-20.596784
Canaã;MG;-42.616676;-20.686889
Jequeri;MG;-42.665064;-20.454229
Araponga;MG;-42.517850;-20.668608
Sericita;MG;-42.482776;-20.474840
Ponte Nova;MG;-42.897819;-20.411100
Rio Doce;MG;-42.899497;-20.241171
Oratórios;MG;-42.797698;-20.429758
Urucânia;MG;-42.736989;-20.352088
Santa Cruz do Escalvado;MG;-42.816886;-20.237238
Piedade de Ponte Nova;MG;-42.737872;-20.243848
Dom Silvério;MG;-42.962673;-20.162726
Sem-Peixe;MG;-42.848331;-20.100845
Santo Antônio do Grama;MG;-42.604689;-20.318471
Rio Casca;MG;-42.646209;-20.228542
Abre Campo;MG;-42.474304;-20.299647
São Pedro dos Ferros;MG;-42.525086;-20.173228
Miradouro;MG;-42.345837;-20.889946
Vieiras;MG;-42.240051;-20.867000
Periquito;MG;-42.233341;-19.157331
São João do Oriente;MG;-42.157502;-19.338445
Dom Cavati;MG;-42.112132;-19.373457
Sobrália;MG;-42.099806;-19.234465
Fernandes Tourinho;MG;-42.080259;-19.154087
Tarumirim;MG;-42.009699;-19.283475
Engenheiro Caldas;MG;-42.050305;-19.206497
Alpercata;MG;-41.969998;-18.973994
Congonhas do Norte;MG;-43.676720;-18.802053
Presidente Kubitschek;MG;-43.562774;-18.619252
Gouveia;MG;-43.742294;-18.451944
Datas;MG;-43.659085;-18.447761
Diamantina;MG;-43.603099;-18.241315
Alvorada de Minas;MG;-43.363807;-18.733362
Serro;MG;-43.374399;-18.599103
Santo Antônio do Itambé;MG;-43.300569;-18.460947
Sabinópolis;MG;-43.075243;-18.665326
Serra Azul de Minas;MG;-43.167460;-18.360154
Materlândia;MG;-43.057900;-18.469911
Couto de Magalhães de Minas;MG;-43.464821;-18.072720
São Gonçalo do Rio Preto;MG;-43.385389;-18.002549
Rio Vermelho;MG;-43.001783;-18.292155
Felício dos Santos;MG;-43.242206;-18.075546
Senador Modestino Gonçalves;MG;-43.217170;-17.946520
Olhos-d'Água;MG;-43.570453;-17.395912
Engenheiro Navarro;MG;-43.946966;-17.283128
Bocaiúva;MG;-43.810433;-17.113544
Guaraciama;MG;-43.667525;-17.014173
Glaucilândia;MG;-43.691952;-16.848056
Juramento;MG;-43.586457;-16.847322
Carbonita;MG;-43.013688;-17.525492
Itacambira;MG;-43.306933;-17.062469
Botumirim;MG;-43.008578;-16.865741
Guanhães;MG;-42.931231;-18.771284
Paulistas;MG;-42.862800;-18.427631
São João Evangelista;MG;-42.765504;-18.548001
Virginópolis;MG;-42.701492;-18.815409
Divinolândia de Minas;MG;-42.610345;-18.800380
Gonzaga;MG;-42.476908;-18.819608
Cantagalo;MG;-42.622259;-18.524772
Peçanha;MG;-42.558276;-18.544079
São Pedro do Suaçuí;MG;-42.598137;-18.360926
Coluna;MG;-42.835185;-18.231080
Frei Lagonegro;MG;-42.761711;-18.175100
Itamarandiba;MG;-42.856120;-17.855157
São José do Jacuri;MG;-42.672930;-18.281037
José Raydan;MG;-42.494643;-18.219505
São Sebastião do Maranhão;MG;-42.565853;-18.087252
Aricanduva;MG;-42.553263;-17.866642
Santa Efigênia de Minas;MG;-42.438832;-18.823464
Sardoá;MG;-42.362938;-18.782799
São Geraldo da Piedade;MG;-42.286665;-18.841119
Coroaci;MG;-42.279099;-18.615570
Virgolândia;MG;-42.306707;-18.473842
Nacip Raydan;MG;-42.248121;-18.454436
Governador Valadares;MG;-41.955530;-18.854452
Guaçuí;ES;-41.673400;-20.766792
Alegre;ES;-41.538237;-20.758041
Ibitirama;ES;-41.666718;-20.546619
Alto Caparaó;MG;-41.873772;-20.430980
Martins Soares;MG;-41.878592;-20.254562
Durandé;MG;-41.797720;-20.205817
Santana do Manhuaçu;MG;-41.927839;-20.103106
São José do Mantimento;MG;-41.748591;-20.005756
Chalé;MG;-41.689714;-20.045328
Conceição de Ipanema;MG;-41.690756;-19.932584
Irupi;ES;-41.644359;-20.350122
Iúna;ES;-41.533440;-20.353135
Ibatiba;ES;-41.508674;-20.234676
Veredinha;MG;-42.730697;-17.397384
Capelinha;MG;-42.514705;-17.688792
Turmalina;MG;-42.728476;-17.282825
Leme do Prado;MG;-42.693570;-17.079273
Minas Novas;MG;-42.588435;-17.215603
Chapada do Norte;MG;-42.539187;-17.088119
José Gonçalves de Minas;MG;-42.601384;-16.905329
Angelândia;MG;-42.264123;-17.727938
Setubinha;MG;-42.158669;-17.600202
Jenipapo de Minas;MG;-42.258934;-17.083129
Berilo;MG;-42.460620;-16.956690
Francisco Badaró;MG;-42.356813;-16.988284
Virgem da Lapa;MG;-42.343071;-16.807011
Araçuaí;MG;-42.063700;-16.852320
Armação dos Búzios;RJ;-41.884559;-22.752846
Macaé;RJ;-41.784828;-22.376807
Conceição de Macabu;RJ;-41.871878;-22.083370
Carapebus;RJ;-41.663010;-22.182112
Quissamã;RJ;-41.469253;-22.103097
Cambuci;RJ;-41.918723;-21.569058
São Fidélis;RJ;-41.756041;-21.655119
Italva;RJ;-41.701442;-21.429607
Itaperuna;RJ;-41.879907;-21.199665
Bom Jesus do Itabapoana;RJ;-41.682222;-21.144851
Cardoso Moreira;RJ;-41.616496;-21.484595
Bom Jesus do Norte;ES;-41.673129;-21.117290
Apiacá;ES;-41.569297;-21.152272
São José do Calçado;ES;-41.663627;-21.027442
Campos dos Goytacazes;RJ;-41.318055;-21.762171
São João da Barra;RJ;-41.044582;-21.638009
Mimoso do Sul;ES;-41.361529;-21.062777
Presidente Kennedy;ES;-41.046800;-21.096358
Marataízes;ES;-40.838436;-21.039813
Itapemirim;ES;-40.830669;-21.009512
Varre-Sai;RJ;-41.870090;-20.927561
Caiana;MG;-41.929208;-20.695579
Espera Feliz;MG;-41.911927;-20.650762
Dores do Rio Preto;ES;-41.840538;-20.693119
Caparaó;MG;-41.906063;-20.528897
Divino de São Lourenço;ES;-41.693725;-20.622932
Lajinha;MG;-41.622818;-20.153941
Muqui;ES;-41.345994;-20.950859
Jerônimo Monteiro;ES;-41.394848;-20.799359
Atilio Vivacqua;ES;-41.198576;-20.912974
Muniz Freire;ES;-41.415630;-20.465187
Adelândia;GO;-50.165699;-16.412700
Nazário;GO;-49.881671;-16.580769
Anicuns;GO;-49.961695;-16.464164
Sanclerlândia;GO;-50.312374;-16.197039
Mossâmedes;GO;-50.213615;-16.123982
Goiás;GO;-50.139963;-15.933305
Americano do Brasil;GO;-49.983129;-16.251449
Campestre de Goiás;GO;-49.694970;-16.762418
Santa Bárbara de Goiás;GO;-49.695385;-16.571363
Avelinópolis;GO;-49.757866;-16.467241
Araçu;GO;-49.680424;-16.356306
Trindade;GO;-49.492667;-16.651708
Abadia de Goiás;GO;-49.441156;-16.757326
Caturaí;GO;-49.493573;-16.444725
Goianira;GO;-49.426998;-16.494746
Inhumas;GO;-49.500111;-16.361057
Brazabrantes;GO;-49.386252;-16.428131
Santo Antônio de Goiás;GO;-49.309607;-16.481534
Damolândia;GO;-49.363098;-16.254372
Guarinos;GO;-49.700609;-14.729215
Pilar de Goiás;GO;-49.578356;-14.760837
Hidrolina;GO;-49.463404;-14.726103
Santa Maria do Tocantins;TO;-47.788708;-8.804598
Itacajá;TO;-47.772615;-8.392935
Centenário;TO;-47.330382;-8.961031
Recursolândia;TO;-47.242107;-8.722700
São Félix do Tocantins;TO;-46.661786;-10.161465
Lizarda;TO;-46.673813;-9.590022
Bonfinópolis de Minas;MG;-45.983859;-16.568034
Riachinho;MG;-45.988755;-16.225802
Arinos;MG;-46.104324;-15.918743
Urucuia;MG;-45.735199;-16.124412
Santa Fé de Minas;MG;-45.410207;-16.685947
São Domingos do Araguaia;PA;-48.736649;-5.537324
Esperantina;TO;-48.537771;-5.365928
Brejo Grande do Araguaia;PA;-48.410250;-5.698216
Palestina do Pará;PA;-48.318076;-5.740269
Jacundá;PA;-49.115272;-4.446173
Bom Jesus do Tocantins;PA;-48.604701;-5.042402
São Pedro da Água Branca;MA;-48.429117;-5.084718
Abel Figueiredo;PA;-48.393285;-4.953327
Itapiratins;TO;-48.107192;-8.379815
Palmeirante;TO;-47.924191;-7.847861
Barra do Ouro;TO;-47.677609;-7.695929
Francinópolis;PI;-42.259062;-6.393341
Barra D'Alcântara;PI;-42.114609;-6.516453
Buriti Bravo;MA;-43.835288;-5.832392
Passagem Franca;MA;-43.775511;-6.177451
Lagoa do Mato;MA;-43.533313;-6.050229
Palmeirais;PI;-43.056021;-5.970864
Matões;MA;-43.201758;-5.513591
Parnarama;MA;-43.101096;-5.673646
São João do Soter;MA;-43.816257;-5.108210
Timbiras;MA;-43.931956;-4.255971
Codó;MA;-43.892354;-4.455623
Canapi;AL;-37.596700;-9.119319
Poço das Trincheiras;AL;-37.288932;-9.307417
Santana do Ipanema;AL;-37.248018;-9.369989
Maravilha;AL;-37.352419;-9.230449
Ouro Branco;AL;-37.355639;-9.158842
Itaíba;PE;-37.417266;-8.945688
Ibimirim;PE;-37.703214;-8.540260
Tupanatinga;PE;-37.344532;-8.747984
Dois Riachos;AL;-37.096502;-9.384645
Cacimbinhas;AL;-36.991082;-9.401212
Águas Belas;PE;-37.122619;-9.111255
Castelo;ES;-41.203133;-20.603255
Cachoeiro de Itapemirim;ES;-41.119829;-20.846212
Rio Novo do Sul;ES;-40.938761;-20.855619
Vargem Alta;ES;-41.017922;-20.669019
Conceição do Castelo;ES;-41.241730;-20.363897
Brejetuba;ES;-41.295410;-20.139489
Venda Nova do Imigrante;ES;-41.135545;-20.327017
Afonso Cláudio;ES;-41.126060;-20.077841
Ipanema;MG;-41.716449;-19.799152
Alvarenga;MG;-41.731740;-19.417403
Taparuba;MG;-41.607981;-19.762079
Mutum;MG;-41.440689;-19.812099
Pocrane;MG;-41.633425;-19.620795
Itanhomi;MG;-41.863017;-19.173587
Capitão Andrade;MG;-41.861414;-19.074835
Conselheiro Pena;MG;-41.473554;-19.178914
Tumiritinga;MG;-41.652674;-18.984446
Galiléia;MG;-41.538653;-19.000464
Itueta;MG;-41.174619;-19.399935
Laranja da Terra;ES;-41.062141;-19.899399
Aimorés;MG;-41.074651;-19.500684
Baixo Guandu;ES;-41.010913;-19.521283
Santa Rita do Itueto;MG;-41.382084;-19.357649
Resplendor;MG;-41.246214;-19.319437
São Geraldo do Baixio;MG;-41.362964;-18.909668
Goiabeira;MG;-41.223526;-18.980659
Cuparaque;MG;-41.098621;-18.964827
Alto Rio Novo;ES;-41.020909;-19.061797
Iconha;ES;-40.813200;-20.791287
Piúma;ES;-40.726813;-20.833433
Anchieta;ES;-40.642545;-20.795499
Alfredo Chaves;ES;-40.754289;-20.639627
Guarapari;ES;-40.509253;-20.677248
Marechal Floriano;ES;-40.669998;-20.415889
Domingos Martins;ES;-40.659425;-20.360306
Santa Maria de Jetibá;ES;-40.743931;-20.025296
Viana;ES;-40.493292;-20.382503
Cariacica;ES;-40.416549;-20.263202
Santa Leopoldina;ES;-40.526998;-20.099914
Santa Teresa;ES;-40.597945;-19.936339
Fundão;ES;-40.407759;-19.937035
Vila Velha;ES;-40.287458;-20.341705
Serra;ES;-40.307408;-20.121032
Itarana;ES;-40.875264;-19.875009
Itaguaçu;ES;-40.860135;-19.801786
São Roque do Canaã;ES;-40.652580;-19.741145
Ibiraçu;ES;-40.373182;-19.836601
João Neiva;ES;-40.385951;-19.757707
Colatina;ES;-40.626898;-19.549316
Marilândia;ES;-40.545648;-19.411358
Pancas;ES;-40.853444;-19.222940
Águia Branca;ES;-40.743690;-18.984588
São Domingos do Norte;ES;-40.628120;-19.145213
São João do Manteninha;MG;-41.162837;-18.722988
São José do Divino;MG;-41.390671;-18.479304
Itabirinha;MG;-41.232597;-18.569104
Mantenópolis;ES;-41.124005;-18.859428
Mantena;MG;-40.987368;-18.776071
Barra de São Francisco;ES;-40.896456;-18.754840
Nova Belém;MG;-41.110670;-18.492517
Água Doce do Norte;ES;-40.985411;-18.548220
Ouro Verde de Minas;MG;-41.273412;-18.071923
Ataléia;MG;-41.114901;-18.043805
Poté;MG;-41.786044;-17.807698
Ladainha;MG;-41.748824;-17.627886
Novo Cruzeiro;MG;-41.882633;-17.465438
Itaipé;MG;-41.669654;-17.401419
Catuji;MG;-41.527640;-17.301807
Caraí;MG;-41.700359;-17.186161
Padre Paraíso;MG;-41.482053;-17.075789
Novo Oriente de Minas;MG;-41.219361;-17.408932
Pavão;MG;-41.003471;-17.426703
Monte Formoso;MG;-41.247306;-16.869145
Crisólita;MG;-40.918392;-17.238124
Águas Formosas;MG;-40.938355;-17.080210
Fronteira dos Vales;MG;-40.922992;-16.889807
Ecoporanga;ES;-40.835976;-18.370231
Nova Venécia;ES;-40.405273;-18.715017
Vila Pavão;ES;-40.608992;-18.609087
Ponto Belo;ES;-40.545801;-18.125318
Mucurici;ES;-40.519975;-18.096516
Boa Esperança;ES;-40.302521;-18.539495
Pinheiros;ES;-40.217142;-18.414068
São Mateus;ES;-39.857935;-18.721407
Montanha;ES;-40.366781;-18.130287
Nanuque;MG;-40.353287;-17.848111
Jaraguá;GO;-49.334428;-15.752948
Guaraíta;GO;-50.026528;-15.612137
Itapuranga;GO;-49.948957;-15.560589
Heitoraí;GO;-49.826826;-15.718986
Morro Agudo de Goiás;GO;-50.055281;-15.318430
São Patrício;GO;-49.818000;-15.350000
Nova América;GO;-49.895297;-15.020589
Talismã;TO;-49.089582;-12.794879
Itaipava do Grajaú;MA;-45.787736;-5.142517
Lagoa Grande do Maranhão;MA;-45.381609;-4.988929
Marajá do Sena;MA;-45.453116;-4.628062
Lago da Pedra;MA;-45.131912;-4.569743
Paulo Ramos;MA;-45.239760;-4.444848
Fernando Falcão;MA;-44.897876;-6.162069
Tuntum;MA;-44.644367;-5.254761
Colinas;MA;-44.254345;-6.031986
Jatobá;MA;-44.215325;-5.822821
São Domingos do Maranhão;MA;-44.382195;-5.580953
Santa Filomena do Maranhão;MA;-44.563763;-5.496706
Presidente Dutra;MA;-44.494980;-5.289801
Graça Aranha;MA;-44.335816;-5.405468
Fortuna;MA;-44.156496;-5.727921
Governador Eugênio Barros;MA;-44.246901;-5.318973
Governador Luiz Rocha;MA;-44.077433;-5.478353
Senador Alexandre Costa;MA;-44.053347;-5.250956
São Raimundo do Doca Bezerra;MA;-45.069554;-5.110532
São Roberto;MA;-45.000992;-5.023096
Bom Lugar;MA;-45.032650;-4.373113
Lago do Junco;MA;-45.049001;-4.609000
Bernardo do Mearim;MA;-44.760753;-4.626663
Trizidela do Vale;MA;-44.628000;-4.538000
Pedreiras;MA;-44.600637;-4.564817
Bacabal;MA;-44.783183;-4.224467
São Luís Gonzaga do Maranhão;MA;-44.665404;-4.385409
Dom Pedro;MA;-44.440883;-5.035182
Santo Antônio dos Lopes;MA;-44.365278;-4.866134
Capinzal do Norte;MA;-44.328045;-4.723603
Gonçalves Dias;MA;-44.301351;-5.147501
São João do Arraial;PI;-42.445860;-3.818597
Esperantina;PI;-42.232422;-3.888631
Morro do Chapéu do Piauí;PI;-42.302442;-3.733374
Batalha;PI;-42.078680;-4.022300
Joca Marques;PI;-42.425517;-3.480399
Luzilândia;PI;-42.371757;-3.468296
Itanhém;BA;-40.332139;-17.164207
Jucuruçu;BA;-40.164074;-16.848752
Vereda;BA;-40.097368;-17.218256
Conceição da Barra;ES;-39.736199;-18.588306
Mucuri;BA;-39.556467;-18.075392
Nova Viçosa;BA;-39.374331;-17.892648
Teixeira de Freitas;BA;-39.739962;-17.539915
Itamaraju;BA;-39.538569;-17.037820
Caravelas;BA;-39.259679;-17.726839
Alcobaça;BA;-39.203609;-17.519468
Prado;BA;-39.222721;-17.336357
Firminópolis;GO;-50.304007;-16.577780
Turvânia;GO;-50.136894;-16.612535
São Luíz do Norte;GO;-49.328495;-14.860804
Senador Canedo;GO;-49.091398;-16.708401
Nerópolis;GO;-49.222708;-16.404708
Goianápolis;GO;-49.023435;-16.509768
Terezópolis de Goiás;GO;-49.079695;-16.394529
Caldazinha;GO;-49.001260;-16.711665
Bonfinópolis;GO;-48.961570;-16.617271
Anápolis;GO;-48.952958;-16.328095
Ouro Verde de Goiás;GO;-49.194154;-16.218107
São Francisco de Goiás;GO;-49.260519;-15.925646
Pirenópolis;GO;-48.958430;-15.850700
Corumbá de Goiás;GO;-48.811706;-15.924473
Cocalzinho de Goiás;GO;-48.774742;-15.791438
Leopoldo de Bulhões;GO;-48.742755;-16.619048
Silvânia;GO;-48.608326;-16.659972
Carutapera;MA;-46.008547;-1.196957
Santo Antônio do Jacinto;MG;-40.181741;-16.533206
Jacinto;MG;-40.294991;-16.142756
Santa Maria do Salto;MG;-40.151164;-16.247895
Itapitanga;BA;-39.565654;-14.413932
Gongogi;BA;-39.468990;-14.319481
Ubatã;BA;-39.520696;-14.206336
Aurelino Leal;BA;-39.329035;-14.320954
Ubaitaba;BA;-39.322166;-14.302971
Ibirapitanga;BA;-39.378737;-14.164898
Ipiaú;BA;-39.735252;-14.122630
Ibirataia;BA;-39.645935;-14.064316
Apuarema;BA;-39.750148;-13.854241
Itamari;BA;-39.682959;-13.778192
Nova Ibiá;BA;-39.618174;-13.811995
Gandu;BA;-39.474651;-13.744101
Wenceslau Guimarães;BA;-39.476178;-13.690825
Piraí do Norte;BA;-39.383592;-13.758962
Nossa Senhora das Dores;SE;-37.196285;-10.485360
Cumbe;SE;-37.184622;-10.351979
Gracho Cardoso;SE;-37.200559;-10.225233
Aquidabã;SE;-37.014768;-10.278043
Itabi;SE;-37.105558;-10.124808
Canhoba;SE;-36.980639;-10.136465
Nossa Senhora de Lourdes;SE;-37.061528;-10.077168
Gararu;SE;-37.086921;-9.972202
Traipu;AL;-37.007119;-9.962625
Muribeca;SE;-36.958820;-10.427146
Malhada dos Bois;SE;-36.925188;-10.341837
São Francisco;SE;-36.886907;-10.344151
Cedro de São João;SE;-36.885641;-10.253441
Japoatã;SE;-36.804504;-10.347719
Amparo de São Francisco;SE;-36.934993;-10.134842
Telha;SE;-36.881778;-10.206424
Propriá;SE;-36.844153;-10.213773
Porto Real do Colégio;AL;-36.837551;-10.184890
Trairi;CE;-39.268126;-3.269323
São Gonçalo do Amarante;CE;-38.972646;-3.605149
Paracuru;CE;-39.030028;-3.414360
Guaiúba;CE;-38.640372;-4.040571
Pacatuba;CE;-38.618296;-3.978396
Maranguape;CE;-38.682911;-3.891432
Maracanaú;CE;-38.625901;-3.866990
Caucaia;CE;-38.661931;-3.727966
Horizonte;CE;-38.470674;-4.120896
Pacajus;CE;-38.464988;-4.171073
Itaitinga;CE;-38.529763;-3.965768
Pindoretama;CE;-38.306118;-4.015839
Eusébio;CE;-38.455875;-3.892501
Aquiraz;CE;-38.389563;-3.899293
Cascavel;CE;-38.241178;-4.129666
Beberibe;CE;-38.127075;-4.177415
Custódia;PE;-37.644280;-8.085460
Sertânia;PE;-37.268393;-8.068467
Mamanguape;PB;-35.121316;-6.833698
Lucena;PB;-34.874769;-6.902578
Cabedelo;PB;-34.828409;-6.987314
Rio Tinto;PB;-35.077569;-6.803828
Marcação;PB;-35.008661;-6.765350
Mataraca;PB;-35.053080;-6.596730
Baía da Traição;PB;-34.938060;-6.692086
Vila Flor;RN;-35.067023;-6.312868
Baía Formosa;RN;-35.003270;-6.371607
Januário Cicco;RN;-35.596566;-6.154793
Serrinha;RN;-35.500038;-6.281811
Lagoa Salgada;RN;-35.472394;-6.122954
Lagoa de Pedras;RN;-35.429888;-6.150815
Vera Cruz;RN;-35.427979;-6.043989
São Pedro;RN;-35.631687;-5.905588
Bom Jesus;RN;-35.579196;-5.986479
Ielmo Marinho;RN;-35.549952;-5.824469
Passagem;RN;-35.370037;-6.272684
Brejinho;RN;-35.359113;-6.185662
Monte Alegre;RN;-35.325337;-6.070630
Goianinha;RN;-35.194289;-6.264864
Arês;RN;-35.160823;-6.188310
São José de Mipibu;RN;-35.241692;-6.077303
Nísia Floresta;RN;-35.199130;-6.093288
Senador Georgino Avelino;RN;-35.129912;-6.157598
Parnamirim;RN;-35.271000;-5.911156
Macaíba;RN;-35.355184;-5.852295
São Gonçalo do Amarante;RN;-35.325729;-5.790682
Taipu;RN;-35.591800;-5.630576
Ceará-Mirim;RN;-35.419741;-5.640061
Pureza;RN;-35.555374;-5.463933
Extremoz;RN;-35.304840;-5.701433
Maxaranguape;RN;-35.263082;-5.521809
Rio do Fogo;RN;-35.379437;-5.276498
Tibau do Sul;RN;-35.086620;-6.191760
São Miguel do Gostoso;RN;-35.641429;-5.125882
Formoso;MG;-46.237075;-14.944633
Sítio d'Abadia;GO;-46.250628;-14.799240
Colinas do Sul;GO;-48.075988;-14.152816
Alto Paraíso de Goiás;GO;-47.510016;-14.130478
Cavalcante;GO;-47.456590;-13.797564
Teresina de Goiás;GO;-47.265896;-13.780056
Minaçu;GO;-48.220580;-13.530413
Paranã;TO;-47.873378;-12.616747
Alvorada;TO;-49.124926;-12.478465
Figueirópolis;TO;-49.174771;-12.131175
Cariri do Tocantins;TO;-49.160876;-11.888081
Gurupi;TO;-49.068046;-11.727940
Sucupira;TO;-48.968510;-11.993034
Peixe;TO;-48.539508;-12.025425
São Valério da Natividade;TO;-48.235315;-11.974315
Dueré;TO;-49.271566;-11.341631
Aliança do Tocantins;TO;-48.936097;-11.305565
Crixás do Tocantins;TO;-48.915194;-11.099375
Currais;PI;-44.406194;-9.011755
Santa Luz;PI;-44.129567;-8.948795
Cristino Castro;PI;-44.222972;-8.822731
Palmeira do Piauí;PI;-44.246618;-8.730761
Brejolândia;BA;-43.967855;-12.481547
Wanderley;BA;-43.895787;-12.114375
Muquém de São Francisco;BA;-43.543808;-12.063376
Ibotirama;BA;-43.216690;-12.177876
Morpará;BA;-43.276566;-11.556892
Buritirama;BA;-43.630231;-10.717055
Barra;BA;-43.145913;-11.085900
Oliveira dos Brejinhos;BA;-42.896872;-12.313183
Brotas de Macaúbas;BA;-42.632609;-11.991452
Ipupiara;BA;-42.617913;-11.821881
Ibipeba;BA;-42.019515;-11.643775
Ibititá;BA;-41.974810;-11.541426
Gentio do Ouro;BA;-42.507652;-11.434233
Xique-Xique;BA;-42.724545;-10.822960
Itaguaçu da Bahia;BA;-42.399671;-11.014672
Uibaí;BA;-42.135381;-11.339420
Presidente Dutra;BA;-41.984341;-11.292345
Central;BA;-42.111625;-11.137576
Itinga;MG;-41.767194;-16.609972
Comercinho;MG;-41.794536;-16.296258
Ponto dos Volantes;MG;-41.502477;-16.747314
Itaobim;MG;-41.501692;-16.557070
Santa Cruz de Salinas;MG;-41.741767;-16.096717
Cristalândia;TO;-49.194202;-10.598480
Santa Rita do Tocantins;TO;-48.916113;-10.861724
Fátima;TO;-48.907583;-10.760259
Oliveira de Fátima;TO;-48.908610;-10.706956
Nova Rosalândia;TO;-48.912477;-10.565102
Ipueiras;TO;-48.460024;-11.232900
Brejinho de Nazaré;TO;-48.568278;-11.005754
Porto Nacional;TO;-48.408008;-10.702705
Marianópolis do Tocantins;TO;-49.655260;-9.793768
Caseara;TO;-49.952123;-9.276121
Santa Maria das Barreiras;PA;-49.721472;-8.857838
Araguacema;TO;-49.556886;-8.807547
Pium;TO;-49.187619;-10.442025
Chapada de Areia;TO;-49.140277;-10.141933
Pugmil;TO;-48.895738;-10.424047
Monte Santo do Tocantins;TO;-48.994080;-10.007470
Paraíso do Tocantins;TO;-48.882269;-10.174998
Divinópolis do Tocantins;TO;-49.216849;-9.800181
Abreulândia;TO;-49.151845;-9.621015
Barrolândia;TO;-48.725203;-9.834042
Miranorte;TO;-48.592222;-9.529068
Lajeado;TO;-48.356482;-9.749956
Miracema do Tocantins;TO;-48.392980;-9.565556
Tocantínia;TO;-48.374104;-9.563198
Dois Irmãos do Tocantins;TO;-49.063756;-9.255336
Goianorte;TO;-48.931291;-8.774126
Colméia;TO;-48.763753;-8.724627
Pequizeiro;TO;-48.932690;-8.593204
Rio dos Bois;TO;-48.524518;-9.344254
Fortaleza do Tabocão;TO;-48.520563;-9.056106
Guaraí;TO;-48.511432;-8.835429
Itaporã do Tocantins;TO;-48.689481;-8.571722
Presidente Kennedy;TO;-48.506232;-8.540599
Brasilândia do Tocantins;TO;-48.482213;-8.389182
Natividade;TO;-47.722344;-11.703428
Chapada da Natividade;TO;-47.748614;-11.617507
Conceição do Tocantins;TO;-47.295103;-12.220874
Santa Rosa do Tocantins;TO;-48.121627;-11.447392
Silvanópolis;TO;-48.169370;-11.147099
Monte do Carmo;TO;-48.111426;-10.761054
Pindorama do Tocantins;TO;-47.572630;-11.131074
Ponte Alta do Tocantins;TO;-47.527577;-10.748118
Taipas do Tocantins;TO;-46.979681;-12.187341
Almas;TO;-47.179197;-11.570553
Porto Alegre do Tocantins;TO;-47.062126;-11.617970
Dianópolis;TO;-46.819787;-11.624008
Taguatinga;TO;-46.436967;-12.402630
Ponte Alta do Bom Jesus;TO;-46.482541;-12.085297
Novo Jardim;TO;-46.632471;-11.825965
Rio da Conceição;TO;-46.884664;-11.394863
Mateiros;TO;-46.416758;-10.546379
Santa Tereza do Tocantins;TO;-47.803285;-10.274621
Aparecida do Rio Negro;TO;-47.963753;-9.941386
Lagoa do Tocantins;TO;-47.537979;-10.367977
Novo Acordo;TO;-47.678533;-9.970633
Tupirama;TO;-48.188324;-8.971676
Pedro Afonso;TO;-48.172942;-8.970341
Bom Jesus do Tocantins;TO;-48.165043;-8.963060
Rio Sono;TO;-47.887968;-9.350020
Tupiratins;TO;-48.127744;-8.393880
Pintópolis;MG;-45.140170;-16.057205
Chapada Gaúcha;MG;-45.611616;-15.301373
Ponto Chique;MG;-45.058770;-16.628233
São Romão;MG;-45.074890;-16.364118
Campo Azul;MG;-44.809624;-16.502805
Ubaí;MG;-44.778251;-16.288505
Icaraí de Minas;MG;-44.903359;-16.213968
São Francisco;MG;-44.859284;-15.951449
Luislândia;MG;-44.588579;-16.109483
São João do Pacuí;MG;-44.513355;-16.537314
Coração de Jesus;MG;-44.363517;-16.684067
Mirabela;MG;-44.160208;-16.256022
Brasília de Minas;MG;-44.429897;-16.210382
Matias Cardoso;MG;-43.914565;-14.856304
Manga;MG;-43.939053;-14.752892
Pai Pedro;MG;-43.070006;-15.527099
Gameleiras;MG;-43.124982;-15.082855
Grão Mogol;MG;-42.892306;-16.566232
Cristália;MG;-42.857120;-16.716014
Josenópolis;MG;-42.515056;-16.541697
Padre Carvalho;MG;-42.508759;-16.364644
Serranópolis de Minas;MG;-42.873156;-15.817567
Fruta de Leite;MG;-42.528772;-16.122458
Bonito de Minas;MG;-44.754267;-15.323129
Pedras de Maria da Cruz;MG;-44.391046;-15.603244
Januária;MG;-44.363889;-15.480154
Cônego Marinho;MG;-44.418059;-15.289172
Ibiracatu;MG;-44.166707;-15.660525
Miravânia;MG;-44.409243;-14.734772
Itacarambi;MG;-44.095005;-15.089028
São João das Missões;MG;-44.092201;-14.885852
Mambaí;GO;-46.116491;-14.482291
Montalvânia;MG;-44.371881;-14.419689
Cocos;BA;-44.535220;-14.181360
Feira da Mata;BA;-44.274424;-14.204405
Juvenília;MG;-44.159656;-14.266238
Coribe;BA;-44.458593;-13.823216
Correntina;BA;-44.633335;-13.347674
Jaborandi;BA;-44.425537;-13.607082
Santa Maria da Vitória;BA;-44.201057;-13.385867
São Félix do Coribe;BA;-44.183708;-13.401883
Canápolis;BA;-44.200949;-13.072454
Santana;BA;-44.050640;-12.979217
Montes Claros;MG;-43.857809;-16.728177
Capitão Enéas;MG;-43.708443;-16.326540
São João da Ponte;MG;-44.009612;-15.927088
Francisco Sá;MG;-43.489615;-16.482729
Janaúba;MG;-43.313168;-15.802228
Nova Porteirinha;MG;-43.294148;-15.799291
Riacho dos Machados;MG;-43.048796;-16.009131
Porteirinha;MG;-43.028090;-15.740420
Rubelita;MG;-42.261042;-16.405270
Coronel Murta;MG;-42.183952;-16.614847
Novorizonte;MG;-42.404433;-16.016214
Salinas;MG;-42.296406;-16.175265
Tanque Novo;BA;-42.493412;-13.548509
Botuporã;BA;-42.516301;-13.377235
Cametá;PA;-49.497885;-2.242949
Goianésia do Pará;PA;-49.097385;-3.843384
Aurora do Pará;PA;-47.567712;-2.148976
São João do Carú;MA;-46.250694;-3.550305
Nova Esperança do Piriá;PA;-46.973150;-2.266925
Joselândia;MA;-44.695834;-4.986111
São José dos Basílios;MA;-44.580937;-5.054931
Esperantinópolis;MA;-44.692649;-4.879378
Lago dos Rodrigues;MA;-44.979763;-4.611735
Poção de Pedras;MA;-44.943199;-4.746260
Igarapé Grande;MA;-44.855833;-4.662500
Taiobeiras;MG;-42.225944;-15.810619
Catuti;MG;-42.962704;-15.361574
Mato Verde;MG;-42.859984;-15.394396
Rio Pardo de Minas;MG;-42.540500;-15.616028
Santo Antônio do Retiro;MG;-42.617092;-15.339271
Monte Azul;MG;-42.871768;-15.151362
Mamonas;MG;-42.946858;-15.047941
Espinosa;MG;-42.809015;-14.924948
Montezuma;MG;-42.494074;-15.170233
Urandi;BA;-42.649766;-14.767799
Licínio de Almeida;BA;-42.509527;-14.684215
Vargem Grande do Rio Pardo;MG;-42.308507;-15.398737
Indaiabira;MG;-42.200514;-15.491056
São João do Paraíso;MG;-42.021342;-15.316799
Mortugaba;BA;-42.372663;-15.022454
Jacaraci;BA;-42.432878;-14.854090
Condeúba;BA;-41.971829;-14.902227
Malhada;BA;-43.768634;-14.337134
Carinhanha;BA;-43.772349;-14.298524
Iuiú;BA;-43.559547;-14.405424
Palmas de Monte Alto;BA;-43.160860;-14.267562
Serra do Ramalho;BA;-43.592907;-13.565889
Serra Dourada;BA;-43.950434;-12.759004
Tabocas do Brejo Velho;BA;-44.007449;-12.702634
Bom Jesus da Lapa;BA;-43.410754;-13.250571
Sítio do Mato;BA;-43.468921;-13.080149
Paratinga;BA;-43.179794;-12.686974
Rio do Antônio;BA;-42.072103;-14.407144
Lagoa Real;BA;-42.132764;-14.033443
Riacho de Santana;BA;-42.939675;-13.605941
Boquira;BA;-42.732354;-12.820536
Macaúbas;BA;-42.694515;-13.018601
Ibipitanga;BA;-42.485568;-12.880435
Paramirim;BA;-42.239502;-13.438849
Caturama;BA;-42.290385;-13.323938
Rio do Pires;BA;-42.290166;-13.118530
Érico Cardoso;BA;-42.135200;-13.421467
Ibitiara;BA;-42.217946;-12.650181
Novo Horizonte;BA;-42.168172;-12.808318
Formosa do Rio Preto;BA;-45.192980;-11.032812
Cristalândia do Piauí;PI;-45.189262;-10.644311
São Desidério;BA;-44.976881;-12.357196
Barreiras;BA;-44.996838;-12.143864
Catolândia;BA;-44.864808;-12.310013
Riachão das Neves;BA;-44.914259;-11.750755
Angical;BA;-44.700272;-12.006313
Baianópolis;BA;-44.538829;-12.301641
Cristópolis;BA;-44.421358;-12.224899
Cotegipe;BA;-44.256559;-12.022761
Sebastião Barros;PI;-44.833718;-10.816978
Santa Rita de Cássia;BA;-44.525496;-11.006253
Mansidão;BA;-44.042798;-10.722707
Corrente;PI;-45.163348;-10.433349
São Gonçalo do Gurguéia;PI;-45.309219;-10.031950
Barreiras do Piauí;PI;-45.470172;-9.929605
Gilbués;PI;-45.342267;-9.830008
Monte Alegre do Piauí;PI;-45.303735;-9.753638
Santa Filomena;PI;-45.911607;-9.112278
Alto Parnaíba;MA;-45.930328;-9.102733
Tasso Fragoso;MA;-45.753647;-8.466203
Riacho Frio;PI;-44.950326;-10.124378
Parnaguá;PI;-44.630018;-10.216642
Redenção do Gurguéia;PI;-44.581145;-9.479368
Júlio Borges;PI;-44.238142;-10.322530
Curimatá;PI;-44.300200;-10.032608
Bom Jesus;PI;-44.358951;-9.071239
Jussara;BA;-41.970235;-11.043084
Avelino Lopes;PI;-43.956305;-10.134487
Morro Cabeça no Tempo;PI;-43.907246;-9.718905
Campo Alegre de Lourdes;BA;-43.012580;-9.522212
Guaribas;PI;-43.694341;-9.386465
Alvorada do Gurguéia;PI;-43.777016;-8.424183
Caracol;PI;-43.328998;-9.279333
Jurema;PI;-43.133676;-9.219924
Anísio de Abreu;PI;-43.049410;-9.185643
São Braz do Piauí;PI;-43.007646;-9.057967
Pilão Arcado;BA;-42.493607;-10.005079
Fartura do Piauí;PI;-42.791176;-9.483420
Remanso;BA;-42.084839;-9.619440
Várzea Branca;PI;-42.969221;-9.238003
Bonfim do Piauí;PI;-42.886482;-9.160502
São Raimundo Nonato;PI;-42.698659;-9.012412
São Lourenço do Piauí;PI;-42.549552;-9.164633
Tamboril do Piauí;PI;-42.921107;-8.409369
Coronel José Dias;PI;-42.523195;-8.813971
Dirceu Arcoverde;PI;-42.434792;-9.339386
Dom Inocêncio;PI;-41.969733;-9.005158
João Costa;PI;-42.426379;-8.507360
Redenção;PA;-50.031687;-8.025294
Pau D'Arco;PA;-50.044000;-7.833916
Floresta do Araguaia;PA;-49.712495;-7.553346
Pau D'Arco;TO;-49.366971;-7.539191
Rio Maria;PA;-50.037875;-7.312358
Xinguara;PA;-49.943672;-7.098301
Sapucaia;PA;-49.683408;-6.940181
Conceição do Araguaia;PA;-49.268913;-8.261356
Couto de Magalhães;TO;-49.246647;-8.282820
Juarina;TO;-49.064347;-8.119507
Bernardo Sayão;TO;-48.889273;-7.874812
Arapoema;TO;-49.063702;-7.654634
Colinas do Tocantins;TO;-48.475728;-8.057640
Bandeirantes do Tocantins;TO;-48.583567;-7.756125
Nova Olinda;TO;-48.425232;-7.631712
Piçarra;PA;-48.871633;-6.437783
Santa Fé do Araguaia;TO;-48.716462;-7.158034
Muricilândia;TO;-48.609135;-7.146690
Aragominas;TO;-48.529096;-7.160051
Carmolândia;TO;-48.397809;-7.032616
Araguanã;TO;-48.639542;-6.582247
São Geraldo do Araguaia;PA;-48.559169;-6.394715
Xambioá;TO;-48.531974;-6.414104
Piraquê;TO;-48.295796;-6.773020
Parauapebas;PA;-49.903733;-6.067812
Curionópolis;PA;-49.606795;-6.099654
Eldorado dos Carajás;PA;-49.355261;-6.103891
Novo Repartimento;PA;-49.949928;-4.247492
Itupiranga;PA;-49.335849;-5.132716
Marabá;PA;-49.132672;-5.380750
São João do Araguaia;PA;-48.792637;-5.363336
Goiatins;TO;-47.325189;-7.714780
Araguaína;TO;-48.204402;-7.192375
Babaçulândia;TO;-47.761257;-7.209227
Wanderlândia;TO;-47.960140;-6.852742
Riachinho;TO;-48.137056;-6.440054
Ananás;TO;-48.073466;-6.364369
Darcinópolis;TO;-47.759709;-6.715912
Angico;TO;-47.861142;-6.391789
Filadélfia;TO;-47.495446;-7.335011
Carolina;MA;-47.463422;-7.335839
Aguiarnópolis;TO;-47.470222;-6.554087
Santa Terezinha do Tocantins;TO;-47.668393;-6.444380
Nazaré;TO;-47.664299;-6.374960
Estreito;MA;-47.443072;-6.560775
Tocantinópolis;TO;-47.422400;-6.324474
Porto Franco;MA;-47.396188;-6.341490
Campos Lindos;TO;-46.864473;-7.989563
Riachão;MA;-46.622528;-7.358189
Feira Nova do Maranhão;MA;-46.678577;-6.965082
São João do Paraíso;MA;-47.059355;-6.456341
São Pedro dos Crentes;MA;-46.531864;-6.823889
Nova Colinas;MA;-46.260668;-7.122628
Fortaleza dos Nogueiras;MA;-46.174893;-6.959826
Formosa da Serra Negra;MA;-46.191583;-6.440173
São Bento do Tocantins;TO;-47.901161;-6.025802
Luzinópolis;TO;-47.858155;-6.177935
Cachoeirinha;TO;-47.923399;-6.115596
Araguatins;TO;-48.123198;-5.646591
Buriti do Tocantins;TO;-48.227134;-5.314483
São Sebastião do Tocantins;TO;-48.202105;-5.261311
Carrasco Bonito;TO;-48.031416;-5.314152
Canaã dos Carajás;PA;-49.877612;-6.496594
Axixá do Tocantins;TO;-47.770070;-5.612747
Augustinópolis;TO;-47.886266;-5.468629
Sampaio;TO;-47.878184;-5.354233
Praia Norte;TO;-47.811144;-5.392812
Maurilândia do Tocantins;TO;-47.512492;-5.951688
Itaguatins;TO;-47.486410;-5.772667
Campestre do Maranhão;MA;-47.362468;-6.170747
Ribamar Fiquene;MA;-47.388777;-5.930666
Sítio Novo do Tocantins;TO;-47.638090;-5.601205
São Miguel do Tocantins;TO;-47.574288;-5.563051
Imperatriz;MA;-47.477726;-5.518471
Governador Edison Lobão;MA;-47.364593;-5.749727
Davinópolis;MA;-47.421665;-5.546372
João Lisboa;MA;-47.406388;-5.443630
Senador La Rocque;MA;-47.295941;-5.446098
Vila Nova dos Martírios;MA;-48.133611;-5.188890
Rondon do Pará;PA;-48.066966;-4.777927
Cidelândia;MA;-47.778145;-5.174655
Açailândia;MA;-47.500383;-4.947137
São Francisco do Brejão;MA;-47.389018;-5.125844
Itinga do Maranhão;MA;-47.523507;-4.452925
Dom Eliseu;PA;-47.505688;-4.283786
Lajeado Novo;MA;-47.029292;-6.185385
Montes Altos;MA;-47.067294;-5.830665
Sítio Novo;MA;-46.703329;-5.876006
Buritirana;MA;-47.013088;-5.598233
Amarante do Maranhão;MA;-46.747284;-5.569130
Grajaú;MA;-46.146233;-5.813670
Bom Jesus das Selvas;MA;-46.864118;-4.476382
Buriticupu;MA;-46.440883;-4.323755
Tucuruí;PA;-49.677336;-3.765702
Breu Branco;PA;-49.573459;-3.771908
Baião;PA;-49.669443;-2.790212
Mocajuba;PA;-49.504224;-2.583098
Tailândia;PA;-48.948873;-2.945839
Bagre;PA;-50.198690;-1.900565
Oeiras do Pará;PA;-49.862792;-2.003576
Curralinho;PA;-49.795227;-1.811793
Limoeiro do Ajuru;PA;-49.390308;-1.898498
São Sebastião da Boa Vista;PA;-49.524893;-1.715972
Anajás;PA;-49.935400;-0.996811
Chaves;PA;-49.987006;-0.164154
Igarapé-Miri;PA;-48.957453;-1.975326
Moju;PA;-48.766765;-1.889926
Abaetetuba;PA;-48.878843;-1.721828
Muaná;PA;-49.222378;-1.539359
Ponta de Pedras;PA;-48.866073;-1.395873
Barcarena;PA;-48.619529;-1.511872
Ananindeua;PA;-48.374298;-1.363914
Marituba;PA;-48.342064;-1.360020
Benevides;PA;-48.243408;-1.361829
Santa Bárbara do Pará;PA;-48.295101;-1.226144
Santa Cruz do Arari;PA;-49.177139;-0.661019
Cachoeira do Arari;PA;-48.950290;-1.012259
Salvaterra;PA;-48.513921;-0.758444
Soure;PA;-48.501536;-0.730320
Colares;PA;-48.280254;-0.936423
Ulianópolis;PA;-47.489239;-3.750074
Tomé-Açu;PA;-48.154100;-2.418028
Paragominas;PA;-47.352692;-3.002116
Ipixuna do Pará;PA;-47.505911;-2.559921
Acará;PA;-48.198513;-1.953833
Concórdia do Pará;PA;-47.942204;-1.992375
São Domingos do Capim;PA;-47.766460;-1.687684
Bujaru;PA;-48.038063;-1.517616
Santa Isabel do Pará;PA;-48.156622;-1.297847
Santo Antônio do Tauá;PA;-48.131446;-1.152200
Inhangapi;PA;-47.911393;-1.434897
Castanhal;PA;-47.916742;-1.297971
São Francisco do Pará;PA;-47.791676;-1.169632
Mãe do Rio;PA;-47.560144;-2.056833
São Miguel do Guamá;PA;-47.478382;-1.613072
Irituia;PA;-47.445997;-1.769842
Santa Maria do Pará;PA;-47.571166;-1.353918
Igarapé-Açu;PA;-47.617409;-1.136139
Bonito;PA;-47.306572;-1.367451
Nova Timboteua;PA;-47.392062;-1.208740
Peixe-Boi;PA;-47.324031;-1.193824
Vigia;PA;-48.138649;-0.861194
São Caetano de Odivelas;PA;-48.024622;-0.747293
Terra Alta;PA;-47.900433;-1.029627
São João da Ponta;PA;-47.918022;-0.857885
Curuçá;PA;-47.851480;-0.733214
Magalhães Barata;PA;-47.601445;-0.803391
Marapanim;PA;-47.703360;-0.714702
Maracanã;PA;-47.451983;-0.778899
Santarém Novo;PA;-47.385457;-0.930970
Salinópolis;PA;-47.346523;-0.630815
Garrafão do Norte;PA;-47.050499;-1.929858
Capitão Poço;PA;-47.062930;-1.747847
Ourém;PA;-47.112649;-1.541676
Capanema;PA;-47.177778;-1.205291
Santa Luzia do Pará;PA;-46.900829;-1.521470
Tracuateua;PA;-46.898131;-1.074270
Bragança;PA;-46.782600;-1.061263
Cachoeira do Piriá;PA;-46.545930;-1.759738
Boa Vista do Gurupi;MA;-46.300223;-1.776137
Viseu;PA;-46.139906;-1.191240
Primavera;PA;-47.125270;-0.945439
Quatipuru;PA;-47.013434;-0.899604
São João de Pirabas;PA;-47.181036;-0.780222
Augusto Corrêa;PA;-46.645551;-1.021634
Balsas;MA;-46.037163;-7.532140
Baixa Grande do Ribeiro;PI;-45.218977;-7.849031
Ribeiro Gonçalves;PI;-45.244737;-7.556505
Sambaíba;MA;-45.351506;-7.134473
São Raimundo das Mangabeiras;MA;-45.480880;-7.021834
Loreto;MA;-45.145081;-7.081110
São Félix de Balsas;MA;-44.809249;-7.075345
São Domingos do Azeitão;MA;-44.650882;-6.814706
Uruçuí;PI;-44.557683;-7.239439
Benedito Leite;MA;-44.557705;-7.210368
Antônio Almeida;PI;-44.188929;-7.212759
Porto Alegre do Piauí;PI;-44.183668;-6.964228
Mirador;MA;-44.368276;-6.374543
Nova Iorque;MA;-44.047137;-6.730471
Pastos Bons;MA;-44.074540;-6.602958
Sucupira do Norte;MA;-44.191889;-6.478392
Jenipapo dos Vieiras;MA;-45.635607;-5.362375
Barra do Corda;MA;-45.248496;-5.496823
Arame;MA;-46.003201;-4.883470
Governador Archer;MA;-44.275412;-5.020778
Lima Campos;MA;-44.464625;-4.518375
Alto Alegre do Maranhão;MA;-44.446000;-4.213000
Peritoró;MA;-44.336909;-4.374589
Colônia do Gurguéia;PI;-43.793999;-8.183697
Manoel Emídio;PI;-43.875490;-8.012340
Eliseu Martins;PI;-43.670481;-8.096286
Bertolínia;PI;-43.949778;-7.633377
Sebastião Leal;PI;-44.059982;-7.568029
Canavieira;PI;-43.723330;-7.688215
Pavussu;PI;-43.228438;-7.960590
Rio Grande do Piauí;PI;-43.136886;-7.780291
Itaueira;PI;-43.024900;-7.599890
Landri Sales;PI;-43.936418;-7.259218
Marcos Parente;PI;-43.892602;-7.115649
Paraibano;MA;-43.979243;-6.426403
Guadalupe;PI;-43.559441;-6.782848
São João dos Patos;MA;-43.703623;-6.493400
Sucupira do Riachão;MA;-43.545472;-6.408584
Jerumenha;PI;-43.503319;-7.091280
Floriano;PI;-43.024136;-6.771821
Barão de Grajaú;MA;-43.026109;-6.744628
Brejo do Piauí;PI;-42.822917;-8.203140
Canto do Buriti;PI;-42.951721;-8.111101
Socorro do Piauí;PI;-42.492179;-7.867730
Flores do Piauí;PI;-42.918009;-7.787931
Pajeú do Piauí;PI;-42.824810;-7.855085
Ribeira do Piauí;PI;-42.712778;-7.690278
São José do Peixe;PI;-42.567162;-7.485535
São João do Piauí;PI;-42.255898;-8.354661
Pedro Laurentino;PI;-42.284744;-8.068068
Nova Santa Rita;PI;-42.047127;-8.097073
Paes Landim;PI;-42.247444;-7.773745
São Miguel do Fidalgo;PI;-42.367591;-7.597133
São Francisco do Piauí;PI;-42.541010;-7.246299
Nazaré do Piauí;PI;-42.677316;-6.970235
Francisco Ayres;PI;-42.688149;-6.626064
Arraial;PI;-42.541809;-6.650753
Colônia do Piauí;PI;-42.175555;-7.226512
Oeiras;PI;-42.128298;-7.019153
Cajazeiras do Piauí;PI;-42.390278;-6.796667
Miguel Leão;PI;-42.743594;-5.680770
Curralinhos;PI;-42.837602;-5.608252
Monsenhor Gil;PI;-42.607493;-5.561999
Lagoa do Piauí;PI;-42.643659;-5.418639
Demerval Lobão;PI;-42.677583;-5.358752
Passagem Franca do Piauí;PI;-42.443630;-5.860365
Elesbão Veloso;PI;-42.135458;-6.199475
Raposa;MA;-44.097343;-2.425399
Junco do Maranhão;MA;-46.090025;-1.838879
Maracaçumé;MA;-45.958734;-2.049177
Amapá do Maranhão;MA;-46.002442;-1.675241
Uruçuca;BA;-39.285085;-14.596268
Santa Rosa do Piauí;PI;-42.281375;-6.795810
Tanque do Piauí;PI;-42.279515;-6.597869
Várzea Grande;PI;-42.247973;-6.548993
Caxias;MA;-43.361749;-4.865053
Aldeias Altas;MA;-43.468931;-4.626213
Coelho Neto;MA;-43.010780;-4.252454
São Francisco do Maranhão;MA;-42.866767;-6.251590
Amarante;PI;-42.843343;-6.243045
Angical do Piauí;PI;-42.740047;-6.087864
Regeneração;PI;-42.684193;-6.231150
Santo Antônio dos Milagres;PI;-42.712322;-6.046473
São Gonçalo do Piauí;PI;-42.709537;-5.993934
São Pedro do Piauí;PI;-42.719211;-5.920780
Agricolândia;PI;-42.666371;-5.796763
Água Branca;PI;-42.636961;-5.888564
Lagoinha do Piauí;PI;-42.622325;-5.830742
Hugo Napoleão;PI;-42.559755;-5.988597
Olho d'Água do Piauí;PI;-42.559392;-5.841246
Barro Duro;PI;-42.514656;-5.816732
São Félix do Piauí;PI;-42.117179;-5.934853
São Miguel da Baixa Grande;PI;-42.193423;-5.856457
Santa Cruz dos Milagres;PI;-41.950644;-5.805808
Beneditinos;PI;-42.363820;-5.456758
Alto Longá;PI;-42.209582;-5.256341
Prata do Piauí;PI;-42.204644;-5.672653
Timon;MA;-42.832920;-5.097692
José de Freitas;PI;-42.574586;-4.751458
União;PI;-42.858285;-4.585709
Lagoa Alegre;PI;-42.630876;-4.515394
Altos;PI;-42.461160;-5.038879
Coivaras;PI;-42.207951;-5.092242
Campo Maior;PI;-42.164124;-4.821697
Cabeceiras do Piauí;PI;-42.306889;-4.477300
Barras;PI;-42.292161;-4.244675
Nossa Senhora de Nazaré;PI;-42.172954;-4.630188
Boqueirão do Piauí;PI;-42.121232;-4.481806
Cocal de Telha;PI;-41.958668;-4.557098
Boa Hora;PI;-42.135748;-4.414041
Santa Luzia;MA;-45.690026;-4.068726
Tufilândia;MA;-45.623781;-3.673550
Porto Seguro;BA;-39.064251;-16.443473
Santa Cruz Cabrália;BA;-39.029495;-16.282529
Belmonte;BA;-38.875780;-15.860789
Pau Brasil;BA;-39.645794;-15.457226
Camacan;BA;-39.491937;-15.414157
Santa Luzia;BA;-39.328678;-15.434188
Arataca;BA;-39.419017;-15.265099
Itaju do Colônia;BA;-39.728298;-15.136598
Santa Cruz da Vitória;BA;-39.811503;-14.963975
Floresta Azul;BA;-39.657877;-14.862934
Ibicaraí;BA;-39.591434;-14.857877
Almadina;BA;-39.641491;-14.708869
Jussari;BA;-39.490962;-15.192010
São José da Vitória;BA;-39.343685;-15.078666
Itapé;BA;-39.423858;-14.887641
Mascote;BA;-39.301602;-15.554159
Una;BA;-39.076507;-15.279076
Canavieiras;BA;-38.953648;-15.672205
Buerarema;BA;-39.302849;-14.959497
Itabuna;BA;-39.278056;-14.787573
Ilhéus;BA;-39.046020;-14.793045
Itagibá;BA;-39.844945;-14.278239
Barra do Rocha;BA;-39.599098;-14.199994
Coaraci;BA;-39.555609;-14.636996
Itajuípe;BA;-39.369823;-14.678766
Itacaré;BA;-38.995910;-14.278422
Camamu;BA;-39.107082;-13.939773
Igrapiúna;BA;-39.136129;-13.829543
Ituberá;BA;-39.148059;-13.724936
Maraú;BA;-39.013656;-14.103549
Cravolândia;BA;-39.803083;-13.353063
Santa Inês;BA;-39.813979;-13.279300
Ubaíra;BA;-39.665991;-13.271414
Teolândia;BA;-39.483986;-13.589639
Presidente Tancredo Neves;BA;-39.420267;-13.447064
Jiquiriçá;BA;-39.573650;-13.262101
Mutuípe;BA;-39.504367;-13.228381
Laje;BA;-39.421264;-13.167327
Brejões;BA;-39.798797;-13.103948
Amargosa;BA;-39.601966;-13.021462
Itatim;BA;-39.695199;-12.709888
São Miguel das Matas;BA;-39.457844;-13.043387
Elísio Medrado;BA;-39.519087;-12.941743
Varzedo;BA;-39.391874;-12.967153
Santa Teresinha;BA;-39.520111;-12.769130
Centro do Guilherme;MA;-46.034486;-2.448908
São Bento do Norte;RN;-35.958705;-5.092594
Centro Novo do Maranhão;MA;-46.122841;-2.126963
Governador Nunes Freire;MA;-45.877680;-2.128991
Santa Luzia do Paruá;MA;-45.780137;-2.511229
Presidente Médici;MA;-45.820014;-2.389908
Maranhãozinho;MA;-45.850679;-2.240777
Pedro do Rosário;MA;-45.349316;-2.972719
Presidente Sarney;MA;-45.359492;-2.587991
Santa Helena;MA;-45.289976;-2.244263
Turilândia;MA;-45.304411;-2.216382
Conceição do Lago-Açu;MA;-44.889531;-3.851417
Lago Verde;MA;-44.825997;-3.946611
Vitória do Mearim;MA;-44.864299;-3.451248
Cajari;MA;-45.014498;-3.327416
Viana;MA;-44.991177;-3.204510
Arari;MA;-44.766459;-3.452136
Miranda do Norte;MA;-44.581449;-3.563129
Anajatuba;MA;-44.612594;-3.262693
São Mateus do Maranhão;MA;-44.470691;-4.037361
Coroatá;MA;-44.124414;-4.134422
Pirapemas;MA;-44.221632;-3.720412
Matões do Norte;MA;-44.546772;-3.624404
Cantanhede;MA;-44.383024;-3.637569
Itapecuru Mirim;MA;-44.350848;-3.402019
Matinha;MA;-45.035016;-3.098487
Olinda Nova do Maranhão;MA;-44.989748;-2.992949
São Vicente Ferrer;MA;-44.868074;-2.894871
Palmeirândia;MA;-44.893271;-2.644330
São Bento;MA;-44.828927;-2.697812
São João Batista;MA;-44.795324;-2.953978
Bacurituba;MA;-44.732877;-2.709998
Cajapió;MA;-44.674097;-2.873262
Pinheiro;MA;-45.078825;-2.522244
Peri Mirim;MA;-44.850385;-2.576762
Bequimão;MA;-44.784199;-2.441621
Central do Maranhão;MA;-44.825385;-2.198312
Guimarães;MA;-44.601990;-2.127551
Santa Rita;MA;-44.321078;-3.142409
Bacabeira;MA;-44.316373;-2.964519
Rosário;MA;-44.253065;-2.934443
Presidente Juscelino;MA;-44.071480;-2.918724
Cachoeira Grande;MA;-44.052763;-2.930745
Axixá;MA;-44.061967;-2.839385
Icatu;MA;-44.050061;-2.772065
Alcântara;MA;-44.406182;-2.395737
Paço do Lumiar;MA;-44.101886;-2.516573
São José de Ribamar;MA;-44.059745;-2.547040
Godofredo Viana;MA;-45.779460;-1.402591
Cândido Mendes;MA;-45.716112;-1.432650
Luís Domingues;MA;-45.866979;-1.274924
Turiaçu;MA;-45.379791;-1.658925
Serrano do Maranhão;MA;-45.120735;-1.852285
Bacuri;MA;-45.132839;-1.696501
Apicum-Açu;MA;-45.086450;-1.458622
Cururupu;MA;-44.864433;-1.814746
Mirinzal;MA;-44.778655;-2.070935
Porto Rico do Maranhão;MA;-44.584229;-1.859247
Cedral;MA;-44.528114;-2.000269
Vargem Grande;MA;-43.916960;-3.536385
Nina Rodrigues;MA;-43.913420;-3.467884
Presidente Vargas;MA;-44.023403;-3.407866
São Benedito do Rio Preto;MA;-43.528720;-3.335147
Afonso Cunha;MA;-43.327527;-4.136313
Chapadinha;MA;-43.353848;-3.738754
Anapurus;MA;-43.101449;-3.675769
Belágua;MA;-43.512153;-3.154854
Urbano Santos;MA;-43.387838;-3.206417
Mata Roma;MA;-43.111204;-3.620354
Morros;MA;-44.035678;-2.853794
Humberto de Campos;MA;-43.464877;-2.598282
Primeira Cruz;MA;-43.423159;-2.505679
Santo Amaro do Maranhão;MA;-43.238033;-2.500681
Duque Bacelar;MA;-42.947696;-4.150019
Miguel Alves;PI;-42.896301;-4.168575
Buriti;MA;-42.917936;-3.941692
Brejo;MA;-42.752704;-3.677957
Nossa Senhora dos Remédios;PI;-42.618408;-3.975744
Porto;PI;-42.699820;-3.888154
Campo Largo do Piauí;PI;-42.639991;-3.804411
Matias Olímpio;PI;-42.550654;-3.714917
Milagres do Maranhão;MA;-42.613121;-3.574429
Santa Quitéria do Maranhão;MA;-42.568771;-3.493085
Madeiro;PI;-42.498072;-3.486245
Curral de Dentro;MG;-41.855684;-15.932709
Medina;MG;-41.472764;-16.224538
Cachoeira de Pajeú;MG;-41.494766;-15.968828
Águas Vermelhas;MG;-41.457127;-15.743057
Joaíma;MG;-41.022873;-16.652189
Jequitinhonha;MG;-41.011717;-16.437482
Pedra Azul;MG;-41.290948;-16.008648
Berizal;MG;-41.743202;-15.610028
Ninheira;MG;-41.756367;-15.314771
Cordeiros;BA;-41.930796;-15.035641
Piripá;BA;-41.716841;-14.944379
Presidente Jânio Quadros;BA;-41.679792;-14.688485
Divisa Alegre;MG;-41.346291;-15.722071
Cândido Sales;BA;-41.241438;-15.499309
Divisópolis;MG;-40.999690;-15.725350
Encruzilhada;BA;-40.912432;-15.530168
Tremedal;BA;-41.414198;-14.973625
Belo Campo;BA;-41.265172;-15.033362
Caraíbas;BA;-41.260336;-14.717677
Felisburgo;MG;-40.760502;-16.634828
Rio do Prado;MG;-40.571371;-16.605648
Palmópolis;MG;-40.429609;-16.736375
Rubim;MG;-40.539714;-16.377494
Almenara;MG;-40.694163;-16.178459
Bandeira;MG;-40.562163;-15.878315
Jordânia;MG;-40.184051;-15.900924
Salto da Divisa;MG;-39.939088;-16.006327
Mata Verde;MG;-40.736559;-15.686917
Ribeirão do Largo;BA;-40.744137;-15.450775
Macarani;BA;-40.420888;-15.564563
Itambé;BA;-40.629955;-15.242913
Vitória da Conquista;BA;-40.844159;-14.861466
Caatiba;BA;-40.409156;-14.969854
Barra do Choça;BA;-40.579058;-14.865423
Maiquinique;BA;-40.258722;-15.623985
Itapetinga;BA;-40.248236;-15.247533
Itarantim;BA;-40.064960;-15.652819
Potiraguá;BA;-39.863846;-15.594290
Nova Canaã;BA;-40.145800;-14.791242
Itororó;BA;-40.068401;-15.109988
Firmino Alves;BA;-39.926859;-14.982334
Ibicuí;BA;-39.987915;-14.844960
Iguaí;BA;-40.089417;-14.752754
Guajeru;BA;-41.938064;-14.546680
Malhada de Pedras;BA;-41.884220;-14.384673
Maetinga;BA;-41.491460;-14.662335
Aracatu;BA;-41.464783;-14.427986
Brumado;BA;-41.669592;-14.202127
Livramento de Nossa Senhora;BA;-41.843187;-13.636868
Dom Basílio;BA;-41.767663;-13.756499
Anagé;BA;-41.135569;-14.615120
Caetanos;BA;-40.917506;-14.334745
Tanhaçu;BA;-41.247271;-14.019662
Ituaçu;BA;-41.300284;-13.810651
Contendas do Sincorá;BA;-41.047953;-13.753690
Rio de Contas;BA;-41.804764;-13.585218
Piatã;BA;-41.770177;-13.146549
Jussiape;BA;-41.588209;-13.515514
Boninal;BA;-41.828604;-12.706887
Barra da Estiva;BA;-41.334697;-13.623699
Ibicoara;BA;-41.283989;-13.405918
Iramaia;BA;-40.959471;-13.290186
Mucugê;BA;-41.370271;-13.005300
Andaraí;BA;-41.329658;-12.804916
Itaeté;BA;-40.967740;-12.983121
Nova Redenção;BA;-41.074785;-12.815001
Ibiquera;BA;-40.933774;-12.644354
Mirante;BA;-40.771799;-14.238469
Planalto;BA;-40.471843;-14.665370
Bom Jesus da Serra;BA;-40.512556;-14.366312
Poções;BA;-40.363354;-14.523391
Boa Nova;BA;-40.206444;-14.359754
Dário Meira;BA;-39.903129;-14.422900
Itagi;BA;-40.013112;-14.161500
Manoel Vitorino;BA;-40.239895;-14.147617
Lafaiete Coutinho;BA;-40.211890;-13.654100
Aiquara;BA;-39.893653;-14.126918
Jitaúna;BA;-39.896892;-14.013085
Jequié;BA;-40.087704;-13.850888
Maracás;BA;-40.432333;-13.435468
Marcionílio Souza;BA;-40.529457;-13.006437
Boa Vista do Tupim;BA;-40.606376;-12.649774
Itiruçu;BA;-40.147186;-13.529043
Lajedo do Tabocal;BA;-40.220429;-13.466286
Planaltino;BA;-40.369519;-13.261789
Jaguaquara;BA;-39.964009;-13.524834
Itaquara;BA;-39.937767;-13.445890
Irajuba;BA;-40.084820;-13.256331
Iaçu;BA;-40.205609;-12.766637
Nova Itarana;BA;-40.065267;-13.024055
Milagres;BA;-39.861092;-12.864635
Guaratinga;BA;-39.784713;-16.583276
Itabela;BA;-39.559318;-16.573188
Eunápolis;BA;-39.582122;-16.371498
Itagimirim;BA;-39.613317;-16.081919
Itapebi;BA;-39.532863;-15.955063
Castro Alves;BA;-39.424753;-12.757950
Nilo Peçanha;BA;-39.109111;-13.603992
Taperoá;BA;-39.100948;-13.532133
Valença;BA;-39.072953;-13.366948
Cairu;BA;-39.046525;-13.490377
Jaguaripe;BA;-38.893891;-13.110924
Santo Antônio de Jesus;BA;-39.258403;-12.961387
Dom Macedo Costa;BA;-39.192259;-12.901582
Muniz Ferreira;BA;-39.109247;-13.009227
Conceição do Almeida;BA;-39.171466;-12.783579
Sapeaçu;BA;-39.182426;-12.720764
São Felipe;BA;-39.089320;-12.839439
Cruz das Almas;BA;-39.100787;-12.667516
Aratuípe;BA;-39.003763;-13.071595
Nazaré;BA;-39.010835;-13.023491
Governador Mangabeira;BA;-39.041207;-12.599415
Muritiba;BA;-38.992052;-12.632901
São Félix;BA;-38.972710;-12.610433
Cachoeira;BA;-38.958695;-12.599443
Maragogipe;BA;-38.917519;-12.776020
Salinas da Margarida;BA;-38.756164;-12.873014
Itaparica;BA;-38.679950;-12.893205
Vera Cruz;BA;-38.615295;-12.956845
Saubara;BA;-38.762473;-12.738664
São Francisco do Conde;BA;-38.678601;-12.618345
Madre de Deus;BA;-38.615316;-12.744621
Candeias;BA;-38.547152;-12.671566
Lauro de Freitas;BA;-38.321008;-12.897800
Simões Filho;BA;-38.402944;-12.786587
Camaçari;BA;-38.326265;-12.699638
Seabra;BA;-41.772159;-12.416905
Palmeiras;BA;-41.580886;-12.505865
Iraquara;BA;-41.615504;-12.242880
Souto Soares;BA;-41.642717;-12.088046
Barro Alto;BA;-41.905441;-11.760493
Canarana;BA;-41.767705;-11.685831
Mulungu do Morro;BA;-41.637352;-11.964776
Cafarnaum;BA;-41.468823;-11.691399
Lençóis;BA;-41.392825;-12.561604
Wagner;BA;-41.171476;-12.281901
Lajedinho;BA;-40.904752;-12.352889
Utinga;BA;-41.095432;-12.078277
Bonito;BA;-41.264698;-11.966768
Morro do Chapéu;BA;-41.156461;-11.548804
Lapão;BA;-41.828615;-11.385096
Irecê;BA;-41.853462;-11.303317
São Gabriel;BA;-41.884267;-11.217453
João Dourado;BA;-41.654799;-11.348642
América Dourada;BA;-41.439007;-11.442938
Várzea Nova;BA;-40.943173;-11.255685
Umburanas;BA;-41.323428;-10.733902
Ourolândia;BA;-41.075576;-10.957848
Ruy Barbosa;BA;-40.493101;-12.281612
Dormentes;PE;-40.766168;-8.441163
Lagoa Grande;PE;-40.276732;-8.994525
Curaçá;BA;-39.899671;-8.984577
Paripiranga;BA;-37.862561;-10.685885
Simão Dias;SE;-37.809746;-10.738652
Andorinha;BA;-39.839142;-10.348214
Monte Santo;BA;-39.332077;-10.437413
Ibirajuba;PE;-36.181151;-8.576331
Cachoeirinha;PE;-36.240182;-8.486685
Cajueiro;AL;-36.155937;-9.399402
Capela;AL;-36.082583;-9.415041
Murici;AL;-35.942798;-9.306823
Branquinha;AL;-36.016152;-9.233420
São José da Laje;AL;-36.051489;-9.012779
União dos Palmares;AL;-36.022270;-9.159210
Ibateguara;AL;-35.937325;-8.978232
Messias;AL;-35.839182;-9.393843
Flexeiras;AL;-35.713933;-9.272807
Joaquim Gomes;AL;-35.747381;-9.132805
Colônia Leopoldina;AL;-35.721414;-8.918058
Novo Lino;AL;-35.664001;-8.941905
Bodocó;PE;-39.933797;-7.777592
Alegrete do Piauí;PI;-40.856587;-7.241964
Francisco Macedo;PI;-40.787999;-7.331000
Hidrolândia;CE;-40.405595;-4.409579
Tapiramutá;BA;-40.792656;-11.847480
Mundo Novo;BA;-40.471390;-11.854080
Piritiba;BA;-40.558739;-11.730008
Itaberaba;BA;-40.305897;-12.524182
Macajuba;BA;-40.357058;-12.132592
Baixa Grande;BA;-40.168970;-11.951884
Mairi;BA;-40.143732;-11.710704
Várzea da Roça;BA;-40.132829;-11.600463
Pintadas;BA;-39.900859;-11.811712
Miguel Calmon;BA;-40.603084;-11.429891
Jacobina;BA;-40.511693;-11.181217
Caém;BA;-40.432039;-11.067693
Mirangaba;BA;-40.573996;-10.960983
Saúde;BA;-40.415458;-10.942790
Várzea do Poço;BA;-40.314941;-11.527307
Serrolândia;BA;-40.298327;-11.408476
Quixabeira;BA;-40.120045;-11.403061
Caldeirão Grande;BA;-40.295577;-11.020798
Capim Grosso;BA;-40.008951;-11.379659
São José do Jacuípe;BA;-39.866934;-11.413680
Ponto Novo;BA;-40.131144;-10.865300
Pindobaçu;BA;-40.367472;-10.743338
Campo Formoso;BA;-40.320031;-10.510550
Antônio Gonçalves;BA;-40.278489;-10.576686
Filadélfia;BA;-40.143710;-10.740475
Sento Sé;BA;-41.878563;-9.741378
Capitão Gervásio Oliveira;PI;-41.813973;-8.496548
Lagoa do Barro do Piauí;PI;-41.534200;-8.476734
Casa Nova;BA;-40.973977;-9.164083
Queimada Nova;PI;-41.410614;-8.570637
Afrânio;PE;-41.009544;-8.511358
Sobradinho;BA;-40.814550;-9.450243
Senhor do Bonfim;BA;-40.186532;-10.459354
Jaguarari;BA;-40.199938;-10.256859
Juazeiro;BA;-40.503251;-9.416217
Petrolina;PE;-40.502731;-9.388662
Ipirá;BA;-39.735941;-12.156057
Rafael Jambeiro;BA;-39.500658;-12.405257
Serra Preta;BA;-39.330490;-12.156039
Pé de Serra;BA;-39.611037;-11.831289
Capela do Alto Alegre;BA;-39.834912;-11.665797
Nova Fátima;BA;-39.630215;-11.603116
Riachão do Jacuípe;BA;-39.381812;-11.806681
Santo Estêvão;BA;-39.250541;-12.427981
Cabaceiras do Paraguaçu;BA;-39.190231;-12.531694
Antônio Cardoso;BA;-39.117555;-12.433493
Ipecaetá;BA;-39.306921;-12.302846
Anguera;BA;-39.246186;-12.146157
Conceição da Feira;BA;-38.997823;-12.507835
São Gonçalo dos Campos;BA;-38.966294;-12.433096
Feira de Santana;BA;-38.966293;-12.266429
Tanquinho;BA;-39.103312;-11.967965
Candeal;BA;-39.120277;-11.804851
Conceição do Coité;BA;-39.280827;-11.559953
Ichu;BA;-39.190523;-11.743065
Santa Bárbara;BA;-38.968085;-11.951490
Santanópolis;BA;-38.869372;-12.031109
Serrinha;BA;-39.009976;-11.658407
Lamarão;BA;-38.887009;-11.773013
Biritinga;BA;-38.805055;-11.607156
Gavião;BA;-39.775751;-11.468796
São Domingos;BA;-39.526792;-11.464927
Valente;BA;-39.456953;-11.406233
Retirolândia;BA;-39.423358;-11.483198
Santaluz;BA;-39.375005;-11.250826
Queimadas;BA;-39.629347;-10.973561
Itiúba;BA;-39.844616;-10.694761
Nordestina;BA;-39.429705;-10.819169
Cansanção;BA;-39.494388;-10.664713
Teofilândia;BA;-38.991264;-11.482719
Araci;BA;-38.958385;-11.325316
Quijingue;BA;-39.213694;-10.750519
Euclides da Cunha;BA;-39.015286;-10.507754
Santo Amaro;BA;-38.713679;-12.547206
Amélia Rodrigues;BA;-38.756289;-12.391429
Conceição do Jacuípe;BA;-38.768433;-12.326831
Terra Nova;BA;-38.623804;-12.388754
Coração de Maria;BA;-38.748654;-12.233332
Teodoro Sampaio;BA;-38.634738;-12.294998
Pedrão;BA;-38.648715;-12.149140
São Sebastião do Passé;BA;-38.490513;-12.512264
Mata de São João;BA;-38.300898;-12.530685
Catu;BA;-38.379108;-12.351274
Pojuca;BA;-38.337436;-12.430336
Aramari;BA;-38.496937;-12.088399
Alagoinhas;BA;-38.420772;-12.133526
Irará;BA;-38.763139;-12.050449
Água Fria;BA;-38.763863;-11.861836
Ouriçangas;BA;-38.616624;-12.017497
Sátiro Dias;BA;-38.593822;-11.592928
Inhambupe;BA;-38.355048;-11.780955
Araças;BA;-38.202658;-12.220038
Itanagra;BA;-38.043595;-12.261435
Entre Rios;BA;-38.087125;-11.939164
Aporá;BA;-38.081418;-11.657708
Acajutiba;BA;-38.019737;-11.657522
Cardeal da Silva;BA;-37.946905;-11.947191
Esplanada;BA;-37.943216;-11.794215
Jandaíra;BA;-37.785274;-11.561614
Olindina;BA;-38.337863;-11.349693
Nova Soure;BA;-38.487103;-11.232948
Cipó;BA;-38.517942;-11.103172
Macau;RN;-36.631842;-5.107947
Guamaré;RN;-36.322172;-5.106194
Galinhos;RN;-36.275449;-5.090901
Caiçara do Norte;RN;-36.071690;-5.070913
Pedra Grande;RN;-35.876018;-5.149882
Parazinho;RN;-35.839787;-5.222758
Gravatá;PE;-35.567458;-8.211184
Amaraji;PE;-35.450051;-8.376914
Chã Grande;PE;-35.457148;-8.238271
Pombos;PE;-35.396732;-8.139816
Passira;PE;-35.581261;-7.997102
Salgadinho;PE;-35.650288;-7.926901
João Alfredo;PE;-35.578715;-7.865649
Feira Grande;AL;-36.681521;-9.898586
Arapiraca;AL;-36.661471;-9.754866
São Sebastião;AL;-36.558967;-9.930434
Junqueiro;AL;-36.480346;-9.906961
Limoeiro de Anadia;AL;-36.512097;-9.740983
Graça;CE;-40.749026;-4.044215
Pacujá;CE;-40.698885;-3.983272
Ibiapina;CE;-40.891147;-3.924025
Frecheirinha;CE;-40.818019;-3.755572
Mucambo;CE;-40.745222;-3.902714
Reriutaba;CE;-40.575915;-4.141911
Varjota;CE;-40.474083;-4.193871
Cariré;CE;-40.476028;-3.948584
Groaíras;CE;-40.385225;-3.917868
Coreaú;CE;-40.658656;-3.541496
Moraújo;CE;-40.677577;-3.463112
Martinópole;CE;-40.689581;-3.225198
Alcântaras;CE;-40.547867;-3.585369
Meruoca;CE;-40.453106;-3.539741
Uruoca;CE;-40.562760;-3.308194
Senador Sá;CE;-40.466200;-3.353050
Sobral;CE;-40.348210;-3.689133
Forquilha;CE;-40.263405;-3.799447
Cumaru;PE;-35.695748;-8.008266
Alcantil;PB;-36.051083;-7.736681
Riacho de Santo Antônio;PB;-36.157018;-7.680234
Boqueirão;PB;-36.130887;-7.487004
Barra de Santana;PB;-35.991325;-7.518085
Caturité;PB;-36.030589;-7.416586
Santa Maria do Cambucá;PE;-35.894138;-7.836760
Vertente do Lério;PE;-35.849054;-7.770844
Santa Cecília;PB;-35.876375;-7.738901
Ribeira do Amparo;BA;-38.424211;-11.042071
Tucano;BA;-38.789407;-10.958388
Ribeira do Pombal;BA;-38.538152;-10.837299
Banzaê;BA;-38.621243;-10.578784
Heliópolis;BA;-38.290664;-10.682539
Cícero Dantas;BA;-38.379377;-10.589664
Crisópolis;BA;-38.151480;-11.505929
Itapicuru;BA;-38.226229;-11.308836
Rio Real;BA;-37.933243;-11.481370
Cristinápolis;SE;-37.758494;-11.466778
Tomar do Geru;SE;-37.843338;-11.369391
Tobias Barreto;SE;-37.999451;-11.179799
Itabaianinha;SE;-37.787505;-11.269268
Poço Verde;SE;-38.181294;-10.715129
Fátima;BA;-38.223891;-10.616013
Adustina;BA;-38.111265;-10.543690
Uauá;BA;-39.479396;-9.833247
Canudos;BA;-39.147059;-9.900135
Santa Maria da Boa Vista;PE;-39.824056;-8.797655
Orocó;PE;-39.602631;-8.610258
Chorrochó;BA;-39.097942;-8.969498
Macururé;BA;-39.051784;-9.162258
Abaré;BA;-39.116178;-8.720734
Cabrobó;PE;-39.309384;-8.505484
Belém de São Francisco;PE;-38.962255;-8.750460
Antas;BA;-38.340118;-10.385621
Novo Triunfo;BA;-38.401374;-10.318235
Jeremoabo;BA;-38.347097;-10.068480
Sítio do Quinto;BA;-38.221325;-10.354453
Coronel João Sá;BA;-37.919825;-10.284700
Pedro Alexandre;BA;-37.893178;-10.012005
Santa Brígida;BA;-38.120935;-9.732273
Canindé de São Francisco;SE;-37.792294;-9.648823
Piranhas;AL;-37.756987;-9.623996
Olho d'Água do Casado;AL;-37.830073;-9.503573
Petrolândia;PE;-38.302664;-9.068627
Rodelas;BA;-38.779990;-8.850214
Itacuruba;PE;-38.697504;-8.822306
Floresta;PE;-38.568675;-8.603066
Paulo Afonso;BA;-38.221560;-9.398296
Glória;BA;-38.254397;-9.343822
Jatobá;PE;-38.260651;-9.174761
Tacaratu;PE;-38.150401;-9.097976
Delmiro Gouveia;AL;-37.998676;-9.385336
Pariconha;AL;-37.998751;-9.256342
Água Branca;AL;-37.937988;-9.261996
Inhapi;AL;-37.750871;-9.225936
Inajá;PE;-37.835124;-8.902058
Conde;BA;-37.613094;-11.817937
Umbaúba;SE;-37.662347;-11.380921
Indiaroba;SE;-37.514979;-11.515746
Arauá;SE;-37.620058;-11.261391
Pedrinhas;SE;-37.677490;-11.190174
Riachão do Dantas;SE;-37.730985;-11.072904
Boquim;SE;-37.619466;-11.139720
Santa Luzia do Itanhy;SE;-37.458642;-11.353596
Estância;SE;-37.448381;-11.265922
Salgado;SE;-37.480385;-11.028833
Lagarto;SE;-37.668948;-10.913628
São Domingos;SE;-37.568515;-10.791613
Pinhão;SE;-37.724246;-10.567664
Pedra Mole;SE;-37.692189;-10.613439
Campo do Brito;SE;-37.495369;-10.739248
Macambira;SE;-37.541303;-10.661871
Frei Paulo;SE;-37.527934;-10.551292
Itaporanga d'Ajuda;SE;-37.307822;-10.989955
Areia Branca;SE;-37.325063;-10.758005
Itabaiana;SE;-37.427287;-10.682555
Ribeirópolis;SE;-37.438035;-10.535669
Malhador;SE;-37.300437;-10.664926
Moita Bonita;SE;-37.351210;-10.576908
São Cristóvão;SE;-37.204360;-11.008443
Laranjeiras;SE;-37.173123;-10.798121
Nossa Senhora do Socorro;SE;-37.123113;-10.846822
Barra dos Coqueiros;SE;-37.032317;-10.899623
Santo Amaro das Brotas;SE;-37.056430;-10.789225
Riachuelo;SE;-37.196551;-10.735008
Santa Rosa de Lima;SE;-37.193123;-10.643362
Divina Pastora;SE;-37.150557;-10.678161
Siriri;SE;-37.113068;-10.596488
Maruim;SE;-37.085598;-10.730762
Rosário do Catete;SE;-37.035690;-10.690432
General Maynard;SE;-36.983758;-10.683471
Carmópolis;SE;-36.988742;-10.644869
Capela;SE;-37.062847;-10.506928
Pirambu;SE;-36.854427;-10.721516
Japaratuba;SE;-36.941765;-10.584913
Carira;SE;-37.700241;-10.352361
Monte Alegre de Sergipe;SE;-37.561605;-10.025574
Nossa Senhora Aparecida;SE;-37.451699;-10.394387
São Miguel do Aleixo;SE;-37.383560;-10.384662
Feira Nova;SE;-37.314672;-10.261645
Nossa Senhora da Glória;SE;-37.421125;-10.215809
Poço Redondo;SE;-37.683263;-9.806155
Pão de Açúcar;AL;-37.440336;-9.740325
Porto da Folha;SE;-37.284233;-9.916264
Belo Monte;AL;-37.277014;-9.822718
Senador Rui Palmeira;AL;-37.457606;-9.469865
São José da Tapera;AL;-37.383125;-9.557684
Carneiros;AL;-37.377303;-9.484762
Palestina;AL;-37.339037;-9.674928
Monteirópolis;AL;-37.250463;-9.603569
Olho d'Água das Flores;AL;-37.297093;-9.536858
São Brás;AL;-36.896529;-10.120734
Olho d'Água Grande;AL;-36.810082;-10.057167
Jacaré dos Homens;AL;-37.207636;-9.635446
Batalha;AL;-37.133014;-9.674195
Olivença;AL;-37.195441;-9.519538
Jaramataia;AL;-37.004558;-9.662243
Major Isidoro;AL;-36.992006;-9.530086
Girau do Ponciano;AL;-36.831592;-9.884041
Campo Grande;AL;-36.792608;-9.955423
Lagoa da Canoa;AL;-36.741257;-9.832909
Craíbas;AL;-36.769726;-9.617798
Mata Grande;AL;-37.732298;-9.118243
Manari;PE;-37.631298;-8.964901
Minador do Negrão;AL;-36.869553;-9.312358
Estrela de Alagoas;AL;-36.764407;-9.390894
Iati;PE;-36.849763;-9.045586
Buíque;PE;-37.160564;-8.619539
Arcoverde;PE;-37.057748;-8.415192
Venturosa;PE;-36.874187;-8.578855
Pedra;PE;-36.939962;-8.496411
Alagoinha;PE;-36.778767;-8.466504
Pacatuba;SE;-36.653078;-10.453811
Santana do São Francisco;SE;-36.610539;-10.292233
Neópolis;SE;-36.584952;-10.321503
Penedo;AL;-36.581905;-10.287422
Ilha das Flores;SE;-36.547935;-10.442506
Brejo Grande;SE;-36.461102;-10.429713
Igreja Nova;AL;-36.659705;-10.123463
Piaçabuçu;AL;-36.433968;-10.405989
Feliz Deserto;AL;-36.302788;-10.293491
Crato;CE;-39.410299;-7.215303
Farias Brito;CE;-39.565114;-6.921460
Tarrafas;CE;-39.753048;-6.678376
Jucás;CE;-39.518679;-6.515229
Cariús;CE;-39.491619;-6.524282
Barbalha;CE;-39.302080;-7.298203
Juazeiro do Norte;CE;-39.307593;-7.196207
Missão Velha;CE;-39.143046;-7.235219
Caririaçu;CE;-39.282760;-7.028076
Granjeiro;CE;-39.214390;-6.881340
Milagres;CE;-38.937829;-7.297492
Pedra Preta;RN;-36.108367;-5.573524
Caiçara do Rio do Vento;RN;-35.993772;-5.765412
Jardim de Angicos;RN;-35.971287;-5.649993
Jandaíra;RN;-36.127836;-5.352106
Bento Fernandes;RN;-35.812996;-5.699057
João Câmara;RN;-35.812186;-5.540940
Poço Branco;RN;-35.663535;-5.622326
Coité do Nóia;AL;-36.584493;-9.633484
Igaci;AL;-36.637191;-9.537678
Taquarana;AL;-36.492844;-9.645290
Belém;AL;-36.490399;-9.570472
Teotônio Vilela;AL;-36.349212;-9.916562
Campo Alegre;AL;-36.352461;-9.784512
Anadia;AL;-36.307783;-9.684886
Tanque d'Arca;AL;-36.436610;-9.533788
Mar Vermelho;AL;-36.388076;-9.447393
Maribondo;AL;-36.304488;-9.583528
Boca da Mata;AL;-36.212498;-9.643082
Pindoba;AL;-36.291829;-9.473822
Coruripe;AL;-36.171724;-10.127632
São Miguel dos Campos;AL;-36.097142;-9.783010
Roteiro;AL;-35.978247;-9.835029
Pilar;AL;-35.954294;-9.601348
Atalaia;AL;-36.008552;-9.511898
Barra de São Miguel;AL;-35.905667;-9.838420
Marechal Deodoro;AL;-35.896731;-9.709715
Coqueiro Seco;AL;-35.799377;-9.637147
Santa Luzia do Norte;AL;-35.823196;-9.603700
Satuba;AL;-35.822697;-9.569113
Rio Largo;AL;-35.839435;-9.477829
Palmeira dos Índios;AL;-36.632812;-9.405681
Quebrangulo;AL;-36.469239;-9.320010
Bom Conselho;PE;-36.685683;-9.169191
Terezinha;PE;-36.627236;-9.056208
Saloá;PE;-36.691031;-8.972301
Paranatama;PE;-36.654905;-8.918750
Lagoa do Ouro;PE;-36.458373;-9.125668
Brejão;PE;-36.566034;-9.029146
Paulo Jacinto;AL;-36.367241;-9.367918
Viçosa;AL;-36.243107;-9.367631
Chã Preta;AL;-36.298334;-9.255605
Correntes;PE;-36.324435;-9.121174
Palmeirina;PE;-36.324202;-9.010896
Santana do Mundaú;AL;-36.217608;-9.171413
Caetés;PE;-36.626828;-8.780301
Capoeiras;PE;-36.630566;-8.734225
Garanhuns;PE;-36.496567;-8.882434
Jucati;PE;-36.487137;-8.701945
São Bento do Una;PE;-36.446469;-8.526370
São João;PE;-36.365320;-8.875762
Jupi;PE;-36.412644;-8.709041
Calçado;PE;-36.336554;-8.731081
Lajedo;PE;-36.329336;-8.657914
Angelim;PE;-36.290174;-8.884289
Canhotinho;PE;-36.197935;-8.876519
Jurema;PE;-36.134739;-8.707135
Quipapá;PE;-36.013665;-8.811752
São Benedito do Sul;PE;-35.945346;-8.816603
Panelas;PE;-36.012539;-8.661211
Altinho;PE;-36.064408;-8.484820
Cupira;PE;-35.951835;-8.624324
Agrestina;PE;-35.944725;-8.459657
Maraial;PE;-35.826630;-8.790624
Lagoa dos Gatos;PE;-35.903976;-8.660205
Jaqueira;PE;-35.794232;-8.726183
Catende;PE;-35.702386;-8.675086
Belém de Maria;PE;-35.833470;-8.625038
São Joaquim do Monte;PE;-35.803498;-8.431962
Bonito;PE;-35.729249;-8.471628
Barra de Guabiraba;PE;-35.658544;-8.420751
Paripueira;AL;-35.551977;-9.463133
São Luís do Quitunde;AL;-35.560553;-9.318160
Barra de Santo Antônio;AL;-35.510083;-9.402297
Passo de Camaragibe;AL;-35.474490;-9.245110
Matriz de Camaragibe;AL;-35.524302;-9.154373
Jundiá;AL;-35.566925;-8.932968
Porto Calvo;AL;-35.398653;-9.051948
São Miguel dos Milagres;AL;-35.376253;-9.264925
Porto de Pedras;AL;-35.304914;-9.160065
Japaratinga;AL;-35.263403;-9.087464
Maragogi;AL;-35.226692;-9.007443
Xexéu;PE;-35.621222;-8.804601
Campestre;AL;-35.568470;-8.847228
Palmares;PE;-35.589032;-8.684226
Água Preta;PE;-35.526311;-8.706095
Jacuípe;AL;-35.459074;-8.839510
Joaquim Nabuco;PE;-35.528814;-8.622810
Cortês;PE;-35.546789;-8.474434
São José da Coroa Grande;PE;-35.151461;-8.889375
Barreiros;PE;-35.183178;-8.816052
Rio Formoso;PE;-35.153212;-8.659196
Gameleira;PE;-35.384573;-8.579803
Ribeirão;PE;-35.369824;-8.509568
Tamandaré;PE;-35.103285;-8.756646
Sirinhaém;PE;-35.112590;-8.587782
Ipojuca;PE;-35.060864;-8.393034
Campo Alegre do Fidalgo;PI;-41.834399;-8.382357
São Francisco de Assis do Piauí;PI;-41.681028;-8.239207
Bela Vista do Piauí;PI;-41.867522;-7.988091
Conceição do Canindé;PI;-41.594193;-7.876378
Simplício Mendes;PI;-41.907533;-7.852944
Campinas do Piauí;PI;-41.877487;-7.659299
Santo Inácio do Piauí;PI;-41.906252;-7.420720
Floresta do Piauí;PI;-41.788328;-7.466821
Isaías Coelho;PI;-41.673495;-7.735968
Vera Mendes;PI;-41.467336;-7.597481
Itainópolis;PI;-41.468713;-7.443357
Jacobina do Piauí;PI;-41.207506;-7.930634
Paulistana;PI;-41.143072;-8.134357
Acauã;PI;-41.083059;-8.219542
Patos do Piauí;PI;-41.240817;-7.672310
Caridade do Piauí;PI;-40.984763;-7.734349
Curral Novo do Piauí;PI;-40.895704;-7.831302
Massapê do Piauí;PI;-41.110323;-7.474687
Jaicós;PI;-41.137126;-7.362286
Belém do Piauí;PI;-40.968786;-7.366524
Padre Marcos;PI;-40.899665;-7.351011
Wall Ferraz;PI;-41.905037;-7.231511
Santa Cruz do Piauí;PI;-41.760932;-7.178499
Paquetá;PI;-41.700031;-7.103031
São João da Varjota;PI;-41.888912;-6.940817
Ipiranga do Piauí;PI;-41.738086;-6.824215
Dom Expedito Lopes;PI;-41.639632;-6.953319
Santana do Piauí;PI;-41.517767;-6.946961
Picos;PI;-41.467003;-7.077213
São José do Piauí;PI;-41.473110;-6.871940
Inhuma;PI;-41.704127;-6.665001
Novo Oriente do Piauí;PI;-41.926124;-6.449010
Valença do Piauí;PI;-41.737498;-6.403013
Lagoa do Sítio;PI;-41.565304;-6.507664
Geminiano;PI;-41.340879;-7.154759
Sussuapara;PI;-41.376699;-7.036868
Bocaina;PI;-41.316752;-6.941236
São Luis do Piauí;PI;-41.317454;-6.819363
Santo Antônio de Lisboa;PI;-41.225202;-6.986762
Campo Grande do Piauí;PI;-41.031486;-7.128274
Vila Nova do Piauí;PI;-40.934514;-7.132718
Francisco Santos;PI;-41.128789;-6.994914
Monsenhor Hipólito;PI;-41.026003;-6.992754
Alagoinha do Piauí;PI;-40.928170;-7.000392
São João da Canabrava;PI;-41.341483;-6.812029
Betânia do Piauí;PI;-40.798910;-8.143761
Santa Filomena;PE;-40.607854;-8.166877
Simões;PI;-40.813731;-7.591095
Marcolândia;PI;-40.660179;-7.441691
Araripina;PE;-40.494025;-7.570733
Santa Cruz;PE;-40.343368;-8.241534
Ouricuri;PE;-40.079983;-7.879184
Trindade;PE;-40.264720;-7.759000
Ipubi;PE;-40.147571;-7.645048
São Julião;PI;-40.824638;-7.083906
Caldeirão Grande do Piauí;PI;-40.636600;-7.331404
Fronteiras;PI;-40.614601;-7.081727
Salitre;CE;-40.450013;-7.283980
Pio IX;PI;-40.608301;-6.830017
Araripe;CE;-40.135894;-7.213195
Campos Sales;CE;-40.368678;-7.067611
Potengi;CE;-40.023292;-7.091540
Assaré;CE;-39.868872;-6.866897
Aiuaba;CE;-40.117820;-6.571216
Arneiroz;CE;-40.165285;-6.316499
Antonina do Norte;CE;-39.986970;-6.769193
Saboeiro;CE;-39.901730;-6.534595
Aroazes;PI;-41.782180;-6.110221
São João da Serra;PI;-41.892309;-5.510805
Novo Santo Antônio;PI;-41.932533;-5.287487
Castelo do Piauí;PI;-41.549865;-5.318693
Pimenteiras;PI;-41.411273;-6.238391
Assunção do Piauí;PI;-41.038851;-5.864996
São Miguel do Tapuio;PI;-41.316451;-5.497291
Buriti dos Montes;PI;-41.093349;-5.305840
Baixio;CE;-38.713376;-6.719447
Umari;CE;-38.700798;-6.638926
Santa Helena;PB;-38.642700;-6.717600
Triunfo;PB;-38.598579;-6.571305
Bernardino Batista;PB;-38.552069;-6.445718
São João do Rio do Peixe;PB;-38.446829;-6.721951
Poço de José de Moura;PB;-38.511054;-6.564011
Santarém;PB;-38.476393;-6.483620
Uiraúna;PB;-38.412754;-6.515044
Poço Dantas;PB;-38.490908;-6.398763
Venha-Ver;RN;-38.487378;-6.314789
Paraná;RN;-38.305701;-6.475645
Luís Gomes;RN;-38.389865;-6.405883
Major Sales;RN;-38.324019;-6.399485
José da Penha;RN;-38.282277;-6.310947
Itaporanga;PB;-38.150442;-7.302021
Igaracy;PB;-38.147761;-7.171840
Aguiar;PB;-38.168059;-7.091802
São José da Lagoa Tapada;PB;-38.162174;-6.936459
Piancó;PB;-37.928880;-7.192823
Coremas;PB;-37.934638;-7.007124
Cajazeirinhas;PB;-37.800929;-6.960164
Ererê;CE;-38.346113;-6.027508
Iracema;CE;-38.291943;-5.812400
Jaguaretama;CE;-38.763861;-5.605096
Jaguaribara;CE;-38.535878;-5.677651
Alto Santo;CE;-38.274318;-5.508941
Rafael Fernandes;RN;-38.221116;-6.189871
Marcelino Vieira;RN;-38.164162;-6.284595
Pau dos Ferros;RN;-38.207714;-6.104976
Pilões;RN;-38.046050;-6.263635
Francisco Dantas;RN;-38.121209;-6.072339
São Francisco do Oeste;RN;-38.151944;-5.974722
São João do Sabugi;RN;-37.202676;-6.713867
Várzea;PB;-36.991287;-6.761887
Caicó;RN;-37.106723;-6.454415
São Fernando;RN;-37.186362;-6.379745
Ouro Branco;RN;-36.942790;-6.695800
São José do Sabugi;PB;-36.797204;-6.762945
Santana do Seridó;RN;-36.731201;-6.766430
Jardim do Seridó;RN;-36.773574;-6.580474
São José do Seridó;RN;-36.874644;-6.440020
Cruzeta;RN;-36.778239;-6.408939
Pesqueira;PE;-36.697783;-8.357974
Abaíra;BA;-41.661869;-13.248830
Dias d'Ávila;BA;-38.292645;-12.618750
Seridó;PB;-36.412223;-6.854255
Jundiá;RN;-35.349469;-6.268657
Pau D'Arco do Piauí;PI;-42.390809;-5.260721
Aroeiras do Itaim;PI;-41.564868;-7.276906
Jequiá da Praia;AL;-36.014230;-10.013268
Luís Eduardo Magalhães;BA;-45.786555;-12.095638
Barrocas;BA;-39.077594;-11.527222
Governador Lindenberg;ES;-40.460954;-19.251820
Mesquita;RJ;-43.460066;-22.802811
Santa Rita do Trivelato;MT;-55.270564;-13.814599
Tio Hugo;RS;-52.595507;-28.571229
Rolador;RS;-54.818640;-28.256554
Mato Queimado;RS;-54.615862;-28.251988
Capão do Cipó;RS;-54.555782;-28.931243
Aceguá;RS;-54.161467;-31.866483
Sousa;PB;-38.231059;-6.751484
Aparecida;PB;-38.080259;-6.784657
São Francisco;PB;-38.096799;-6.607728
Vieirópolis;PB;-38.256666;-6.506845
Lastro;PB;-38.174199;-6.506032
Tenente Ananias;RN;-38.181981;-6.458229
Santa Cruz;PB;-38.061748;-6.523700
Alexandria;RN;-38.014230;-6.405335
São Domingos;PB;-37.928347;-6.812336
Lagoa;PB;-37.912745;-6.585723
Pombal;PB;-37.800270;-6.766059
Bom Sucesso;PB;-37.923409;-6.441761
Jericó;PB;-37.803641;-6.545769
Brejo dos Santos;PB;-37.825311;-6.370652
Acopiara;CE;-39.448042;-6.089114
Piquet Carneiro;CE;-39.417017;-5.800251
Mombaça;CE;-39.630009;-5.738442
Pedra Branca;CE;-39.707771;-5.453411
Senador Pompeu;CE;-39.370369;-5.582444
Quixelô;CE;-39.201067;-6.246369
Deputado Irapuan Pinheiro;CE;-39.257010;-5.914848
Orós;CE;-38.905264;-6.251816
Milhã;CE;-39.187483;-5.672525
Solonópole;CE;-39.010720;-5.718936
Banabuiú;CE;-38.913175;-5.304544
Boa Viagem;CE;-39.733652;-5.112583
Madalena;CE;-39.572542;-4.846012
Itatira;CE;-39.620155;-4.526076
Caridade;CE;-39.191166;-4.225141
Itapiúna;CE;-38.928113;-4.555165
Aratuba;CE;-39.047117;-4.412291
Mulungu;CE;-38.995133;-4.302938
Guaramiranga;CE;-38.932044;-4.262477
Capistrano;CE;-38.904842;-4.455688
Baturité;CE;-38.881206;-4.325983
Aracoiaba;CE;-38.812512;-4.368720
Pacoti;CE;-38.922011;-4.224918
Jaguaribe;CE;-38.622653;-5.902134
São Miguel;RN;-38.494696;-6.202833
Coronel João Pessoa;RN;-38.444150;-6.249736
Pereiro;CE;-38.462397;-6.035761
Riacho de Santana;RN;-38.311576;-6.251392
Água Nova;RN;-38.294140;-6.203514
Doutor Severiano;RN;-38.379407;-6.080819
Encanto;RN;-38.303280;-6.106912
Juazeiro do Piauí;PI;-41.697561;-5.174589
Jatobá do Piauí;PI;-41.816965;-4.770254
Sigefredo Pacheco;PI;-41.731123;-4.916647
Capitão de Campos;PI;-41.943999;-4.457000
Piripiri;PI;-41.771569;-4.271574
Lagoa de São Francisco;PI;-41.596883;-4.385053
Pedro II;PI;-41.448184;-4.425845
Poranga;CE;-40.920533;-4.746717
Milton Brandão;PI;-41.417343;-4.682946
Domingos Mourão;PI;-41.268267;-4.249498
Croatá;CE;-40.902228;-4.404805
Parambu;CE;-40.690524;-6.207681
Quiterianópolis;CE;-40.700217;-5.842496
Novo Oriente;CE;-40.771296;-5.525519
Tauá;CE;-40.296802;-5.985853
Catarina;CE;-39.873644;-6.122911
Independência;CE;-40.308526;-5.387889
Crateús;CE;-40.653635;-5.167683
Ararendá;CE;-40.830990;-4.745667
Ipaporanga;CE;-40.753705;-4.897636
Ipueiras;CE;-40.711764;-4.538021
Ipu;CE;-40.705877;-4.317484
Pires Ferreira;CE;-40.644180;-4.239218
Nova Russas;CE;-40.562118;-4.705807
Catunda;CE;-40.199988;-4.643360
Santa Quitéria;CE;-40.152305;-4.326081
Terra Nova;PE;-39.382482;-8.222436
Parnamirim;PE;-39.579547;-8.087292
Granito;PE;-39.615034;-7.707105
Exu;PE;-39.723789;-7.503640
Moreilândia;PE;-39.545999;-7.619314
Serrita;PE;-39.295062;-7.940406
Salgueiro;PE;-39.124690;-8.073734
Verdejante;PE;-38.970083;-7.922353
Tamboril;CE;-40.319593;-4.831364
Monsenhor Tabosa;CE;-40.064637;-4.791022
Carnaubeira da Penha;PE;-38.751155;-8.317994
Mirandiba;PE;-38.738841;-8.121131
Serra Talhada;PE;-38.289034;-7.981778
São José do Belmonte;PE;-38.757689;-7.857235
Santa Inês;PB;-38.553995;-7.620999
Mauriti;CE;-38.770786;-7.385974
Conceição;PB;-38.501437;-7.551063
Ibiara;PB;-38.405918;-7.479567
Santana de Mangueira;PB;-38.323633;-7.547048
Quixeramobim;CE;-39.288947;-5.190672
Choró;CE;-39.134397;-4.839062
Quixadá;CE;-39.015474;-4.966301
Canindé;CE;-39.315505;-4.351618
Tianguá;CE;-40.992286;-3.729645
Viçosa do Ceará;CE;-41.091554;-3.566703
Araioses;MA;-41.904993;-2.890914
Cedro;PE;-39.236733;-7.711790
Penaforte;CE;-39.070724;-7.821630
Jardim;CE;-39.282620;-7.575989
Porteiras;CE;-39.114028;-7.522647
Jati;CE;-39.002871;-7.679700
Brejo Santo;CE;-38.979911;-7.484686
Abaiara;CE;-39.041590;-7.345879
Santana do Cariri;CE;-39.730161;-7.176125
Nova Olinda;CE;-39.671280;-7.084145
Altaneira;CE;-39.735600;-6.998371
Aurora;CE;-38.974221;-6.933487
Várzea Alegre;CE;-39.294155;-6.782642
Cedro;CE;-39.060869;-6.600342
Iguatu;CE;-39.289150;-6.362806
Lavras da Mangabeira;CE;-38.970619;-6.744801
Icó;CE;-38.855352;-6.396271
Betânia;PE;-38.034540;-8.267867
Calumbi;PE;-38.148244;-7.935515
Santa Cruz da Baixa Verde;PE;-38.147560;-7.813390
Manaíra;PB;-38.152292;-7.703313
Triunfo;PE;-38.097776;-7.832719
São José de Princesa;PB;-38.089432;-7.736329
Curral Velho;PB;-38.196175;-7.530751
Diamante;PB;-38.261510;-7.417380
Boa Ventura;PB;-38.211347;-7.409822
Nova Olinda;PB;-38.038229;-7.472325
Pedra Branca;PB;-38.068882;-7.421694
Flores;PE;-37.971511;-7.858420
Princesa Isabel;PB;-37.988647;-7.731747
Carnaíba;PE;-37.794584;-7.793421
Quixaba;PE;-37.844580;-7.707339
Tavares;PB;-37.871208;-7.626971
Santana dos Garrotes;PB;-37.981885;-7.381615
Juru;PB;-37.815021;-7.529826
Barro;CE;-38.774133;-7.171879
Monte Horebe;PB;-38.583762;-7.204024
Cachoeira dos Índios;PB;-38.675980;-6.913531
Bom Jesus;PB;-38.645344;-6.816010
Cajazeiras;PB;-38.557722;-6.880044
Bonito de Santa Fé;PB;-38.513335;-7.313407
São José de Piranhas;PB;-38.501980;-7.118704
Serra Grande;PB;-38.364696;-7.209574
São José de Caiana;PB;-38.298928;-7.246359
Carrapateira;PB;-38.339885;-7.034141
Marizópolis;PB;-38.352830;-6.827479
Nazarezinho;PB;-38.322032;-6.911401
Ipaumirim;CE;-38.717856;-6.782648
Timbaúba dos Batistas;RN;-37.274519;-6.457678
Jardim de Piranhas;RN;-37.349643;-6.376649
Cacimba de Areia;PB;-37.156288;-7.121280
Desterro;PB;-37.092525;-7.286996
Cacimbas;PB;-37.060429;-7.207212
Passagem;PB;-37.043302;-7.134667
Quixabá;PB;-37.145833;-7.022398
São Mamede;PB;-37.095436;-6.923862
Afogados da Ingazeira;PE;-37.630991;-7.743121
Iguaraci;PE;-37.508199;-7.832221
Solidão;PE;-37.644494;-7.594717
Água Branca;PB;-37.635703;-7.511441
Tabira;PE;-37.537735;-7.583661
Imaculada;PB;-37.507918;-7.388904
Ingazeira;PE;-37.457575;-7.669089
Santa Terezinha;PE;-37.478750;-7.376960
Tuparetama;PE;-37.316530;-7.600300
São José do Egito;PE;-37.273974;-7.469447
Brejinho;PE;-37.286493;-7.346940
São Sebastião do Umbuzeiro;PB;-37.013841;-8.152887
Zabelê;PB;-37.105676;-8.079012
Monteiro;PB;-37.118419;-7.883626
Poção;PE;-36.711137;-8.187258
São João do Tigre;PB;-36.854720;-8.077026
Camalaú;PB;-36.824191;-7.885031
Areia de Baraúnas;PB;-36.940363;-7.117021
Salgadinho;PB;-36.845776;-7.100984
Taperoá;PB;-36.824499;-7.206294
Santa Luzia;PB;-36.917792;-6.860922
Assunção;PB;-36.725040;-7.072310
Junco do Seridó;PB;-36.716595;-6.992694
Equador;RN;-36.716957;-6.938355
Ipueira;RN;-37.204541;-6.805962
Taboleiro Grande;RN;-38.036693;-5.919480
Rodolfo Fernandes;RN;-38.057886;-5.783929
Antônio Martins;RN;-37.883363;-6.213666
Serrinha dos Pintos;RN;-37.954799;-6.110873
Martins;RN;-37.907973;-6.082790
João Dias;RN;-37.788546;-6.272154
Frutuoso Gomes;RN;-37.837493;-6.156686
Lucrécia;RN;-37.813365;-6.105248
Almino Afonso;RN;-37.763574;-6.147496
Portalegre;RN;-37.986524;-6.020640
Viçosa;RN;-37.946236;-5.982529
Riacho da Cruz;RN;-37.949047;-5.926543
Itaú;RN;-37.991249;-5.836301
Severiano Melo;RN;-37.956980;-5.776659
Umarizal;RN;-37.818031;-5.982379
Potiretama;CE;-38.157804;-5.712870
São João do Jaguaribe;CE;-38.269366;-5.275164
Tabuleiro do Norte;CE;-38.128175;-5.243527
Apodi;RN;-37.794593;-5.653489
Ibaretama;CE;-38.750085;-4.803764
Ibicuitinga;CE;-38.636249;-4.969993
Morada Nova;CE;-38.370158;-5.097360
Ocara;CE;-38.593256;-4.485230
Redenção;CE;-38.727719;-4.215873
Acarape;CE;-38.705532;-4.220830
Barreira;CE;-38.642910;-4.289206
Chorozinho;CE;-38.498572;-4.288730
Limoeiro do Norte;CE;-38.084693;-5.143921
Quixeré;CE;-37.980200;-5.071482
Russas;CE;-37.972086;-4.926727
Palhano;CE;-37.965549;-4.736722
Jaguaruana;CE;-37.780997;-4.831508
Itaiçaba;CE;-37.832957;-4.671461
Aracati;CE;-37.767894;-4.558259
Fortim;CE;-37.798056;-4.451256
Brasileira;PI;-41.785855;-4.133696
Piracuruca;PI;-41.708779;-3.933350
São José do Divino;PI;-41.830793;-3.814114
Caxingó;PI;-41.895476;-3.419044
Caraúbas do Piauí;PI;-41.842517;-3.475249
Buriti dos Lopes;PI;-41.869481;-3.182592
Cocal;PI;-41.554623;-3.472792
Cocal dos Alves;PI;-41.440197;-3.620471
Bom Princípio do Piauí;PI;-41.640333;-3.196314
São João da Fronteira;PI;-41.256921;-3.954975
Carnaubal;CE;-40.941304;-4.159846
Ubajara;CE;-40.920437;-3.854479
Parnaíba;PI;-41.775388;-2.905847
Ilha Grande;PI;-41.818580;-2.857737
Luís Correia;PI;-41.664142;-2.884380
Cajueiro da Praia;PI;-41.340801;-2.931114
Chaval;CE;-41.243460;-3.035708
Barroquinha;CE;-41.135850;-3.020514
São Benedito;CE;-40.859572;-4.047131
Guaraciaba do Norte;CE;-40.747588;-4.158136
Massapê;CE;-40.342271;-3.523645
Santana do Acaraú;CE;-40.211827;-3.461435
Morrinhos;CE;-40.123275;-3.234258
Miraíma;CE;-39.966267;-3.568674
Granja;CE;-40.837205;-3.127879
Camocim;CE;-40.854362;-2.900499
Jijoca de Jericoacoara;CE;-40.512742;-2.793312
Marco;CE;-40.158240;-3.128501
Bela Cruz;CE;-40.167127;-3.049964
Cruz;CE;-40.175981;-2.918128
Acaraú;CE;-40.118306;-2.887689
Itarema;CE;-39.916654;-2.924797
Irauçuba;CE;-39.784332;-3.747367
Itapagé;CE;-39.585531;-3.683137
Tejuçuoca;CE;-39.579866;-3.988307
General Sampaio;CE;-39.453958;-4.043507
Apuiarés;CE;-39.435930;-3.945057
Umirim;CE;-39.346513;-3.676541
Amontada;CE;-39.828816;-3.360166
Uruburetama;CE;-39.510677;-3.623165
Itapipoca;CE;-39.583571;-3.499329
Tururu;CE;-39.429656;-3.584132
Paramoti;CE;-39.241691;-4.088146
Pentecoste;CE;-39.269159;-3.792736
Palmácia;CE;-38.844601;-4.138308
São Luís do Curu;CE;-39.239123;-3.669760
Paraipaba;CE;-39.147868;-3.437987
Condado;PE;-35.099940;-7.587868
Itambé;PE;-35.096266;-7.414027
Pedras de Fogo;PB;-35.106464;-7.391069
Goiana;PE;-34.995910;-7.560603
Caaporã;PB;-34.905514;-7.513508
Alhandra;PB;-34.905662;-7.429770
Ilha de Itamaracá;PE;-34.830319;-7.747664
Pitimbu;PB;-34.815133;-7.466398
Ingá;PB;-35.605000;-7.281444
Juarez Távora;PB;-35.568623;-7.171302
Mogeiro;PB;-35.483182;-7.285169
Gurinhém;PB;-35.422198;-7.123300
Ouro Velho;PB;-37.151857;-7.616037
Prata;PB;-37.080088;-7.688261
Itapetim;PE;-37.186266;-7.371777
Amparo;PB;-37.062838;-7.555022
Sumé;PB;-36.883972;-7.662060
Livramento;PB;-36.949116;-7.371127
Surubim;PE;-35.748102;-7.847456
Casinhas;PE;-35.720635;-7.740839
Umbuzeiro;PB;-35.658233;-7.691993
Gado Bravo;PB;-35.789887;-7.582786
Queimadas;PB;-35.903136;-7.350288
Fagundes;PB;-35.793128;-7.344537
Aroeiras;PB;-35.706549;-7.544731
Parari;PB;-36.652248;-7.309751
Santo André;PB;-36.621339;-7.220157
Gurjão;PB;-36.492288;-7.248332
Juazeirinho;PB;-36.579316;-7.060924
Tenório;PB;-36.627336;-6.938546
Boa Vista;PB;-36.235706;-7.263653
Soledade;PB;-36.366791;-7.058292
Cubati;PB;-36.361887;-6.866858
Olivedos;PB;-36.241033;-6.984343
Parelhas;RN;-36.656626;-6.684908
Pedra Lavrada;PB;-36.475813;-6.749974
Acari;RN;-36.634719;-6.428201
Carnaúba dos Dantas;RN;-36.586818;-6.550153
Frei Martinho;PB;-36.452628;-6.397590
Nova Palmeira;PB;-36.422023;-6.671221
Sossêgo;PB;-36.253820;-6.770670
Baraúna;PB;-36.260065;-6.634845
Picuí;PB;-36.349704;-6.508451
Nova Floresta;PB;-36.205722;-6.450562
Jaçanã;RN;-36.203081;-6.418559
Coronel Ezequiel;RN;-36.222346;-6.374801
Puxinanã;PB;-35.954301;-7.154793
Pocinhos;PB;-36.066779;-7.066580
Algodão de Jandaíra;PB;-36.012861;-6.892919
Campina Grande;PB;-35.873144;-7.221958
Lagoa Seca;PB;-35.849111;-7.155349
Montadas;PB;-35.959198;-7.088482
Massaranduba;PB;-35.784768;-7.189950
Riachão do Bacamarte;PB;-35.669257;-7.253472
São José dos Cordeiros;PB;-36.808494;-7.387749
Olho d'Água;PB;-37.740569;-7.221175
Emas;PB;-37.716276;-7.099635
Catingueira;PB;-37.606380;-7.120081
São Bentinho;PB;-37.724345;-6.885961
Condado;PB;-37.606036;-6.898312
Malta;PB;-37.522067;-6.897190
Mãe d'Água;PB;-37.432211;-7.252009
Santa Teresinha;PB;-37.443533;-7.079637
Maturéia;PB;-37.350992;-7.261879
Teixeira;PB;-37.252477;-7.221036
São José do Bonfim;PB;-37.303625;-7.160695
Patos;PB;-37.274702;-7.017427
São José de Espinharas;PB;-37.321433;-6.839736
Paulista;PB;-37.618548;-6.591381
Vista Serrana;PB;-37.570358;-6.730298
Mato Grosso;PB;-37.727857;-6.540181
Riacho dos Cavalos;PB;-37.648339;-6.440670
Catolé do Rocha;PB;-37.746967;-6.340625
Brejo do Cruz;PB;-37.494340;-6.341853
Serra Negra do Norte;RN;-37.399601;-6.660306
São Bento;PB;-37.448835;-6.485286
Sanharó;PE;-36.569598;-8.360969
Jataúba;PE;-36.494292;-7.976684
Belo Jardim;PE;-36.425822;-8.331300
Brejo da Madre de Deus;PE;-36.374142;-8.149333
Tacaimbó;PE;-36.300035;-8.308673
Santa Cruz do Capibaribe;PE;-36.206092;-7.948019
Congo;PB;-36.658059;-7.790782
Coxixola;PB;-36.606441;-7.623653
Caraúbas;PB;-36.492046;-7.720487
Serra Branca;PB;-36.666003;-7.480344
São João do Cariri;PB;-36.534457;-7.381683
Barra de São Miguel;PB;-36.320915;-7.746031
São Domingos do Cariri;PB;-36.437398;-7.632733
Cabaceiras;PB;-36.286981;-7.488991
São Caitano;PE;-36.144077;-8.329239
Caruaru;PE;-35.969863;-8.284547
Toritama;PE;-36.063659;-8.009546
Taquaritinga do Norte;PE;-36.042265;-7.894457
Vertentes;PE;-35.968143;-7.901581
Riacho das Almas;PE;-35.864796;-8.137417
Bezerros;PE;-35.795995;-8.232796
Camocim de São Félix;PE;-35.765332;-8.358649
Sairé;PE;-35.696737;-8.328638
Frei Miguelinho;PE;-35.911283;-7.939179
Matinhas;PB;-35.766922;-7.124856
Serra Redonda;PB;-35.684195;-7.186216
Areial;PB;-35.931324;-7.047894
São Sebastião de Lagoa de Roça;PB;-35.867807;-7.110340
Esperança;PB;-35.859719;-7.022783
Alagoa Nova;PB;-35.759065;-7.053772
Areia;PB;-35.697731;-6.963965
Arara;PB;-35.755210;-6.828126
Barra de Santa Rosa;PB;-36.067069;-6.718164
Remígio;PB;-35.801124;-6.949918
Damião;PB;-35.910136;-6.631607
Cuité;PB;-36.151456;-6.476466
São Bento do Trairí;RN;-36.086332;-6.337979
Japi;RN;-35.934551;-6.465440
Casserengue;PB;-35.817875;-6.779538
Cacimba de Dentro;PB;-35.777835;-6.638596
Monte das Gameleiras;RN;-35.783133;-6.436980
Araruna;PB;-35.749800;-6.548484
Serra de São Bento;RN;-35.703258;-6.417616
São José do Campestre;RN;-35.706681;-6.310871
Rafael Godeiro;RN;-37.715961;-6.072441
Patu;RN;-37.635638;-6.106559
Belém do Brejo do Cruz;PB;-37.534763;-6.185146
Messias Targino;RN;-37.515817;-6.071935
Olho-d'Água do Borges;RN;-37.704696;-5.948597
Caraúbas;RN;-37.558629;-5.783867
São José do Brejo do Cruz;PB;-37.360063;-6.210543
Janduís;RN;-37.404820;-6.014739
Augusto Severo;RN;-37.310572;-5.853600
Felipe Guerra;RN;-37.687518;-5.592737
Governador Dix-Sept Rosado;RN;-37.518265;-5.448867
Upanema;RN;-37.263485;-5.637611
Jucurutu;RN;-37.009020;-6.030599
Triunfo Potiguar;RN;-37.178606;-5.854080
Paraú;RN;-37.103187;-5.768931
Florânia;RN;-36.822624;-6.122645
Tenente Laurentino Cruz;RN;-36.713515;-6.137797
São Rafael;RN;-36.877770;-5.797907
Itajá;RN;-36.871176;-5.638936
Açu;RN;-36.907538;-5.565024
Ipanguaçu;RN;-36.850137;-5.489844
Carnaubais;RN;-36.833489;-5.341808
Alto do Rodrigues;RN;-36.749971;-5.281857
Pendências;RN;-36.709550;-5.256400
Baraúna;RN;-37.612911;-5.069770
Mossoró;RN;-37.347446;-5.183737
Tibau;RN;-37.255358;-4.837292
Icapuí;CE;-37.353062;-4.712063
Serra do Mel;RN;-37.024219;-5.177245
Grossos;RN;-37.162080;-4.980680
Areia Branca;RN;-37.125226;-4.952537
Porto do Mangue;RN;-36.788710;-5.054413
São Vicente;RN;-36.682696;-6.218931
Currais Novos;RN;-36.514648;-6.254843
Lagoa Nova;RN;-36.470270;-6.093386
Santana do Matos;RN;-36.657763;-5.946055
Cerro Corá;RN;-36.350278;-6.035030
Campo Redondo;RN;-36.188768;-6.238290
Bodó;RN;-36.416706;-5.980269
Angicos;RN;-36.609434;-5.657920
Fernando Pedroza;RN;-36.528193;-5.690955
Afonso Bezerra;RN;-36.507469;-5.492294
Pedro Avelino;RN;-36.386653;-5.516099
Lajes;RN;-36.247040;-5.693222
Lajes Pintadas;RN;-36.117088;-6.149429
Santa Cruz;RN;-36.019297;-6.224752
São Tomé;RN;-36.079798;-5.964037
Barcelona;RN;-35.924653;-5.942840
Ruy Barbosa;RN;-35.933039;-5.887454
Tangará;RN;-35.798888;-6.196490
Sítio Novo;RN;-35.908974;-6.111322
Presidente Juscelino;RN;-35.716327;-6.105987
Senador Elói de Souza;RN;-35.697813;-6.033343
Lagoa de Velhos;RN;-35.872928;-6.011897
Riachuelo;RN;-35.821515;-5.821555
São Paulo do Potengi;RN;-35.764229;-5.899401
Santa Maria;RN;-35.691409;-5.838022
Limoeiro;PE;-35.440154;-7.872599
Primavera;PE;-35.354378;-8.329987
Vitória de Santo Antão;PE;-35.297620;-8.128191
Escada;PE;-35.224082;-8.356717
Glória do Goitá;PE;-35.290440;-8.005675
Feira Nova;PE;-35.380130;-7.947042
Lagoa do Itaenga;PE;-35.287387;-7.930053
Chã de Alegria;PE;-35.204002;-8.006795
Paudalho;PE;-35.171639;-7.902870
Orobó;PE;-35.595568;-7.745535
Bom Jardim;PE;-35.578391;-7.796947
Natuba;PB;-35.558629;-7.635142
Machados;PE;-35.511376;-7.688274
Itatuba;PB;-35.637994;-7.381153
São Vicente Ferrer;PE;-35.480844;-7.589691
Macaparana;PE;-35.442460;-7.555638
Salgado de São Félix;PB;-35.430480;-7.353375
Lagoa do Carro;PE;-35.310783;-7.843834
Buenos Aires;PE;-35.318197;-7.724488
Vicência;PE;-35.313950;-7.656549
Carpina;PE;-35.251397;-7.845660
Tracunhaém;PE;-35.231421;-7.802284
Nazaré da Mata;PE;-35.219297;-7.741491
Aliança;PE;-35.222696;-7.603980
Timbaúba;PE;-35.311884;-7.504835
Camutanga;PE;-35.266360;-7.405449
Ferreiros;PE;-35.237252;-7.446664
Juripiranga;PB;-35.232143;-7.361759
Cabo de Santo Agostinho;PE;-35.025293;-8.282180
Moreno;PE;-35.083540;-8.108706
São Lourenço da Mata;PE;-35.012359;-8.006840
Camaragibe;PE;-34.978152;-8.023505
Paulista;PE;-34.868407;-7.934007
Abreu e Lima;PE;-34.898389;-7.900719
Olinda;PE;-34.854504;-8.010166
Araçoiaba;PE;-35.080897;-7.783911
Itaquitinga;PE;-35.100231;-7.663729
Igarassu;PE;-34.901306;-7.828810
Itapissuma;PE;-34.897136;-7.767983
Alagoa Grande;PB;-35.620609;-7.039432
Pilões;PB;-35.612974;-6.868269
Alagoinha;PB;-35.533196;-6.946569
Cuitegi;PB;-35.521529;-6.890577
Pilõezinhos;PB;-35.531036;-6.842773
Mulungu;PB;-35.460023;-7.025245
Guarabira;PB;-35.484986;-6.850636
Itabaiana;PB;-35.331723;-7.331674
São José dos Ramos;PB;-35.372536;-7.252384
Caldas Brandão;PB;-35.327159;-7.102500
Pilar;PB;-35.252326;-7.264034
São Miguel de Taipu;PB;-35.201630;-7.247643
Riachão do Poço;PB;-35.291431;-7.141726
Sobrado;PB;-35.235740;-7.144288
Sapé;PB;-35.227975;-7.093591
Mari;PB;-35.318044;-7.059424
Araçagi;PB;-35.373667;-6.843739
Cuité de Mamanguape;PB;-35.250219;-6.912922
Itapororoca;PB;-35.240635;-6.823738
Capim;PB;-35.167250;-6.916244
Serraria;PB;-35.628233;-6.815693
Borborema;PB;-35.618728;-6.801995
Solânea;PB;-35.663568;-6.751606
Bananeiras;PB;-35.624573;-6.747749
Dona Inês;PB;-35.620479;-6.615656
Pirpirituba;PB;-35.490558;-6.779222
Belém;PB;-35.516580;-6.742614
Sertãozinho;PB;-35.437159;-6.751273
Serra da Raiz;PB;-35.437868;-6.685274
Caiçara;PB;-35.458127;-6.621151
Duas Estradas;PB;-35.417976;-6.684988
Logradouro;PB;-35.438351;-6.611912
Riachão;PB;-35.660966;-6.542688
Passa e Fica;RN;-35.644179;-6.430183
Lagoa d'Anta;RN;-35.594904;-6.394926
Nova Cruz;RN;-35.428615;-6.475115
Santo Antônio;RN;-35.473868;-6.311951
Curral de Cima;PB;-35.263934;-6.723248
Lagoa de Dentro;PB;-35.370641;-6.672130
Pedro Régis;PB;-35.296567;-6.633226
Jacaraú;PB;-35.289032;-6.614526
Montanhas;RN;-35.284158;-6.485217
Várzea;RN;-35.373182;-6.346412
Espírito Santo;RN;-35.305217;-6.335634
Pedro Velho;RN;-35.219539;-6.435600
Canguaretama;RN;-35.128059;-6.371927
Cruz do Espírito Santo;PB;-35.085721;-7.139022
Conde;PB;-34.899876;-7.257464
Santa Rita;PB;-34.975295;-7.117242
Bayeux;PB;-34.929336;-7.123797
Touros;RN;-35.462102;-5.201821
Balbinos;SP;-49.361906;-21.896293
Jaboatão dos Guararapes;PE;-35.014959;-8.112982
São Francisco de Itabapoana;RJ;-41.109135;-21.470187
Córrego Fundo;MG;-45.561742;-20.447422
Fervedouro;MG;-42.278997;-20.726000
Barra do Mendes;BA;-42.058999;-11.810000
Nova Ipixuna;PA;-49.082194;-4.916221
Jardim do Mulato;PI;-42.629999;-6.099000
Santa Margarida do Sul;RS;-54.081719;-30.339283
Boa Vista do Cadeado;RS;-53.810821;-28.579055
Bozano;RS;-53.771965;-28.365906
Pedras Altas;RS;-53.581437;-31.736539
Boa Vista do Incra;RS;-53.391034;-28.818456
São Pedro das Missões;RS;-53.251298;-27.770568
Jacuizinho;RS;-53.065694;-29.040118
Novo Xingu;RS;-53.063941;-27.749049
Lagoa Bonita do Sul;RS;-53.016956;-29.493874
Almirante Tamandaré do Sul;RS;-52.914209;-28.114942
Cruzaltense;RS;-52.652168;-27.667161
Arroio do Padre;RS;-52.424598;-31.438942
Quatro Irmãos;RS;-52.442417;-27.825684
Paulo Bento;RS;-52.416919;-27.705138
Canudos do Vale;RS;-52.237436;-29.327124
Forquetinha;RS;-52.098068;-29.382756
Coqueiro Baixo;RS;-52.094158;-29.180163
Santa Cecília do Sul;RS;-51.927934;-28.160861
Westfalia;RS;-51.764455;-29.426275
Coronel Pilar;RS;-51.684731;-29.269476
São José do Sul;RS;-51.482104;-29.544818
Capão Bonito do Sul;RS;-51.396093;-28.125440
Pinhal da Serra;RS;-51.167329;-27.875132
Itanhangá;MT;-56.684065;-12.166012
Itati;RS;-50.101571;-29.497386
Figueirão;MS;-53.641258;-18.682824
Rondolândia;MT;-61.469737;-10.837556
Conquista D'Oeste;MT;-59.547973;-14.537072
Colniza;MT;-59.225189;-9.461207
Vale de São Domingos;MT;-59.068313;-15.285975
Curvelândia;MT;-57.913269;-15.608389
Ipiranga do Norte;MT;-56.145936;-12.240162
Nova Santa Helena;MT;-55.187200;-10.865144
Santo Antônio do Leste;MT;-53.638935;-14.797045
Santa Cruz do Xingu;MT;-52.395348;-10.153161
Nova Nazaré;MT;-51.800203;-13.948627
Bom Jesus do Araguaia;MT;-51.503154;-12.170571
Serra Nova Dourada;MT;-51.402543;-12.089627
Novo Santo Antônio;MT;-50.968570;-12.287538
Lagoa Santa;GO;-51.399333;-19.185113
Campo Limpo de Goiás;GO;-49.079073;-16.302822
Ipiranga de Goiás;GO;-49.671844;-15.170417
Gameleira de Goiás;GO;-48.644369;-16.483064
Nazária;PI;-42.811532;-5.352268
Boa Vista;RR;-60.675328;2.823842
Macapá;AP;-51.069395;0.034934
Rio Branco;AC;-67.824348;-9.974990
Porto Alegre;RS;-51.206533;-30.031771
Campo Grande;MS;-54.629463;-20.448589
Porto Velho;RO;-63.899902;-8.760772
Manaus;AM;-60.021230;-3.118662
Cuiabá;MT;-56.097397;-15.600979
Florianópolis;SC;-48.547696;-27.594486
Curitiba;PR;-49.264622;-25.419547
São Paulo;SP;-46.639520;-23.532905
Rio de Janeiro;RJ;-43.200295;-22.912897
Belo Horizonte;MG;-43.926572;-19.910183
Vitória;ES;-40.312806;-20.315472
Goiânia;GO;-49.264346;-16.686439
Palmas;TO;-48.355751;-10.239973
Belém;PA;-48.489756;-1.455396
Teresina;PI;-42.803364;-5.091944
São Luís;MA;-44.282513;-2.538742
Salvador;BA;-38.501068;-12.971780
Aracaju;SE;-37.067660;-10.909133
Maceió;AL;-35.734960;-9.665985
Fortaleza;CE;-38.542298;-3.716638
Recife;PE;-34.877065;-8.046658
João Pessoa;PB;-34.864121;-7.115090
Natal;RN;-35.198604;-5.793567
Brasília;DF;-47.929657;-15.779522
Taguatinga;DF;-48.0323;-15.5
Ceilândia;DF;-48.10389;-15.81278
EOT;
        return $csv;
    }
}
