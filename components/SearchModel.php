<?php

namespace app\components;

use yii\base\Model;
use yii\data\ActiveDataProvider;

abstract class SearchModel extends Model
{
    /**
     * @return string
     */
    public function getModelClassName()
    {
        $searchClassNamespace = explode('\\', get_called_class());
        $searchClass = array_pop($searchClassNamespace);
        return substr($searchClass, 0, strlen($searchClass) - strlen('Search'));
    }
    
    /**
     * @return string
     */
    public function getModelClassFullName()
    {
        return 'app\\models\\' . $this->getModelClassName();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $className = $this->getModelClassFullName;
        return (new $className)-> attributeLabels();
    }

    /**
     * @param array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params = [])
    {
        $classFullName = $this->getModelClassFullName();
        $query = $classFullName::find();
        $this->searchScopes($query);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        
        $searchClassName = $this->getModelClassName() . 'Search';
        
        if (is_array($params) && count($params) && !isset($params[$searchClassName])) {
            $params = [$searchClassName => $params];
        }
        
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->searchConditions($query);
        
        return $dataProvider;
    }
    
    /**
     * @param \yii\db\ActiveQuery $query
     * @param string $attribute
     * @param boolean $partialMatch
     * @return void
     */
    protected function addCondition($query, $attribute, $partialMatch = false)
    {
        $value = $this->$attribute;
        
        if (trim($value) === '') {
            return;
        }
        
        if ($partialMatch) {
            $value = '%' . strtr($value, ['%' => '\%', '_' => '\_', '\\' => '\\\\']) . '%';
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
    
    /**
     * The method where you should apply your search conditions.
     *
     * For example:
     *
     * ```php
     * public function searchConditions($query)
     * {
     *     $this->addCondition($query, 'id');
     *     $this->addCondition($query, 'name', true);
     *     $this->addCondition($query, 'blocked');
     * }
     * ```
     * @param \yii\db\ActiveQuery $query
     */
    abstract public function searchConditions($query);
    
    /**
     * Apply scopes before even if it is not filtering
     * @param \yii\db\ActiveQuery $query
     */
    public function searchScopes($query)
    {
        
    }
}
