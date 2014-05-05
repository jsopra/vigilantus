<?php
namespace app\models\query;

use app\components\ActiveQuery;

class ImovelQuery extends ActiveQuery
{
    public function daRua($rua)
    {
        $this->andWhere('rua_id = :rua', [':rua' => $rua]);
        return $this;
    }

    public function doQuarteirao($quarteirao)
    {
        $this->andWhere('bairro_quarteirao_id = :quarteirao', [':quarteirao' => $quarteirao]);
        return $this;
    }

    public function doNumero($numero)
    {
        if (strval($numero) !== '') {
            $this->andWhere('numero = :numero', [':numero' => $numero]);
        } else {
            $this->andWhere("numero = '' OR numero IS NULL");
        }
        return $this;
    }

    public function daSeq($seq)
    {
        if (strval($seq) !== '') {
            $this->andWhere('sequencia = :seq', [':seq' => $seq]);
        } else {
            $this->andWhere("sequencia = '' OR sequencia IS NULL");
        }
        return $this;
    }

    public function doComplemento($complemento)
    {
        if (strval($complemento) !== '') {
            $this->andWhere('complemento = :compl', [':compl' => $complemento]);
        } else {
            $this->andWhere("complemento = '' OR complemento IS NULL");
        }
        return $this;
    }

    public function doTipoLira($foco)
    {
        if ($foco) {
            $this->andWhere('imovel_lira = TRUE');
        } else {
            $this->andWhere('imovel_lira = FALSE OR imovel_lira IS NULL');
        }
        return $this;
    }
}
