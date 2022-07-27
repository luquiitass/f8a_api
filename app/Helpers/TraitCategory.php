<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 21/09/18
 * Time: 17:27
 */

namespace App\Helpers;

use App\Models\Category;
use Auth;
use Exception;

trait TraitCategory
{

    public function getCategoryId() : int{
        $user = Auth::guard('api')->user();

        if(!empty($user) && !empty($user->category)){
            return $user->category->id;
        }

        $category = Category::first();
        if(! empty($category)){
            return $category->id;
        }
        throw new Exception('No existen categorías registradas, por favor registre al menos una.');
        
    }

}