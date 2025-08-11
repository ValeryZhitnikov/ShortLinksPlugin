<?php
namespace ShortLinks;

class Config
{
    public const ENTITIES = ['ShortLink'];
    private const TEXT_DOMAIN = 'short-links';
    private const UNIC_CLICKS_TIME_OFFSET_DEFAULT = 2;

    public static function getEntities(): array
    {
        return self::ENTITIES;
    }

    public static function getUniqueClicksTimeOffset(): int
    {
        return (int) get_option('shortlinks_unic_clicks_time_offset', self::UNIC_CLICKS_TIME_OFFSET_DEFAULT);
    }

    public static function getTextDomain(): string
    {
        return self::TEXT_DOMAIN_DEFAULT;
    }
}