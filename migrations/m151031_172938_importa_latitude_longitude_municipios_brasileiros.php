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
            if ('' === trim($row)) {
                continue;
            }
            $row = explode(';', $row);
            if (!count($row)) {
                continue;
            }
            list($id, $uf, $nome, $longitude, $latitude) = $row;

            $uf = str_replace('"', '', $uf);
            $nome = str_replace('"', '', $nome);
            $slug = Inflector::slug($nome . ' ' . $uf);

            $coordenadas = $this->arrayToWkt('Point', [$longitude, $latitude]);

            if (!$this->getMunicipio($id)) {
                echo "Inserindo {$nome} - {$uf}\n";
                $this->insert(
                    'municipios',
                    [
                        'id' => $id,
                        'nome' => $nome,
                        'sigla_estado' => $uf,
                        'coordenadas_area' => new Expression($coordenadas),
                        'slug' => $slug,
                    ]
                );
                continue;
            }

            echo "Atualizando {$nome} - {$uf}\n";
            $this->update(
                'municipios',
                [
                    'coordenadas_area' => new Expression($coordenadas),
                    'nome' => $nome,
                    'slug' => $slug,
                ],
                'id = ' . $id
            );
        }
    }

    public function safeDown()
    {
        echo "m151031_172938_importa_latitude_longitude_municipios_brasileiros cannot be reverted.\n";
        return false;
    }

    protected function getMunicipio($id)
    {
        $command = $this->db->createCommand(
            "SELECT * FROM municipios WHERE id = :id",
            [':id' => $id]
        );
        return $command->query()->read();
    }

    protected function getCsv()
    {
        return file_get_contents('https://raw.githubusercontent.com/alanwillms/geoinfo/master/latitude-longitude-cidades.csv');
    }
}
