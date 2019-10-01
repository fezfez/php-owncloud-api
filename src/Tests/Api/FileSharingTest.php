<?php

declare(strict_types=1);

namespace Owncloud\Tests\Api;

use GuzzleHttp\Adapter\MockAdapter;
use GuzzleHttp\Adapter\TransactionInterface;
use GuzzleHttp\Message\Response;
use Owncloud\Api;
use PHPUnit\Framework\TestCase;

class FileSharingTest extends TestCase
{
    /** @var Api */
    protected $api;

    public function setUp() : void
    {
        $mockAdapter = new MockAdapter(static function (TransactionInterface $trans) {
            // You have access to the request
            $request = $trans->getRequest();
            // Return a response
            return new Response(200);
        });

        $this->api = new Api('http://demo.com', ['foo', 'bar'], ['adapter' => $mockAdapter]);
    }

    public function testDeleteShare() : void
    {
        $fileSharing = $this->api->fileSharing();

        //$response = $fileSharing->deleteShare(1);
    }
}
