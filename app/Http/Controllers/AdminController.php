<?php

namespace App\Http\Controllers;
use App\Role;
use App\Source;

use Illuminate\Http\Request;

class AdminController extends Controller
{
     public function __invoke()
    {
        return view('admin', ['roles' => Role::All(), 'sources' => Source::All()]);
    }
}
