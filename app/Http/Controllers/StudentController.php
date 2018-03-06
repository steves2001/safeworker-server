<?php

namespace App\Http\Controllers;
use App\Role;
use App\Source;

use Illuminate\Http\Request;

class StudentController extends Controller
{
     public function __invoke()
    {
        return view('student', ['roles' => Role::All(), 'sources' => Source::All()]);
    }
}
