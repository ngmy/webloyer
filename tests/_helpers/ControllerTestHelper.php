<?php

namespace Tests\Helpers;

use App\Models\User;
use Illuminate\Foundation\Testing\TestResponse;
use Session;

trait ControllerTestHelper
{
    public function post($uri, array $data = [], array $headers = []): TestResponse
    {
        $data = array_merge($data, ['_token' => Session::token()]);

        $response = parent::post($uri, $data, $headers);

        return $response;
    }

    public function put($uri, array $data = [], array $headers = []): TestResponse
    {
        $data = array_merge($data, ['_token' => Session::token()]);

        $response = parent::put($uri, $data, $headers);

        return $response;
    }

    public function delete($uri, array $data = [], array $headers = []): TestResponse
    {
        $data = array_merge($data, ['_token' => Session::token()]);

        $response = parent::delete($uri, $data, $headers);

        return $response;
    }

    protected function auth($obj = null, $data = []): void
    {
        if (isset($obj)) {
            $user = $obj;
        } else {
            $user = new User();
        }

        foreach ($data as $key => $val) {
            $user->$key = $val;
        }

        $this->be($user);
    }
}
