<?php

namespace app\ocorrencia;

use app\models\Cliente;
use app\models\Municipio;
use Yii;

class Module extends \yii\base\Module
{
    /**
     * @var Municipio
     */
    protected $municipio;

    /**
     * @var Cliente
     */
    protected $cliente;

    public function getMunicipio()
    {
        if (null === $this->municipio) {
            $slug = Yii::$app->request->get('slug', '');
            if ($municipio = Municipio::find()->where(['slug' => $slug])->one()) {
                $this->municipio = $municipio;
            } else {
                throw new HttpException(400, 'Município não localizado');
            }
        }
        return $this->municipio;
    }

    public function getCliente()
    {
        if (null === $this->cliente) {
            $municipio = $this->getMunicipio();
            if ($cliente = Cliente::find()->where(['municipio_id' => $municipio->id])->one()) {
                $this->cliente = $cliente;
            } else {
                throw new HttpException(400, 'Este município ainda não utiliza o software.');
            }
        }
        return $this->cliente;
    }
}
