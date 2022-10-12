<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function admin()
    {

        return inertia('Admin/Dashboard');
    }
    public function tamu()
    {
        return inertia('Student/Dashboard');
    }
}
