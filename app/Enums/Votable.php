<?php

namespace App\Enums;

enum Votable: string
{
    case Post = 'post';
    case Comment = 'comment';
}
