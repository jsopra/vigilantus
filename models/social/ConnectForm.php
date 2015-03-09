<?php

namespace app\models\social;

use app\models\SocialAccount;
use app\models\User;
use yii\base\Model;
use Yii;

/**
 * Class ConnectForm
 *
 * Connect social account to an existing user.
 *
 * @package app\models\social
 */
class ConnectForm extends Model
{

    /**
     * @var User
     */
    public $user;

    /**
     * @var Profile
     */
    public $profile;

    /**
     * @var Cliente
     */
    public $cliente;

    /**
     * @var int
     */
    public $usuario_id;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['user', 'profile', 'cliente', 'usuario_id'], 'required'],
        ];
    }

    /**
     * Connect social account
     */
    public function connect()
    {
        if (!$this->validate()) {
            return false;
        }

        if (!$this->profile->validate()) {
            $this->addError('profile', Yii::t('app', 'Profile details contains invalid information.'));
            return false;
        }

        $social = SocialAccount::find()->doCliente($this->cliente->id)->daRede($this->profile->social)->one();
        if (!$social) {
            $social = new SocialAccount();
            $social->cliente_id = $this->cliente->id;
            $social->social = $this->profile->social;
            $social->inserido_por = $this->usuario_id;
        }

        $social->social_id = (string) $this->profile->id;
        $social->token = $this->profile->token;

        if (!$social->save()) {
            $this->addError('profile', Yii::t('app', 'Algo ocorreu errado ao associar o perfil'));
            return false;
        }
        return true;
    }
}
