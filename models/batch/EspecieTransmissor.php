<?php

namespace app\models\batch;

use app\batch\Model;

class EspecieTransmissor extends Model
{
    /**
     * @inheritdoc
     */
    public function columnLabels()
    {
        return [
            'nome' => 'Nome',
            'qtde_metros_area_foco' => 'Área de foco (metros)',
            'qtde_dias_permanencia_foco' => 'Permanência do foco (dias)',
        ];
    }
    /**
     * @inheritdoc
     */
    public function columnHints()
    {
        return [
            'qtde_metros_area_foco' => 'Somente números',
            'qtde_dias_permanencia_foco' => 'Somente números',
        ];
    }

    /**
     * @inheritdoc
     */
    public function insert($row)
    {
        $model = new \app\models\EspecieTransmissor;

        $model->nome = $row->getValue('nome');
        $model->qtde_metros_area_foco = $row->getValue('qtde_metros_area_foco');
        $model->qtde_dias_permanencia_foco = $row->getValue('qtde_dias_permanencia_foco');

        if ($model->save()) {
            return true;
        } else {
            $row->addErrorsFromObject($model);
            return false;
        }
    }
}