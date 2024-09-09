<?php

namespace App\Enums;

enum HttpStatusMessages: string
{
    case OK = 'ok';
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
    case NO_CONTENT = 'no_content';
    case BAD_REQUEST = 'bad_request';
    case LOGGED_IN = 'logged_in';
    case LOGGED_OUT = 'logged_out';
    case TOKEN_REFRESHED = 'token_refreshed';
    case UNAUTHORIZED = 'unauthorized';
    case UNAUTHENTICATED = 'unauthenticated';
    case NOT_FOUND = 'not_found';
}