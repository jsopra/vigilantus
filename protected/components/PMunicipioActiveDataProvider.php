<?php

class PMunicipioActiveDataProvider extends CActiveDataProvider {
	
	public function __construct($modelClass,$config=array()) {
		
		if(Yii::app()->hasComponent('user') && method_exists(Yii::app()->user, 'getUser') && Yii::app()->user->getUser()->municipio_id)
            $config['criteria']->compare('t.municipio_id',(int) Yii::app()->user->getUser()->municipio_id);	
		
		parent::__construct($modelClass, $config);
	}
}