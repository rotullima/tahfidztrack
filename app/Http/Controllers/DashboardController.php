<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data programs untuk dashboard, bukan redirect
        $programs = Program::all();
        return view('admin.dashboard', compact('programs'));
    }
}