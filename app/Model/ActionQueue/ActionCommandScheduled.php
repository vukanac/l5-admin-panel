<?php

namespace App\Model\ActionQueue;

use Log;

class ActionCommandScheduled
{

    public static function run()
    {
    	Log::info('Schedule run start.');

        $client = new ActionCommandClient();
        $result = $client->run();

        Log::info('Schedule result.', $result);
        Log::info('Schedule run finish.');

        return json_encode($result);
    }
}
