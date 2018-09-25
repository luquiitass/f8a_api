<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 23/06/17
 * Time: 14:06
 */

namespace App\Exceptions;


use App\Models\Util\ReturnJSON;
use League\Flysystem\Exception;

class ExepcionValidaciones  extends Exception
{
    private $mensaje;
    // Redefinir la excepción, por lo que el mensaje no es opcional
    public function __construct($message, $code = 0, Exception $previous = null) {
        // algo de código
        $this->mensaje = $message;
        // asegúrese de que todo está asignado apropiadamente
        parent::__construct($message, $code, $previous);
    }

    // representación de cadena personalizada del objeto
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }


    /**
     *
     */

    public function get(){
        return ReturnJSON::errorValidaciones($this->getMessage());
    }
}