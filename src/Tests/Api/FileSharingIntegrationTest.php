<?php

declare(strict_types=1);

namespace Owncloud\Tests\Api;

use Owncloud\Api;
use Owncloud\Api\FileSharing;
use PHPUnit\Framework\TestCase;
use function count;
use function getenv;

/**
 * @coversDefaultClass Api\FileSharing
 */
class FileSharingIntegrationTest extends TestCase
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
        $this->api->fileSharing()->setDebug(true);
    }

    public function createAndGetShareId($filename)
    {
        // In case of integration test, de file test.txt should existe in the root of the filesystem
        $response = $this->api->fileSharing()->createNewShare($filename, ['shareType' => FileSharing::SHARE_TYPE_PUBLIC_LINK]);
        return $response['id'];
    }

    /**
     * @group internet
     * @covers                   Api\FileSharing::createNewShare
     */
    public function testCreateNewShare() : void
    {
        $filename = $this->getTestFilename();
        $response = $this->api->fileSharing()->createNewShare($filename, ['shareType' => FileSharing::SHARE_TYPE_PUBLIC_LINK]);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('url', $response);
        $this->assertArrayHasKey('token', $response);
        $this->assertCount(3, $response);
    }

    /**
     * @group internet
     * @covers                   Api\FileSharing::createNewShare
     * @expectedException        Owncloud\ResponseException
     */
    public function testCreateNewShareWithIncorrectDirectoryOrFileShouldFail() : void
    {
        $this->api->fileSharing()->createNewShare('non/existing/path', ['shareType' => FileSharing::SHARE_TYPE_PUBLIC_LINK]);
    }

    /**
     * @group internet
     * @covers                   Api\FileSharing::getShare
     */
    public function testGetShare() : void
    {
        $filename = $this->getTestFilename();
        $shareId  = $this->createAndGetShareId($filename);

        $response = $this->api->fileSharing()->getShare($shareId);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('path', $response);
        $this->assertArrayHasKey('token', $response);
        $this->assertArrayHasKey('item_type', $response);
    }

    /**
     * @group internet
     * @covers                   Api\FileSharing::getShare
     * @expectedException        Owncloud\ResponseException
     */
    public function testGetShareWithWrongShareIdShouldFail() : void
    {
        $this->api->fileSharing()->getShare(0);
    }

    /**
     * @group internet
     * @covers                   Api\FileSharing::getAllShares
     */
    public function testGetAllShares() : void
    {
        $filename = $this->getTestFilename();
        $this->createAndGetShareId($filename); // We create at least one.

        $response = $this->api->fileSharing()->getAllShares();

        $this->assertGreaterThan(0, count($response));
    }

    /**
     * @group internet
     * @covers                   Api\FileSharing::deleteShare
     */
    public function testDeleteShare() : void
    {
        $filename = $this->getTestFilename();
        $shareId  = $this->createAndGetShareId($filename); // We create at least one.

        $response = $this->api->fileSharing()->deleteShare($shareId);
        $this->assertCount(0, $response);
    }

    /**
     * @group internet
     * @covers                   Api\FileSharing::deleteShare
     * @expectedException        Owncloud\ResponseException
     */
    public function testDeleteShareWithWrongShareIdShouldFail() : void
    {
        $fileSharing = $this->api->fileSharing();

        $response = $fileSharing->deleteShare(10000);
    }

    public function getTestFilename()
    {
        return 'php-owncloud-api/existing_file.txt';
    }
}
