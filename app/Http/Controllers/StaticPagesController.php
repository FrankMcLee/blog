<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 25/01/2017
 * Time: 8:37 PM
 */

namespace app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaticPagesController extends Controller
{
    public function home()
    {
        $feedItems = [];
        if (Auth::check()) {
            $feedItems = Auth::user()->feed()
                ->paginate(30);
        }

        return view('static_pages/home', compact('feedItems'));
    }

    public function help()
    {
        return view('static_pages/help');
    }

    public function about()
    {
        return view('static_pages/about');
    }
}