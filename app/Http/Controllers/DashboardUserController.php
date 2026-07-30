<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Structures;
use Illuminate\Http\Request;

class DashboardUserController extends Controller
{
    public function index()
    {
        $structures = Structures::all();
        $agenda = Event::all();
        return view('dashboardUser', compact('structures','agenda'));
    }
}
