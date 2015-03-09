<?php
namespace app\models\social\action;

use Yii;
use MetzWeb\Instagram\Instagram;
use yii\base\Object;
use \yii\helpers\Json;
use app\models\mongo\SocialSearchResult;
use app\models\Social;
use app\models\SocialSearch;

/**
 * Class Facebook
 * @package app\models\sharer
 */
class InstagramSocialAction extends Object implements SocialActionInterface
{
    /**
     * @var InstagramSession
     */
    protected $session;

    /**
     * @var Social profile
     */
    protected $profile;

    /**
     * @param Social $socialProfile
     */
    public function __construct(Social $socialProfile)
    {
        $this->profile = $socialProfile;
        $this->session = new Instagram(Yii::$app->params['instagram']['app_key']);
        $this->session->setAccessToken($this->profile->token);
    }

    /**
     * @inheritdoc
     */
    public function complaintCapture()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function sendDirectMessage($message, $userId)
    {
        return false;
    }
}
