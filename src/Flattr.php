<?php

namespace FlattrMail;

use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;

class Flattr
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getFlattrs()
    {
        $url = 'https://api.flattr.com/rest/v2/users/' . $this->user . '/flattrs';
        $client = new Client;
        $client->setAdapter(new Curl);
        $client->setUri($url);
        $response = $client->send();
        if (!$response->isSuccess()) {
            throw new Exception('Error requesting flattr api: ' . $response->getBody(), 1);
        }
        $body = $response->getBody();
        return json_decode($body, true);
    }

    public function getFlattrsFromCache()
    {
        $body = file_get_contents('flattrs');
        return json_decode($body, true);
    }

    public function getFlattrsThisMonth(array $flattrs = null)
    {
        if ($flattrs === null) {
            $flattrs = $this->getFlattrs();
        }
        $flattrsThisMonth = [];
        $firstOfMonth = strtotime(date('1.m.Y'));
        foreach ($flattrs as $flattr) {
            if ($flattr['created_at'] > $firstOfMonth) {
                $flattrsThisMonth[] = $flattr;
            }
        }
        return $flattrsThisMonth;
    }
}
