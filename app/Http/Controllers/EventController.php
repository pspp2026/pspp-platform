<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event; // 👈 สำคัญ ต้องมีบรรทัดนี้

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('calendar', compact('events'));
    }

    public function store(Request $request)
    {
        Event::create($request->all());
        return back();
    }
}