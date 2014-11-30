<?php

namespace tests;

class FactoryObjectTrigger
{
    public function usuarioBeforeSave($usuario)
    {
        $usuario->confirmacao_senha = $usuario->senha;
    }
}
