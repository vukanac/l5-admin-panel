<?php

namespace App\Model\ActionQueue;


class ActionCommandScheduled
{

    public static function run()
    {
        $client = new ActionCommandClient();
        $result = $client->run();

        return json_encode($result);
    }
}
