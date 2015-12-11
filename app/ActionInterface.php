<?php

namespace App;

interface ActionInterface
{
    /**
     * Run worker action class to produce results.
     */
    public function run();
}
