<?php
declare(strict_types=1);

namespace App\Services\Deployment;

use App\Services\Filesystem\FilesystemInterface;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;

/**
 * Class DeployerServerListFileBuilder
 * @package App\Services\Deployment
 */
class DeployerServerListFileBuilder implements DeployerFileBuilderInterface
{
    /**
     * @var FilesystemInterface
     */
    protected FilesystemInterface $fs;

    /**
     * @var DeployerFile
     */
    protected DeployerFile $deployerFile;

    /**
     * @var Parser
     */
    protected Parser $yamlParser;

    /**
     * @var Dumper
     */
    protected Dumper $yamlDumper;

    /**
     * @var null|Model
     */
    protected ?Model $server;

    /**
     * @var null|Model
     */
    protected ?Model $project;

    /**
     * @var bool
     */
    protected bool $deployerFileInitialized = false;

    /**
     * DeployerServerListFileBuilder constructor.
     * @param FilesystemInterface $fs
     * @param DeployerFile $deployerFile
     * @param Parser $parser
     * @param Dumper $dumper
     */
    public function __construct(FilesystemInterface $fs, DeployerFile $deployerFile, Parser $parser, Dumper $dumper)
    {
        $this->fs = $fs;
        $this->deployerFile = $deployerFile;
        $this->yamlParser = $parser;
        $this->yamlDumper = $dumper;
    }

    public function __destruct()
    {
        $this->fs->delete($this->deployerFile->getFullPath());
    }

    /**
     * Set a server list file path info.
     *
     * @return DeployerServerListFileBuilder $this
     */
    public function pathInfo()
    {
        $id = md5(uniqid((string)rand(), true));

        $baseName = "host_$id.yaml";
        $fullPath = storage_path("app/$baseName");

        $this->deployerFile->setBaseName($baseName);
        $this->deployerFile->setFullPath($fullPath);
        return $this;
    }

    /**
     * Put a server list file.
     *
     * @return DeployerServerListFileBuilder $this
     */
    public function put()
    {
        $fullPath = $this->deployerFile->getFullPath();
        $contents = $this->server->body;

        // Override settings in a server list file
        $serverList = $this->yamlParser->parse($contents);
        $projectAttributes = $this->project->attributes;
        if (!is_null($projectAttributes)) {
            foreach ($serverList as $i => $server) {
                if (!is_null($projectAttributes->getDeployPath())) {
                    $serverList[$i]['deploy_path'] = $projectAttributes->getDeployPath();
                }
            }
        }
        $newContents = 'hosts: ' . $this->yamlDumper->dump($serverList);
        $this->fs->put($fullPath, $newContents);
        $this->deployerFileInitialized = true;
        return $this;
    }

    /**
     * Get a server list file instance.
     *
     * @return DeployerFile
     */
    public function getResult()
    {
        return $this->deployerFile;
    }

    /**
     * Set a server model instance.
     *
     * @param Model $server
     * @return DeployerServerListFileBuilder $this
     */
    public function setServer(Model $server)
    {
        $this->server = $server;
        return $this;
    }

    /**
     * Set a project model instance.
     *
     * @param Model $project
     * @return DeployerServerListFileBuilder $this
     */
    public function setProject(Model $project)
    {
        $this->project = $project;
        return $this;
    }
}
