<?php

namespace App\Enums;

enum Commentable: string
{
    case Post = 'post';
    case Comment = 'comment';
}
