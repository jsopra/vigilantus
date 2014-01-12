<?php
use yii\rbac\Item;

return [
    'Anonimo' => [
        'type' => Item::TYPE_ROLE,
        'description' => '',
        'bizRule' => '',
        'data' => '',
        'children' => [],
    ],
    'Usuario' => [
        'type' => Item::TYPE_ROLE,
        'description' => '',
        'bizRule' => '',
        'data' => '',
        'children' => ['Anonimo'],
    ],
    'Gerente' => [
        'type' => Item::TYPE_ROLE,
        'description' => '',
        'bizRule' => '',
        'data' => '',
        'children' => ['Usuario'],
    ],
    'Administrador' => [
        'type' => Item::TYPE_ROLE,
        'description' => '',
        'children' => ['Gerente'],
        'bizRule' => '',
        'data' => ''
    ],
    'Root' => [
        'type' => Item::TYPE_ROLE,
        'description' => '',
        'children' => ['Administrador'],
        'bizRule' => '',
        'data' => ''
    ],
];
