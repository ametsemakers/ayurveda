<?php
// Un test simple d'utilisation d'un service

namespace AM\AdminBundle\Antispam;

class AMAntispam
{
    /**
    * Vérifie si le texte est un spam ou non
    *
    * @param string $text
    * @return bool
    */
    public function isSpam($text)
    {
        return strlen($text) < 50;
    }
}