<?php
class UserRole extends CPhpAuthManager
{
    /**
     * Carrega a hierarquia de roles
     */
    public function init()
    {
        $return = parent::init();

		$this->createRole('Gerente');
        
        $this->createRole('Usuario');
		
		$roleAdministrador = $this->createRole('Administrador');
		$roleAdministrador->addChild('Gerente');
        $roleAdministrador->addChild('Usuario');
		
        $roleRoot = $this->createRole('Root');
		$roleRoot->addChild('Administrador');

        return $return;
    }
}
