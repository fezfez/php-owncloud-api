<?php

declare(strict_types=1);

namespace Owncloud\Tests\Api;

use Owncloud\Api;
use PHPUnit\Framework\TestCase;
use function getenv;
use function rand;

/**
 * @coversDefaultClass Api\FileManagement
 */
class FileManagementIntegrationTest extends TestCase
{
    /** @var Api  */
    protected $api;

    public function setUp() : void
    {
        if (getenv('owncloud_host') !== '') {
            $this->api = new Api(getenv('owncloud_host'), getenv('owncloud_user'), getenv('owncloud_password'));
        } else {
            $this->api = new Api($_SERVER['owncloud_host'], $_SERVER['owncloud_user'], $_SERVER['owncloud_password']);
        }
    }

    /**
     * @group internet
     * @covers                   Api\FileManagement::update
     */
    public function testRead() : void
    {
        $filename = $this->getTestFilename();
        $expected = 'Example random number :' . rand(0, 15000);

        $this->api->fileManagement()->update($filename, $expected);
        $actual = $this->api->fileManagement()->read($filename);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @group internet
     * @covers                   Api\FileManagement::write
     */
    public function testWrite() : void
    {
        $filename = $this->getTestFilename();
        $expected = 'Example random number :' . rand(0, 15000);

        $this->api->fileManagement()->write($filename, $expected);
        $actual = $this->api->fileManagement()->read($filename);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @group internet
     * @covers                   Api\FileManagement::update
     */
    public function testUpdate() : void
    {
        $filename = $this->getTestFilename();
        $expected = 'Example random number :' . rand(0, 15000);

        $this->api->fileManagement()->update($filename, $expected);
        $actual = $this->api->fileManagement()->read($filename);

        $this->assertEquals($expected, $actual);
    }

    public function getTestFilename()
    {
        return 'php-owncloud-api/test_file_management.txt';
    }
}
