<?php
$sal = 'asd7y%i3';

return array(
	'usuarios_1' => array(
        'id' => '1',
        'nome' => 'perspectiva.in',
        'login' => 'perspectiva',
        'senha' => Usuario::encryptPassword($sal, 'a1s2d3f4g5'),
        'sal' => $sal,
        'usuario_role_id' => 1,
        'email' => 'dengue@perspectiva.in',
        'excluido' => false,
    ),
    'usuarios_2' => array(
        'id' => '2',
        'nome' => 'administrador',
        'login' => 'administrador',
        'senha' => Usuario::encryptPassword($sal, 'administrador'),
        'sal' => $sal,
        'usuario_role_id' => 2,
        'municipio_id' => 1,
        'email' => 'dengueadministrador@perspectiva.in',
        'excluido' => false,
    ),
    'usuarios_3' => array(
        'id' => '3',
        'nome' => 'excluido',
        'login' => 'excluido',
        'senha' => Usuario::encryptPassword($sal, 'excluido'),
        'sal' => $sal,
        'usuario_role_id' => 2,
        'municipio_id' => 2,
        'email' => 'dengueexcluido@perspectiva.in',
        'excluido' => true,
    ),
);