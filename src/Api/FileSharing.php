<?php

declare(strict_types=1);

namespace Owncloud\Api;

use Owncloud\Client;
use Owncloud\ResponseException;

class FileSharing
{
    /** @var Client  */
    private $client;
    /** @var bool  */
    private $debug = false;
    /** @var int  */
    private $version = 1;

    public const SHARE_TYPE_USER        = 0;
    public const SHARE_TYPE_GROUP       = 2;
    public const SHARE_TYPE_PUBLIC_LINK = 3;

    public const SHARE_PERMISSION_READONLY = 1;
    public const SHARE_PERMISSION_UPDATE   = 2;
    public const SHARE_PERMISSION_CREATE   = 4;
    public const SHARE_PERMISSION_DELETE   = 8;
    public const SHARE_PERMISSION_RESHARE  = 16;
    public const SHARE_PERMISSION_PRIVATE  = 31;


    public function __construct(Client $client, bool $debug = false)
    {
        $this->client = $client;
        $this->debug  = $debug;
    }

    public function getAllShares()
    {
        $response = $this->getClient()->get(
            $this->getFileSharingRestUrl(),
            ['debug' => $this->debug]
        );

        return $response->getData();
    }

    public function getShare($shareId)
    {
        $response = $this->getClient()->get(
            "{$this->getFileSharingRestUrl()}/{$shareId}",
            ['debug' => $this->debug]
        );

        $data = $response->getData();
        if (! isset($data['element'])) {
            throw new ResponseException('No element on response');
        }

        return $data['element'];
    }

    public function createNewShare($path, $options)
    {
        $options['path'] = $path;
        $response        = $this->getClient()->post(
            $this->getFileSharingRestUrl(),
            ['body' => $options, 'debug' => $this->debug]
        );

        return $response->getData();
    }

    public function deleteShare($shareId)
    {
        $response = $this->getClient()->delete(
            "{$this->getFileSharingRestUrl()}/{$shareId}",
            ['debug' => $this->debug]
        );
        return $response->getData();
    }

    public function setDebug($debug = true) : void
    {
        $this->debug = $debug;
    }

    public function setVersion($version) : void
    {
        $this->version = $version;
    }

    public function getVersion() : int
    {
        return $this->version;
    }

    private function getFileSharingRestUrl() : string
    {
        return "ocs/v1.php/apps/files_sharing/api/v{$this->version}/shares";
    }

    private function getClient() : Client
    {
        if (! isset($this->client)) {
            throw new ResponseException('The REST client is not set.');
        }
        return $this->client;
    }
}
