<?php

namespace App\Enums;

class ListingType
{
    const DOMAIN = 'domain';
    const WEBSITE = 'website';
    const SOCIAL_MEDIA = 'social_media';

    public static function all()
    {
        return [
            self::DOMAIN => 'Domain',
            self::WEBSITE => 'Website',
            self::SOCIAL_MEDIA => 'Social Media Account',
        ];
    }

    public static function isValid($type)
    {
        return in_array($type, [self::DOMAIN, self::WEBSITE, self::SOCIAL_MEDIA]);
    }
}


