<?php

namespace App\Model\ActionQueue;

/**
* 
*/
interface ActionCommandInterface
{
    /**
     * Run worker action class to produce results.
     */
    public function execute();
}
