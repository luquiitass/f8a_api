<?php

namespace App\Models;

use App\Http\Requests\LoginRequest;
use App\Models\Util\ReturnJSON;
use App\User as AppUser;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use Response;

/**
 * Class Cliente
 */
class User extends AppUser
{

    protected $table = 'users';




}