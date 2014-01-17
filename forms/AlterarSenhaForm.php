<?php

namespace app\forms;

use Yii;
use yii\base\Model;

class AlterarSenhaForm extends Model
{
    public $senha;
    public $senha2;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['senha', 'senha2'], 'required'],
            ['senha', 'compare', 'compareAttribute' => 'senha2'],
            ['senha', 'string', 'min' => 8],
        ];
    }
    
    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'senha' => 'Senha',
            'senha2' => 'Repita a senha',
        ];
    }
    
    /**
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) {
            
            Yii::$app->user->identity->senha = $this->senha;
            Yii::$app->user->identity->senha2 = $this->senha2;
            
            return (bool) Yii::$app->user->identity->save();
        }
        
        return false;
    }
}
