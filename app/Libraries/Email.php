<?php

namespace App\Libraries;

class Email
{
    public static function SendEmail(Array $params): Bool
    {
        $email = service('email');

        $email->setTo($params['to']);
        $email->setFrom(config('Application')->admin_email, config('Application')->application_name);
        
        $email->setSubject($params['subject']);

        $message = view('backend/template/' . $params['view'], $params);

        $email->setMessage($message);

        if ($email->send()): 
            return true;
        endif; 
        
        return false;

        // print_r($email->printDebugger(['headers'])); die(); 
    }
}