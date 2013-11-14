<?php
/**
 * Adiciona funcionalidades de filtro de municipio ao modelo
 */
class PMunicipioActiveRecord extends PActiveRecord
{
	
	public function beforeFind() {
		
        if(Yii::app()->hasComponent('user') && Yii::app()->user->getUser()->municipio_id) {
            
            $alias = $this->getTableAlias(false, false);
            
            $this->getDbCriteria()->mergeWith(array(
				'condition' => '"' . $alias . '"' . ".municipio_id = :idMunicipio",
				'params' => array(':idMunicipio' => (int) Yii::app()->user->getUser()->municipio_id),
            ));
        }
		
		return parent::beforeFind();
	}
	
	public function beforeValidate() {
		
		$parent = parent::beforeValidate();
		
		if(Yii::app()->hasComponent('user')) {
		
			if(!$this->municipio_id) {
				$this->municipio_id = Yii::app()->user->getUser()->municipio_id;
			} 
			else if($this->municipio_id != Yii::app()->user->getUser()->municipio_id) {
				$this->addError('municipio_id', Yii::t('Site', 'Município inválido!'));
			}
		}
		
		return $parent;
	}
}
