<?php

namespace tests\unit\models;

use app\models\BairroCategoria;
use Phactory;
use tests\TestCase;

class BairroCategoriaTest extends TestCase
{
    public function testNaoSalvaDuplicado()
    {
        // Trava no mesmo município
        Phactory::bairroCategoria(['nome' => 'Espacial', 'cliente_id' => 1]);
        $categoriaDuplicada = Phactory::bairroCategoria(['cliente_id' => 1]);
        $categoriaDuplicada->nome = 'Espacial';
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
        Phactory::bairro(['bairro_categoria_id' => $categoriaComBairros->id]);
        Phactory::bairro(['bairro_categoria_id' => $categoriaComBairros->id]);
        Phactory::bairro(['bairro_categoria_id' => $categoriaComBairros->id]);
        $this->setExpectedException('\Exception');
        $categoriaComBairros->delete();
    }
}
