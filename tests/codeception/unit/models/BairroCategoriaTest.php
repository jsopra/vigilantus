<?php

namespace tests\unit\models;

use app\models\BairroCategoria;
use Phactory;
use perspectiva\phactory\ActiveRecordTest;

class BairroCategoriaTest extends ActiveRecordTest
{
    public function testNaoSalvaDuplicado()
    {
        // Trava no mesmo município
        $categoriaOriginal = Phactory::bairroCategoria(['nome' => 'Repetindo']);
        $categoriaDuplicada = Phactory::unsavedBairroCategoria();
        $categoriaDuplicada->attributes = $categoriaOriginal->attributes;
        $this->assertFalse($categoriaDuplicada->save());

        // Permite com municípios diferentes
        $categoriaDuplicada->cliente_id = Phactory::cliente()->id;
        $this->assertTrue($categoriaDuplicada->save());
    }

    public function testNaoExcluiCategoriaComBairros()
    {
        $categoriaSemBairros = Phactory::bairroCategoria();
        $this->assertEquals(1, $categoriaSemBairros->delete());
        $this->assertNull(BairroCategoria::findOne($categoriaSemBairros->id));

        $categoriaComBairros = Phactory::bairroCategoria();
        Phactory::bairro(['categoria' => $categoriaComBairros]);
        Phactory::bairro(['categoria' => $categoriaComBairros]);
        Phactory::bairro(['categoria' => $categoriaComBairros]);
        $this->setExpectedException('\Exception');
        $categoriaComBairros->delete();
    }
}
