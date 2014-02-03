<?php

$sal = 'asd7y%i3';

return array(
	// não adicione registros que foram inseridos em migrations
    'usuarios_2' => array(
        'id' => '2',
        'nome' => 'administrador',
        'login' => 'administrador',
        'senha' => md5('administrador' . $sal),
        'sal' => $sal,
        'usuario_role_id' => 2,
        'municipio_id' => 1,
        'email' => 'dengueadministrador@perspectiva.in',
    ),
    'usuarios_3' => array(
        'id' => '3',
        'nome' => 'excluido',
        'login' => 'excluido',
        'senha' => md5('excluido' . $sal),
        'sal' => $sal,
        'usuario_role_id' => 2,
        'municipio_id' => 2,
        'email' => 'dengueexcluido@perspectiva.in',
        'excluido' => new yii\db\Expression('true'),
    ),
    'usuarios_4' => array(
        'id' => '4',
        'nome' => 'root',
        'login' => 'root',
        'senha' => md5('root' . $sal),
        'sal' => $sal,
        'usuario_role_id' => 1,
        'municipio_id' => null,
        'email' => 'dengueroot@perspectiva.in',
        'excluido' => new yii\db\Expression('false'),
    ),
);