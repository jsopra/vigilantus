<?php
namespace app\models\social\action;

/**
 * Interface SocialActionInterface
 */
interface SocialActionInterface
{
    /**
     * @return array
     */
    public function complaintCapture();

    /**
     * @param string $message
     * @param integer $userId
     * @return boolean
     */
    public function sendDirectMessage($message, $userId);
}
