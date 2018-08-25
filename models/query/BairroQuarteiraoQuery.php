<?php
namespace app\models\query;

use Yii;
use app\components\ActiveQuery;
use app\models\BairroQuarteirao;
use app\models\Cliente;
use app\models\EquipeAgente;

class BairroQuarteiraoQuery extends ActiveQuery
{
    public function doBairro($id)
    {
        $this->andWhere('bairro_id = :id', [':id' => $id]);
        return $this;
    }

    public function doNumero($numero)
    {
        $this->andWhere("trim(leading '0' FROM numero_quarteirao) = :numero", [':numero' => ltrim($numero, '0')]);
        return $this;
    }

    public function dosNumeros($numero)
    {
        $this->andWhere("trim(leading '0' FROM numero_quarteirao) = :numero OR trim(leading '0' FROM numero_quarteirao_2) = :numero", [':numero' => ltrim($numero, '0')]);
        return $this;
    }

    public function daSequencia($numero)
    {
        $this->andWhere('seq = :sequencia', [':sequencia' => $numero]);
        return $this;
    }

    public function comCoordenadas()
    {
        $this->andWhere('coordenadas_area IS NOT NULL');
        return $this;
    }

    public function queNao($id)
    {
        $this->andWhere('id <> :quenao', [':quenao' => $id]);
        return $this;
    }

    public function pontoNaArea($coordenadas)
    {
        $this->andWhere('ST_Intersects(' . $coordenadas . ', coordenadas_area)');
        return $this;
    }

    public function comVisita(EquipeAgente $agente)
    {
        $this->andWhere("id IN (
            SELECT quarteirao_id.bairro_quarteirao_id
            FROM semana_epidemiologica_visitas
            WHERE agente_id = " . $agente->id . "
        )");

        return $this;
    }

    public function comFocosAtivos($lira = null, $especieTransmissor = null)
    {
        $whereLira = null;
        if ($lira !== null) {
            $whereLira = $lira === true ? 'imovel_lira = TRUE' : 'imovel_lira = FALSE';
        }

        $whereEspecie = '';
        if ($especieTransmissor !== null) {
            $whereEspecie = ' AND et.id = ' . $especieTransmissor;
        }

        $query = "
        id IN (
            SELECT focos_transmissores.bairro_quarteirao_id
            FROM focos_transmissores
            JOIN especies_transmissores et ON focos_transmissores.especie_transmissor_id = et.id
            LEFT JOIN imoveis ON focos_transmissores.imovel_id = imoveis.id
            WHERE
                " . ($whereLira ? $whereLira . ' AND ' : '') . "
                data_coleta BETWEEN NOW() - INTERVAL '1 DAY' * et.qtde_dias_permanencia_foco AND NOW() AND
                (quantidade_forma_aquatica > 0 OR quantidade_forma_adulta > 0 OR quantidade_ovos > 0)
                " . $whereEspecie . "
        )";

        $this->andWhere($query);

        return $this;
    }

    public function emAreaDeTratamento($cliente, $lira = null, $especieTransmissor = null)
    {
        $idsAreaTratamento = BairroQuarteirao::getIDsAreaTratamento($cliente->id, $especieTransmissor, $lira);

        $query = $idsAreaTratamento ? "id IN (" . implode(',', $idsAreaTratamento) . ")" : '1=2';

        $this->andWhere($query);

        return $this;
    }

    public function comFocoDeclarado()
    {
        $this->andWhere('data_ultimo_foco is not null');

        return $this;
    }
}
