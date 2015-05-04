<?php namespace Tests\Helpers;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

trait ConsoleCommandTestHelper {

	protected function runConsoleCommand(Command $command, $arguments = [], $options = [])
	{
		$command->setLaravel($this->app);

		$tester = new CommandTester($command);

		return $tester->execute($arguments, $options);
	}

}
