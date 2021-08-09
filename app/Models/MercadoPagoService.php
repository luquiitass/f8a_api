<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use MercadoPago;
use MercadoPago\MerchantOrder;

/**
 * Class Cliente
 */
class MercadoPagoService extends Model
{

    protected $table = 'categorias';
    
    private $token = "TEST-8895056597439462-080214-7a0c9306365026e759c20afaea17f51f-176099545";

    public $timestamps = true;

    protected $fillable = [
        'nombre'
    ];


    protected $guarded = [];


    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);


        return $model;
    }


    public function createPreference(){
        //require_once public_path('../vendor/autoload.php');

        MercadoPago\SDK::setAccessToken(config('services.mp.private_key_test'));

        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();

        // Crea un ítem en la preferencia
        $item = new MercadoPago\Item();
        $item->title = 'Registro de Equipo';
        $item->quantity = 1;
        $item->unit_price = 500;
        $item->currency_id = "ARS";
        $preference->items = array($item);

        $preference->back_urls = array(
            "success" => "https://futbol8alem.com/#/payment/success",
            "failure" => "https://futbol8alem.com/#/payment/failure",
            "pending" => "https://futbol8alem.com/#/payment/pending"
        );
        $preference->auto_return = "approved";

        $preference->save();
    
        return $preference->id;
    }

        
}