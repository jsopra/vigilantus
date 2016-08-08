<?php

use perspectivain\postgis\PostgisTrait;
use yii\db\Expression;
use yii\db\Migration;
use yii\helpers\Inflector;

class m151121_125024_importa_bairros_municipios extends Migration
{
    use PostgisTrait;

    public function safeUp()
    {
        // Exclui bairros de teste
        $this->delete('boletim_rg_fechamento', 'boletim_rg_id IN (
            SELECT id FROM boletins_rg
            WHERE bairro_quarteirao_id IN (SELECT id FROM bairro_quarteiroes WHERE bairro_id IN (49, 48, 50))
        )');
        $this->delete('boletins_rg', 'bairro_quarteirao_id IN (SELECT id FROM bairro_quarteiroes WHERE bairro_id IN (49, 48, 50))');
        $this->delete('focos_transmissores', 'bairro_quarteirao_id IN (SELECT id FROM bairro_quarteiroes WHERE bairro_id IN (49, 48, 50))');
        $this->delete('bairro_quarteiroes', 'bairro_id IN (49, 48, 50)');
        $this->delete('bairros', 'id IN (49, 48, 50)');

        $rows = explode("\n", $this->getCsv());
        array_shift($rows); // tira cabeçalho

        foreach ($rows as $row) {
            if ('' === trim($row)) {
                continue;
            }
            $row = explode(';', $row);
            if (!count($row)) {
                continue;
            }
            list($id_municipio, $id_bairro, $uf, $cidade, $bairro, $longitude, $latitude) = $row;

            $cidade = str_replace("'", '`', $cidade);
            $cidade = str_replace('"', '', $cidade);
            $bairro = str_replace('"', '', $bairro);
            $uf = str_replace('"', '', $uf);
            $slug = Inflector::slug($cidade . ' ' . $uf);
            $coordenadas = new Expression(
                $this->arrayToWkt('Point', [$longitude, $latitude])
            );

            $rowBairro = $this->findBairro($id_municipio, $bairro);

            if (!$rowBairro) {
                echo "Inserindo $bairro, {$cidade} - {$uf}\n";
                $this->insert(
                    'bairros',
                    [
                        'nome' => $bairro,
                        'municipio_id' => $id_municipio,
                        'cliente_id' => new Expression('(SELECT id FROM clientes WHERE municipio_id = ' . $id_municipio . ')'),
                        'coordenadas_centro' => $coordenadas,
                    ]
                );
                continue;
            }

            echo "Atualizando $bairro, {$cidade} - {$uf}\n";
            $this->update(
                'bairros',
                [
                    'coordenadas_centro' => $coordenadas,
                ],
                'id = ' . $rowBairro['id']
            );
        }
    }

    public function safeDown()
    {
        echo "m151121_125024_importa_bairros_municipios cannot be reverted.\n";
        return false;
    }

    protected function findBairro($municipio_id, $bairro)
    {
        $stmt = $this->db->masterPdo->prepare(
            "SELECT *
            FROM bairros
            WHERE municipio_id = :municipio_id
            AND nome = :nome"
        );
        $stmt->bindParam(':municipio_id', $municipio_id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $bairro, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    protected function getCsv()
    {
        return file_get_contents('https://raw.githubusercontent.com/alanwillms/geoinfo/master/latitude-longitude-bairros.csv');
    }
}
