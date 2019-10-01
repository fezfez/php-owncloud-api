<?php

declare(strict_types=1);

namespace Owncloud;

class Api
{
    /** @var Client */
    private $client;
    /** @var string */
    private $host;
    /** @var string */
    private $username;
    /** @var string */
    private $password;

    public function __construct(string $host, string $username, string $password, array $config = [])
    {
        $this->host     = $host;
        $this->username = $username;
        $this->password = $password;

        $config['base_url']         = $host;
        $config['defaults']['auth'] = [$username, $password];
        $this->client               = new Client($config);
    }

    public function fileSharing() : Api\FileSharing
    {
        return new Api\FileSharing($this->client);
    }

    public function fileManagement() : Api\FileManagement
    {
        return new Api\FileManagement($this->host, $this->username, $this->password);
    }
}
