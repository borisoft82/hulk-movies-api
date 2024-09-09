<?php 

namespace App\Helpers;

use Illuminate\Contracts\Auth\Authenticatable;

class Helper {

    public static function loggedUser(): ?Authenticatable
    {
        return auth()->user();
    }

    public static function loggedUserId(): int|null
    {
        return auth()->user()->id;
    }
    
}