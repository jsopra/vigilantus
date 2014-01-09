<?php

class WebUser extends CWebUser {

	/**
	 * Usuário
	 * @var Usuario
	 */
	protected $_model;

	/**
	 * Carrega o usuário
	 * @return Usuario 
	 */
	public function getUser() {
		if ($this->_model === null) {
			$this->_model = Usuario::find($this->id);
		}
		return $this->_model;
	}
	
	public function isRoot() {
        
        if(!$this->_model)
			$this->_model = Usuario::find($this->id);
        
		return $this->_model->usuario_role_id == UsuarioRole::ROOT;
	}
	
	public function isAdministrador() {
		
		if(!$this->_model)
			$this->_model = Usuario::find($this->id);

		return in_array($this->_model->usuario_role_id, array(UsuarioRole::ROOT, UsuarioRole::ADMINISTRADOR));
	}
    
	public function checkAccess($opr, $params = array(), $allowCaching = true) {
		if ($this->isGuest) {
			return false;
		}

		$aOpr = !is_array($opr) ? array($opr) : $opr;

		foreach ($aOpr as $operation) {

			$rolesDesignadas = Yii::app()->authManager->getRoles(Yii::app()->user->id);

			if (empty($rolesDesignadas)) {

				Yii::app()->authManager->assign($this->getUser()->role->nome, Yii::app()->user->id);
			}

			// Verifica se ele tem a role (pode ser que ele seja admin, o que englobaria, por exemplo, o analista
			if (Yii::app()->authManager->isAssigned($operation, $this->id)) {
				return true;
			}

			$ret = $this->_roleHasChild($this->getUser()->role->nome, $operation);
			if ($ret) {
				return true;
			}
		}
		return false;
	}

	protected function _roleHasChild($role, $child) {
		
		foreach (Yii::app()->authManager->getItemChildren($role) as $role) {

			if ($role->name == $child || $this->_roleHasChild($role->name, $child)) {
				return true;
			}
		}

		return false;
	}
	
	public function getJQueryLanguage() {
		
		$language = Yii::app()->language;
		
		switch($language) {
			case 'pt' :
				return 'pt-BR';
			case 'pt_br' : 
				return 'pt-BR';
			default :
				return $language;
		}
		
	}
}