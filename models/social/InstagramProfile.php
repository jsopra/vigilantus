<?php

namespace app\models\social;

use app\models\SocialNetwork;
use app\models\User;
use yii\helpers\Json;

/**
 * Class InstagramProfile
 * @package app\models\social
 */
class InstagramProfile extends Profile
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['id', 'required'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getRawAttribute('id');
    }

    /**
     * @inheritdoc
     */
    public function getSocial()
    {
        return SocialNetwork::INSTAGRAM;
    }

    /**
     * @inheritdoc
     */
    public function getMeta()
    {
        return Json::encode($this->rawAttributes);
    }

    /**
     * @return mixed|string
     */
    public function getToken()
    {
        return $this->accessToken['access_token'];
    }

    /**
     * @return int|mixed
     */
    public function getTokenExpiration()
    {
        return time() + $this->accessToken['expires'];
    }
}
