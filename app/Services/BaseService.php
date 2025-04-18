<?php

namespace App\Services;

use App\Traits\Response;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;

class BaseService
{
    use Response;

    public $onDebug;

    public function __construct()
    {
        $this->onDebug = env('APP_DEBUG');
    }
   
}
