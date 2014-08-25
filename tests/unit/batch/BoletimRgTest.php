<?php

namespace tests\unit\models;

use Yii;
use Phactory;
use tests\TestCase;
use app\batch\Row;
use app\models\ImovelTipo;

class BoletimRgTest extends TestCase
{
    private $_bairro;
    private $_quarteirao;
    private $_attributes;
    
    private function _createScenario() 
    {
        $this->_bairro = Phactory::bairro(['nome' => 'teste A', 'municipio_id' => 1]);
        $this->_quarteirao = Phactory::bairroQuarteirao(['numero_quarteirao' => '10', 'bairro_id' => $this->_bairro->id, 'municipio_id' => 1]);
        
        $this->_attributes = [
            'columns' => [
                'bairro',
                'quarteirao', 
                'folha', 
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
        
        $data = ['teste B', '10', '100', date('d/m/Y')];
        
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
        
        $data = ['teste A', '11', '100', date('d/m/Y')];
    
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
        
        $data = ['teste A', '10', '100', date('d/m/Y')];

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
}
