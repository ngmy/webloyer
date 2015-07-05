<?php namespace Tests\Helpers;

use Session;

trait ControllerTestHelper {

	public function post($uri, array $data = [], array $headers = [])
	{
		$data = array_merge($data, ['_token' => Session::token()]);

		parent::post($uri, $data, $headers);

		return $this;
	}

	public function put($uri, array $data = [], array $headers = [])
	{
		$data = array_merge($data, ['_token' => Session::token()]);

		parent::put($uri, $data, $headers);

		return $this;
	}

	public function delete($uri, array $data = [], array $headers = [])
	{
		$data = array_merge($data, ['_token' => Session::token()]);

		parent::delete($uri, $data, $headers);

		return $this;
	}

	protected function auth($class = 'App\Models\User', $data = [])
	{
		$user = new $class;

		foreach ($data as $key => $val) {
			$user->$key = $val;
		}

		$this->be($user);
	}

}
