<?php

declare(strict_types=1);

namespace Owncloud;

class Client extends \GuzzleHttp\Client
{
    public function __construct(array $config = [])
    {
        $config['defaults']['query'] = ['format' => 'json'];
        parent::__construct($config);
    }

    public function get($uri = null, array $options = []) : Response
    {
        return new Response(parent::get($uri, $options)->getBody());
    }

    public function post($uri = null, array $options = []) : Response
    {
        return new Response(parent::post($uri, $options)->getBody());
    }

    public function delete($uri = null, array $options = []) : Response
    {
        return new Response(parent::delete($uri, $options)->getBody());
    }
}
