<?php

namespace App\Services\Deployment;

use App\Services\Deployment\DeployerFile;
use App\Services\Filesystem\FilesystemInterface;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;

class DeployerServerListFileBuilder implements DeployerFileBuilderInterface
{
    protected $fs;

    protected $deployerFile;

    protected $yamlParser;

    protected $yamlDumper;

    protected $server;

    protected $project;

    public function __construct(FilesystemInterface $fs, DeployerFile $deployerFile, Parser $parser, Dumper $dumper)
    {
        $this->fs           = $fs;
        $this->deployerFile = $deployerFile;
        $this->yamlParser   = $parser;
        $this->yamlDumper   = $dumper;
    }

    public function __destruct()
    {
        $this->fs->delete($this->deployerFile->getFullPath());
    }

    /**
     * Set a server list file path info.
     *
     * @return \App\Services\ServerList\DeployerServerListFileBuilder $this
     */
    public function pathInfo()
    {
        $id = md5(uniqid(rand(), true));

        $baseName = "server_$id.yml";
        $fullPath = storage_path("app/$baseName");

        $this->deployerFile->setBaseName($baseName);
        $this->deployerFile->setFullPath($fullPath);

        return $this;
    }

    /**
     * Put a server list file.
     *
     * @return \App\Services\ServerList\DeployerServerListFileBuilder $this
     */
    public function put()
    {
        $fullPath = $this->deployerFile->getFullPath();
        $contents = $this->server->body;

        // Override settings in a server list file
        $serverList = $this->yamlParser->parse($contents);
        $projectAttributes = $this->project->getProjectAttributes();
        foreach ($projectAttributes as $projectAttribute) {
            foreach ($serverList as $i => $server) {
                $serverList[$i][$projectAttribute->name] = $projectAttribute->value;
            }
        }
        $newContents = $this->yamlDumper->dump($serverList);

        $this->fs->put($fullPath, $newContents);

        return $this;
    }

    /**
     * Get a server list file instance.
     *
     * @return \App\Services\Deployment\DeployerFile
     */
    public function getResult()
    {
        return $this->deployerFile;
    }

    /**
     * Set a server model instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $server
     * @return \App\Services\ServerList\DeployerServerListFileBuilder $this
     */
    public function setServer(Model $server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Set a project model instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $project
     * @return \App\Services\ServerList\DeployerServerListFileBuilder $this
     */
    public function setProject(Model $project)
    {
        $this->project = $project;

        return $this;
    }
}
