<?php

class MunicipioTest extends PDbTestCase
{
	public function testDelete() {
        
        $municipio = Municipio::model()->findByPk(1);
        $this->setExpectedException('Exception');
        $municipio->delete();
    }
}