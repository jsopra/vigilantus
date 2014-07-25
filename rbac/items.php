<?php

use yii\rbac\Item;

return [
    'Anonimo' => [
        'type' => Item::TYPE_ROLE,
    ],
    'Usuario' => [
        'type' => Item::TYPE_ROLE,
        'children' => ['Anonimo'],
    ],
    'Gerente' => [
        'type' => Item::TYPE_ROLE,
        'children' => ['Usuario'],
    ],
    'Administrador' => [
        'type' => Item::TYPE_ROLE,
        'children' => ['Gerente'],
    ],
    'Root' => [
        'type' => Item::TYPE_ROLE,
        'children' => ['Administrador'],
    ],
];
