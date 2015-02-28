<?php namespace Tests\Helpers;

use Session;

trait ControllerTestHelper {

	protected function auth($class = 'App\Models\User', $data = [])
	{
		$user = new $class;

		foreach ($data as $key => $val) {
			$user->$key = $val;
		}

		$this->be($user);
	}

	protected function get($uri, $params = [])
	{
		$this->call('GET', $uri, $params);
	}

	protected function post($uri, $params = [])
	{
		$params = array_merge($params, ['_token' => Session::token()]);

		$this->call('POST', $uri, $params);
	}

	protected function put($uri, $params = [])
	{
		$params = array_merge($params, ['_token' => Session::token()]);

		$this->call('PUT', $uri, $params);
	}

	protected function delete($uri, $params = [])
	{
		$params = array_merge($params, ['_token' => Session::token()]);

		$this->call('DELETE', $uri, $params);
	}

}
