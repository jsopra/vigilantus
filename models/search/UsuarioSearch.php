<?php

namespace app\models\search;

use app\components\SearchModel;
use Yii;

class UsuarioSearch extends SearchModel
{

    public $id;
    public $nome;
    public $login;
    public $municipio_id;
    public $usuario_role_id;
    public $ultimo_login;
    public $email;

    public function rules()
    {
        return [
            [['id', 'municipio_id', 'usuario_role_id'], 'integer'],
            [['nome', 'login', 'ultimo_login', 'email'], 'safe'],
        ];
    }
    
    public function searchScopes($query)
    {
        $query->ativo();
        $query->doNivelDoUsuario(Yii::$app->user->identity);
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'nome', true);
        $this->addCondition($query, 'login', true);
        $this->addCondition($query, 'municipio_id');
        $this->addCondition($query, 'usuario_role_id');
        $this->addCondition($query, 'ultimo_login', true);
        $this->addCondition($query, 'email', true);
    }
}
