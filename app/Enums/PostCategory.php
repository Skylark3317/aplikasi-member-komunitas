<?php

namespace App\Enums;

enum PostCategory: string
{
    case All = '';
    case News = 'news';
    case Event = 'event';
}
