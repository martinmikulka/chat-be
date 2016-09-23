<?php

namespace App\Exceptions;

/**
 * Tato zakladni trida dedi z \PDOEcxeption z duvodu, ze PDOException ma implementovany 
 * mechanismus pro nastavovani i neciselnych hodnot do parametru code. Tuto funkcionalitu
 * vyuzivame pri vyhazovani vyjimek pri validaci hodnot z formularovych prvku. Diky
 * moznosti nastavit String muzeme vyjimku adresovat konkretnimu prvku formulare a chybu
 * vypsat primo u daneho prvku.
 *
 * @author Martin Mikulka
 */
class BaseException extends \PDOException
{


    public function __construct($message = null, $code = null)
    {
        $this->message = $message;
        $this->code = $code;
    }


}