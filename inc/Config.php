<?php
namespace ShortLinks;

class Config
{
    public const array ENTITIES = ['ShortLink'];
    private const string TEXT_DOMAIN = 'short-links';
    private const int UNIC_CLICKS_TIME_OFFSET_DEFAULT = 2;
    public const string CLICK_TIME_OFFSET_OPTION = 'shortlinks_unic_clicks_time_offset';

    public static function getEntities(): array
    {
        return self::ENTITIES;
    }

    public static function getUniqueClicksTimeOffset(): int
    {
        return get_option(self::CLICK_TIME_OFFSET_OPTION, self::UNIC_CLICKS_TIME_OFFSET_DEFAULT);
    }

    public static function getTextDomain(): string
    {
        return self::TEXT_DOMAIN;
    }
}