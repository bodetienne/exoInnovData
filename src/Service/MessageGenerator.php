<?php

// src/Service/MessageGenerator.php
namespace App\Service;

class MessageGenerator
{
    public function getMessage($index)
    {
        $messages = [
            'Votre compte à été créé',
            'Votre compte a été modifié',
            'Votre compte a été supprimé',
        ];


        //$index = array_rand($messages);


        return $messages[$index];
    }
}

?>
