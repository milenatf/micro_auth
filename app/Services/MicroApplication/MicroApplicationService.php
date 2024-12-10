<?php

namespace App\Services\MicroApplication;

use App\Models\User;
use Milenatf\MicroservicesCommon\Services\Traits\ConsumerExternalService;

class MicroApplicationService
{
    use ConsumerExternalService;

    protected $url, $token;

    public function __construct()
    {
        $this->url = config('services.micro_application.url');
        $this->token = config('services.micro_application.token');
    }
}