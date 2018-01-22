<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployer;

use Ngmy\Webloyer\Common\Filesystem\FilesystemInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileBuilderInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Symfony\Component\Yaml\Dumper as YamlDumper;
use Symfony\Component\Yaml\Parser as YamlParser;

class DeployerServerListFileBuilder implements DeployerFileBuilderInterface
{
    private $fs;

    private $deployerFile;

    private $yamlParser;

    private $yamlDumper;

    private $server;

    private $project;

    /**
     * Create a new builder instance.
     *
     * @param \Ngmy\Webloyer\Common\Filesystem\FilesystemInterface       $fs
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile $deployerFile
     * @param \Symfony\Component\Yaml\Parser                             $yamlParser
     * @param \Symfony\Component\Yaml\Dumper                             $yamlDumper
     * @return void
     */
    public function __construct(FilesystemInterface $fs, DeployerFile $deployerFile, YamlParser $yamlParser, YamlDumper $yamlDumper)
    {
        $this->fs           = $fs;
        $this->deployerFile = $deployerFile;
        $this->yamlParser   = $yamlParser;
        $this->yamlDumper   = $yamlDumper;
    }

    /**
     * Delete a server list file.
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        $this->fs->delete($this->deployerFile->getFullPath());
    }

    /**
     * Set a server list file path info.
     *
     * @return $this
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
     * @return $this
     */
    public function put()
    {
        $fullPath = $this->deployerFile->getFullPath();
        $contents = $this->server->body();

        // Override settings in a server list file
        $serverList = $this->yamlParser->parse($contents);
        $projectAttribute = $this->project->attribute();
        if (!is_null($projectAttribute)) {
            foreach ($serverList as $i => $server) {
                if (!is_null($projectAttribute->deployPath())) {
                    $serverList[$i]['deploy_path'] = $projectAttribute->deployPath();
                }
            }
        }
        $newContents = $this->yamlDumper->dump($serverList);

        $this->fs->put($fullPath, $newContents);

        return $this;
    }

    /**
     * Get a server list file instance.
     *
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile
     */
    public function getResult()
    {
        return $this->deployerFile;
    }

    /**
     * Set a server model instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server $server
     * @return $this
     */
    public function setServer(Server $server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Set a project model instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project
     * @return $this
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }
}
