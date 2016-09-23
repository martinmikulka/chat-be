<?php

namespace App\Model;

use Nette;

/**
 * Api
 */
class Api extends Nette\Object
{
    private $apiKey;


    public function getApiKey()
    {
        return $this->apiKey;
    }


    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }
}
