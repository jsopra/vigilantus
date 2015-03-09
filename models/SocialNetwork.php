<?php
namespace app\models;

class SocialNetwork
{
    const INSTAGRAM = 1;
    const TWITTER = 2;
    const FACEBOOK = 3;

    /**
     * @return array
     */
    public static function getNetworks()
    {
        return [
            self::INSTAGRAM => 'Instagram',
            self::TWITTER => 'Twitter',
            self::FACEBOOK => 'Facebook',
        ];
    }

    /**
     * @return string
     */
    public static function getNetworkName($id)
    {
        $networks = self::getNetworks();

        return isset($networks[$id]) ? $networks[$id] : null;
    }
}
