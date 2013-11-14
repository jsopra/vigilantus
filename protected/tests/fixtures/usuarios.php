<?php
$sal = 'asd7y%i3';

return array(
	'usuarios_1' => array(
        'id' => '1',
        'nome' => 'erspectiva.in',
        'login' => 'perspectiva',
        'senha' => Usuario::encryptPassword($sal, 'a1s2d3f4g5'),
        'sal' => $sal,
        'usuario_role_id' => 1,
        'email' => 'dengue@perspectiva.in',
        'excluido' => false,
    ),
);