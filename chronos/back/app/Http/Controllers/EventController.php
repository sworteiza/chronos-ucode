<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Events;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function create_event(Request $request)
    {
        $input = $request->validate([
            'title' => 'required|string',
            'start' => 'required | date_format:"Y-m-d H:i:s"|after:"2015-01-01 00:00:00"',
            'end'   => 'required | date_format:"Y-m-d H:i:s"|after:start',
            'category' => 'required|string'
        ]);

        $event = Events::create([
            'title' => $input['title'],
            'category' => $input['category'],
            'start' => $input['start'],
            'end' => $input['end'],
            'user_login' => Auth::user()->login
        ]);

        $response = [
            'event' => $event
        ];

        return response($response, 201);
    }

    public function destroy($title)
    {
        return Events::destroy(Events::where('title', $title)->get());
    }

    public function index()
    {
        return DB::table('events')
            ->select('title', 'category as className', 'start', 'end')
            ->where('user_login', Auth::user()->login)
            ->get();
    }

    public function get_event($title)
    {
        return  Events::where('title', $title)->get();
    }
}
