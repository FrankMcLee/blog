<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 01/02/2017
 * Time: 2:09 PM
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class UsersController extends Controller
{
    public function create()
    {
        return view('users.create');
    }
}
