<?php

declare(strict_types=1);

namespace Owncloud\Api;

use League\Flysystem\Filesystem;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client;

class FileManagement extends Filesystem
{
    public function __construct(string $host, string $username, string $password, array $settings = [])
    {
        $settings['baseUri']  = $host;
        $settings['userName'] = $username;
        $settings['password'] = $password;

        $client  = new Client($settings);
        $adapter = new WebDAVAdapter($client, 'remote.php/webdav/');

        parent::__construct($adapter);
    }
}
