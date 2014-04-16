<?php

namespace app\forms;

use Yii;
use yii\base\Model;

class AlterarSenhaForm extends Model
{
    public $senha;
    public $confirmacao_senha;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['senha', 'confirmacao_senha'], 'required'],
            ['senha', 'compare', 'compareAttribute' => 'confirmacao_senha'],
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
            'confirmacao_senha' => 'Repita a senha',
        ];
    }
    
    /**
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) {
            
            Yii::$app->user->identity->senha = $this->senha;
            Yii::$app->user->identity->confirmacao_senha = $this->confirmacao_senha;
            
            return (bool) Yii::$app->user->identity->save();
        }
        
        return false;
    }
}
