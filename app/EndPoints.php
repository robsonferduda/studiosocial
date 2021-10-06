<?php

namespace App;

class EndPoints
{
    const BASE_URL = 'https://graph.facebook.com/v12.0/';
    const MENTION_URL = '{ig_user_id}/tags';
    const SEARCH_ID_HASHTAG_URL = 'ig_hashtag_search';
    const RECENT_MEDIA_BY_HASHTAG_URL = '{ig_hashtag_id}/recent_media';
    
    public static function getMetionsLink($ig_user_id)
    {
        return str_replace('{ig_user_id}', urlencode($ig_user_id), static::BASE_URL.static::MENTION_URL);
    }

    public static function getSearchIdHashTagLink()
    {
        return  static::BASE_URL.static::SEARCH_ID_HASHTAG_URL;
    }

    public static function getRecentMediaByHashTagLink($ig_hashtag_id)
    {
        return str_replace('{ig_hashtag_id}', urlencode($ig_hashtag_id), static::BASE_URL.static::RECENT_MEDIA_BY_HASHTAG_URL);
    }
}
