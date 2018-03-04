<?php

namespace App\Http\Controllers;
use App\Role;
use App\Source;

use Illuminate\Http\Request;

class SecurityController extends Controller
{
     public function __invoke()
    {
        return view('security', ['roles' => Role::All(), 'sources' => Source::All()]);
    }
}
