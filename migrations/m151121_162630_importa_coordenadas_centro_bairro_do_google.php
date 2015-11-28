<?php

use perspectivain\postgis\postgisTrait;
use yii\db\Expression;
use yii\db\Migration;

class m151121_162630_importa_coordenadas_centro_bairro_do_google extends Migration
{
    use postgisTrait;

    public function up()
    {
        $command = $this->db->createCommand(
            "SELECT
                b.id AS bairro_id,
                b.nome AS bairro,
                m.nome AS cidade,
                m.sigla_estado AS uf
            FROM bairros b
            INNER JOIN municipios m ON b.municipio_id = m.id
            WHERE b.coordenadas_centro IS NULL
            ORDER BY sigla_estado, m.nome, b.nome"
        );
        $reader = $command->query();
        while ($row = $reader->read()) {
            $id = $row['bairro_id'];
            $bairro = $row['bairro'];
            $cidade = $row['cidade'];
            $uf = $row['uf'];

            $coordenadas = $this->getCoordenadasCentro($bairro, $cidade, $uf);

            if (!$coordenadas) {
                echo "Parece que o Google não permite mais baixar dados!\n";
                echo "Aguarde alguns minutos e rode novamente a migration. ";
                echo "Ela continuará de onde parou.\n";
                return false;
            }

            $this->update(
                'bairros',
                ['coordenadas_centro' => $coordenadas],
                'id = ' . $id
            );
        }
    }

    public function safeDown()
    {
        echo "m151121_162630_importa_coordenadas_centro_bairro_do_google cannot be reverted.\n";
        return false;
    }

    protected function getCoordenadasCentro($bairro, $cidade, $uf)
    {
        $ch = curl_init();
        curl_setopt(
            $ch,
            CURLOPT_URL,
            'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode(
                'Bairro ' . $bairro . ', ' . $cidade . ' - ' . $uf . ', Brasil'
            ) . '&key=' . getenv('GOOGLE_MAPS_KEY')
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        $dadosGoogle = json_decode($output, true);

        echo "Atualizando $bairro, {$cidade} - {$uf}: ";

        if (
            $dadosGoogle
            && isset($dadosGoogle['results'])
            && isset($dadosGoogle['results'][0])
            && isset($dadosGoogle['results'][0]['geometry'])
            && isset($dadosGoogle['results'][0]['geometry']['location'])
            ) {
            $latitude = $dadosGoogle['results'][0]['geometry']['location']['lat'];
            $longitude = $dadosGoogle['results'][0]['geometry']['location']['lng'];
            echo "{$longitude}, {$latitude}\n";
            return new Expression($this->arrayToWkt('Point', [$longitude, $latitude]));
        }

        if ($dadosGoogle && isset($dadosGoogle['status']) && $dadosGoogle['status'] == 'ZERO_RESULTS') {
            echo "não encontrado\n";
            return new Expression('NULL');
        }

        echo $output;

        echo "?\n";
    }
}
