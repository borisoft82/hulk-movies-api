<?php

namespace App\Policies;

use App\Helpers\Helper;

class MoviePolicy {

    public function authorize($movieId): bool {

        return Helper::loggedUserId() == $movieId;

    }
}