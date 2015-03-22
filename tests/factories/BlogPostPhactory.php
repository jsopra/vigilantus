<?php

namespace tests\factories;

class BlogPostPhactory
{
    public function blueprint()
    {
        return [
            'titulo' => 'Post A',
            'descricao' => null,
            'texto' => 'Texto do post #{sn}',
            'data' => date('d/m/Y H:i:s'),
        ];
    }
}
