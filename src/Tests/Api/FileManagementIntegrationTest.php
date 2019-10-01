<?php

declare(strict_types=1);

namespace Owncloud\Tests\Api;

use Owncloud\Api\FileManagement;
use PHPUnit\Framework\TestCase;
use function getenv;
use function rand;

/**
 * @coversDefaultClass FileManagement
 */
class FileManagementIntegrationTest extends TestCase
{
    /** @var FileManagement  */
    protected $api;

    public function setUp() : void
    {
        $this->api = new FileManagement($_SERVER['owncloud_host'], $_SERVER['owncloud_user'], $_SERVER['owncloud_password']);
    }

    /**
     * @group internet
     * @covers                   FileManagement::update
     */
    public function testRead() : void
    {
        $filename = $this->getTestFilename();
        $expected = 'Example random number :' . rand(0, 15000);

        $this->api->update($filename, $expected);

        $this->assertEquals($expected, $this->api->read($filename));
    }

    /**
     * @group internet
     * @covers                   FileManagement::write
     */
    public function testWrite() : void
    {
        $filename = $this->getTestFilename();
        $expected = 'Example random number :' . rand(0, 15000);

        $this->api->write($filename, $expected);
        $this->assertEquals($expected, $this->api->read($filename));
    }

    /**
     * @group internet
     * @covers                   FileManagement::update
     */
    public function testUpdate() : void
    {
        $filename = $this->getTestFilename();
        $expected = 'Example random number :' . rand(0, 15000);

        $this->api->update($filename, $expected);

        $this->assertEquals($expected, $this->api->read($filename));
    }

    public function getTestFilename()
    {
        return 'php-owncloud-api/test_file_management.txt';
    }
}
