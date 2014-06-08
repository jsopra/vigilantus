<?php
class BlogPostPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'titulo' => 'Post A',
            'descricao' => null,
            'texto' => 'Texto do post #{sn}',
            'data' => date('d/m/Y H:i:s'),
        ];
    }
}