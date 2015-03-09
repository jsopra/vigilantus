<?php

namespace app\models\social;

use app\models\SocialNetwork;
use app\models\User;
use yii\helpers\Json;

/**
 * Class FacebookProfile
 * @package app\models\social
 */
class FacebookProfile extends Profile
{

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->getRawAttribute('email');
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
        return SocialNetwork::FACEBOOK;
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
        return $this->accessToken;
    }

    /**
     * @return int|mixed
     */
    public function getTokenExpiration()
    {
        return time() + $this->accessToken['expires'];
    }
}
