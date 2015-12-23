<?php

namespace App\Model\ActionQueue;

use Mail;

/**
 * 
 */
class ActionCommandMailReceiver
{

    /**
     * @return The return value is the number of recipients who were accepted for
     * delivery.
     */
    public function methodToInvoke($params)
    {
        // check does all parameters are sent
        $requiredParams = [ 'template', 'data', 'mailFromEmail', 'mailFromName',
                            'mailToEmail', 'mailToName', 'mailSubject'];
        foreach ($requiredParams as $key) {
            if(!array_key_exists($key, $params)) {
                throw new \Exception('Required parameter missing in MailReceiver ('.$key.').');
            }
        }

        $numberEmailsSent = 
            Mail::send(
                $params['template'],
                $params['data'],
                function ($m) use ($params) {
                    $m->from($params['mailFromEmail'], $params['mailFromName']);
                    $m->to($params['mailToEmail'], $params['mailToName']);
                    $m->subject($params['mailSubject']);
                });

        if (empty($numberEmailsSent)) {
            throw new \Exception('No emails have been sent!');
        }
        return $numberEmailsSent;
    }
}
