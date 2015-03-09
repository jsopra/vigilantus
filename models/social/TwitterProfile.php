<?php

namespace app\models\social;

use app\models\SocialNetwork;
use app\models\User;
use yii\helpers\Json;

/**
 * Class TwitterProfile
 */
class TwitterProfile extends Profile
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
     * @return int
     */
    public function getSocial()
    {
        return SocialNetwork::TWITTER;
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
    public function getEmail()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->getRawAttribute('screen_name');
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        $rawAttributes = $this->rawAttributes;
        unset($rawAttributes['entities']);
        unset($rawAttributes['status']);
        return Json::encode($this->rawAttributes);
    }

    /**
     * @return mixed|void
     */
    public function getToken()
    {
        return Json::encode($this->accessToken);
    }

    /**
     * No Expiration
     *
     * @return mixed|null
     */
    public function getTokenExpiration()
    {
        return null;
    }
}
