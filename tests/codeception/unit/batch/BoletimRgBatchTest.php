<?php

namespace tests\unit\models;

use Yii;
use Phactory;
use perspectiva\phactory\ActiveRecordTest;
use app\batch\Row;
use app\models\ImovelTipo;

class BoletimRgBatchTest extends ActiveRecordTest
{
    private $_bairro;
    private $_quarteirao;
    private $_attributes;

    private function _createScenario()
    {
        $cliente = Phactory::cliente();

        $this->_bairro = Phactory::bairro(['nome' => 'teste A', 'cliente_id' => $cliente->id]);
        $this->_quarteirao = Phactory::bairroQuarteirao(['numero_quarteirao' => '10', 'bairro_id' => $this->_bairro->id, 'cliente_id' => $cliente->id]);

        $this->_attributes = [
            'columns' => [
                'bairro',
                'quarteirao',
                'data',
            ],
        ];

        $tipoImovel = ImovelTipo::find()->all();

        foreach($tipoImovel as $tipo) {
            $this->_attributes['columns'][] = 'imovelTipo_' . $tipo->id;
        }

        foreach($tipoImovel as $tipo) {
            $this->_attributes['columns'][] = 'imovelTipo_' . $tipo->id . '_lira';
        }
    }

    public function testSaveRowErroBairro()
    {
        $this->_createScenario();

        $data = ['teste B', '10', date('d/m/Y')];

        $tipoImovel = ImovelTipo::find()->all();
        foreach($tipoImovel as $tipo) {
            $data[] = 1;
            $data[] = 1;
        }

        $row = new Row;
        $row->number = 1;
        $row->data = $data;

        $model = new \app\models\batch\BoletimRg;

        $row->model = $model;
        $row->model->attributes = $this->_attributes;

        $this->assertFalse($model->insert($row));

        $this->assertTrue(in_array('Bairro não localizado', $row->errors));
    }

    public function testSaveRowErroBairroQuarteirao()
    {
        $this->_createScenario();

        $data = ['teste A', '11', date('d/m/Y')];

        $tipoImovel = ImovelTipo::find()->all();
        foreach($tipoImovel as $tipo) {
            $data[] = 1;
            $data[] = 1;
        }

        $row = new Row;
        $row->number = 1;
        $row->data = $data;

        $model = new \app\models\batch\BoletimRg;

        $row->model = $model;
        $row->model->attributes = $this->_attributes;

        $this->assertFalse($model->insert($row));

        $this->assertTrue(in_array('Quarteirão não localizado', $row->errors));
    }

    public function testSaveRow()
    {
        $this->_createScenario();

        $data = ['teste A', '10', date('d/m/Y')];

        $tipoImovel = ImovelTipo::find()->all();
        foreach($tipoImovel as $tipo) {
            $data[] = 1;
            $data[] = 1;
        }

        $row = new Row;
        $row->number = 1;
        $row->data = $data;

        $model = new \app\models\batch\BoletimRg;

        $row->model = $model;
        $row->model->attributes = $this->_attributes;

        $this->assertTrue($model->insert($row, 1, 1));

        $this->assertEquals(0, count($row->errors));
    }

    protected function getModelClass()
    {
        return 'app\models\batch\BoletimRg';
    }
}
