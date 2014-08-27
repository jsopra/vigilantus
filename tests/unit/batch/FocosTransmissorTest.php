<?php

namespace tests\unit\models;

use Yii;
use Phactory;
use tests\TestCase;
use app\models\FocoTransmissor;
use app\batch\Row;

class FocosTransmissorTest extends TestCase
{
    private $_bairro;
    private $_quarteirao;
    private $_tipoDeposito;
    private $_especieTransmissor;
    private $_attributes;
    
    private function _createScenario() 
    {
        $this->_bairro = Phactory::bairro(['nome' => 'teste A', 'municipio_id' => 1]);
        $this->_quarteirao = Phactory::bairroQuarteirao(['numero_quarteirao' => '10', 'bairro_id' => $this->_bairro->id, 'municipio_id' => 1]);
        $this->_tipoDeposito = Phactory::depositoTipo(['sigla' => 'TT', 'municipio_id' => 1]);
        $this->_especieTransmissor = Phactory::especieTransmissor(['nome' => 'Transmissor Teste', 'municipio_id' => 1]);
        
        $this->_attributes = [
            'columns' => [
                'laboratorio',
                'tecnico', 
                'tipo_deposito', 
                'especie', 
                'bairro',
                'quarteirao',
                'data_entrada',
                'data_exame',
                'data_coleta',
                'qtde_aquatica',
                'qtde_adulta',
                'qtde_ovos',      
            ],
        ];
    }

    public function testSaveRowErroBairro()
    {
        $this->_createScenario();
        
        $data = ['teste lab', 'teste tecnico', 'TT', 'Transmissor Teste', 'teste B', '10', date('d/m/Y'), date('d/m/Y'), date('d/m/Y'), '1', '1', '1'];
    
        $row = new Row;    
        $row->number = 1;
        $row->data = $data;
        
        $model = new \app\models\batch\FocosTransmissor;
        
        $row->model = $model;
        $row->model->attributes = $this->_attributes;
        
        $this->assertFalse($model->insert($row));
        
        $this->assertTrue(in_array('Bairro não localizado', $row->errors));
    }
    
    
    public function testSaveRowErroBairroQuarteirao()
    {
        $this->_createScenario();
        
        $data = ['teste lab', 'teste tecnico', 'TT', 'Transmissor Teste', 'teste A', '11', date('d/m/Y'), date('d/m/Y'), date('d/m/Y'), '1', '1', '1'];
    
        $row = new Row;    
        $row->number = 1;
        $row->data = $data;
        
        $model = new \app\models\batch\FocosTransmissor;
        
        $row->model = $model;
        $row->model->attributes = $this->_attributes;
        
        $this->assertFalse($model->insert($row));

        $this->assertTrue(in_array('Quarteirão não localizado', $row->errors));
    }
    
    public function testSaveRowErroTipoDeposito()
    {
        $this->_createScenario();
        
        $data = ['teste lab', 'teste tecnico', 'TX', 'Transmissor Teste', 'teste A', '10', date('d/m/Y'), date('d/m/Y'), date('d/m/Y'), '1', '1', '1'];
    
        $row = new Row;    
        $row->number = 1;
        $row->data = $data;
        
        $model = new \app\models\batch\FocosTransmissor;
        
        $row->model = $model;
        $row->model->attributes = $this->_attributes;
        
        $this->assertFalse($model->insert($row));
        
        $this->assertTrue(in_array('Tipo de depósito não localizado', $row->errors));
    }
    
    public function testSaveRowErroEspecieTransmissor()
    {
        $this->_createScenario();
        
        $data = ['teste lab', 'teste tecnico', 'TT', 'Transmissor Erro', 'teste A', '10', date('d/m/Y'), date('d/m/Y'), date('d/m/Y'), '1', '1', '1'];
    
        $row = new Row;    
        $row->number = 1;
        $row->data = $data;
        
        $model = new \app\models\batch\FocosTransmissor;
        
        $row->model = $model;
        $row->model->attributes = $this->_attributes;
        
        $this->assertFalse($model->insert($row));
        
        $this->assertTrue(in_array('Espécie transmissor não localizado', $row->errors));
    }
    
    public function testSaveRow()
    {
        $this->_createScenario();
        
        $data = ['teste lab', 'teste tecnico', 'TT', 'Transmissor Teste', 'teste A', '10', date('d/m/Y'), date('d/m/Y'), date('d/m/Y'), '1', '1', '1'];

        $row = new Row;    
        $row->number = 1;
        $row->data = $data;
        
        $model = new \app\models\batch\FocosTransmissor;
        
        $row->model = $model;
        $row->model->attributes = $this->_attributes;
        
        $this->assertTrue($model->insert($row, 1));
        
        $this->assertEquals(0, count($row->errors));
    }
}
