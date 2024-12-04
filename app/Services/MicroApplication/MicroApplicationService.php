<?php

namespace App\Services\MicroApplication;

use Milenatf\MicroservicesCommon\Services\Traits\ConsumerExternalService;

class MicroApplicationService
{
    use ConsumerExternalService;

    protected $url, $token, $user;

    public function __construct()
    {
        $this->url = config('services.micro_application.url');
        $this->token = config('services.micro_application.token');
    }

    public function auth(string $uuidTeacher)
    {
        $response = $this->request('get', "/auth/{$uuidTeacher}");
        // dd($response);

        // dd($response->body());
        return $response->body();
        // dd('microAuthApplication');
    }
}