<?php

declare(strict_types=1);

namespace Deployer\Infra\Framework\Laravel\App\Listeners;

use Common\Domain\Model\Event\DomainEventPublisher;
use DateTimeImmutable;
use Deployer\Domain\Model\{
    DeployerFinished,
    DeployerProgressed,
    DeployerStarted,
};
use Illuminate\Support\Facades\{
    DB,
    Storage,
};
use stdClass;
use Symfony\Component\Process\Process;
use Webloyer\Domain\Model\Deployment\DeploymentRequested;

class DeploymentRequestedListener extends DeployerEventListener
{
    /** @var list<string> */
    private $createdFiles = [];

    /**
     * @return void
     */
    public function __destruct()
    {
        if (empty($this->createdFiles)) {
            return;
        }
        foreach ($this->createdFiles as $createdFile) {
            $this->deleteFile($createdFile);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function listensTo(string $typeName): bool
    {
        return $typeName == DeploymentRequested::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function perform(stdClass $event): void
    {
        $this->createDeployerFile($event);
        $this->runDeployer($event);
    }

    /**
     * @param stdClass $event
     * @return void
     */
    public function createDeployerFile(stdClass $event): void
    {
        // Create recipe files
        $i = 1;
        foreach ($event->recipe_bodies->recipe_bodies as $recipeBody) {
            $this->createFile($this->getRecipeFileName($event, $i++), $recipeBody->value);
        }

        // Create the server file
        $this->createFile($this->getServerFileName($event), $event->server_body->value);

        // Create the deployer file
        $contents[] = '<?php';
        $contents[] = 'namespace Deployer;';
        foreach ($this->getRecipeFileNames($event) as $recipeFileName) {
            $contents[] = "require '" . $recipeFileName . "';";
        }
        $contents[] = "set('repository', '" . $event->repository_url->value . "');";
        $contents[] = "serverList('" . $this->getServerFileName($event) . "');";
        $this->createFile($this->getDeployerFileName($event), implode(PHP_EOL, $contents));
    }

    /**
     * @param stdClass $event
     * @return void
     */
    public function runDeployer(stdClass $event): void
    {
        $process = new Process([
            base_path('vendor/bin/dep'),
            '-f=' . $this->getDeployerFileName($event),
            '--ansi',
            '-n',
            '-vvv',
            $event->task->scalar,
            $event->stage_name->value,
        ]);
        $process->setTimeout(600);
        DB::transaction(function () use ($event) {
            DomainEventPublisher::getInstance()->publish(
                new DeployerStarted(
                    $event->project_id->value,
                    $event->number->value,
                    (new DateTimeImmutable())->format('Y-m-d H:i:s')
                )
            );
        });

        $log = '';
        $process->run(function (string $type, string $buffer) use ($event, &$log) {
            $log .= $buffer;
            DB::transaction(function () use ($event, $log) {
                DomainEventPublisher::getInstance()->publish(
                    new DeployerProgressed(
                        $event->project_id->value,
                        $event->number->value,
                        $log
                    )
                );
            });
        });

        $log = $process->isSuccessful() ? $process->getOutput() : $process->getErrorOutput();
        assert(!is_null($process->getExitCode()));
        $status = $process->getExitCode();
        $finishDate = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        DomainEventPublisher::getInstance()->publish(
            new DeployerFinished(
                $event->project_id->value,
                $event->number->value,
                $log,
                $status,
                'now'
            )
        );
    }

    /**
     * @param stdClass $event
     * @return string
     */
    public function getServerFileName(stdClass $event): string
    {
        return sprintf('server_%s_%s.yaml', $event->project_id->value, $event->number->value);
    }

    /**
     * @param stdClass $event
     * @return list<string>
     */
    public function getRecipeFileNames(stdClass $event): array
    {
        $i = 1;
        return array_map(function (stdClass $recipeBody) use ($event, $i): string {
            return $this->getRecipeFileName($event, $i++);
        }, $event->recipe_bodies->recipe_bodies);
    }

    /**
     * @param stdClass $event
     * @param int      $i
     * @return string
     */
    public function getRecipeFileName(stdClass $event, int $i): string
    {
        return sprintf('server_%s_%s_%s.php', $event->project_id->value, $event->number->value, $i);
    }

    /**
     * @param stdClass $event
     * @return string
     */
    public function getDeployerFileName(stdClass $event): string
    {
        return sprintf('deployer_%s_%s.php', $event->project_id->value, $event->number->value);
    }

    /**
     * @param string $fileName
     * @param string $contents
     * @return void
     */
    public function createFile(string $fileName, string $contents): void
    {
        Storage::disk('local')->put($fileName, $contents);
        $this->createdFiles[] = $fileName;
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function deleteFile(string $fileName): void
    {
        Storage::disk('local')->delete($fileName);
    }
}
