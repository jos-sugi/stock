<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StocksController extends Controller
{
    public function index()
    {
        $data = [];
        if(\Auth::check()) {
            $user = \Auth::user();
            $stocks = $user->stocks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'stocks' => $stocks,
            ];
        }
        return view('welcome', $data);

    }
    public function store(Request $request)
    {

        $request->validate([
            'content' => 'required|max:255',
        ]);

        $request->user()->stocks()->create([
            'content' => $request->content,
        ]);

        return back();
    }
    public function destroy($id)
    {
        $stock = \App\stock::findOrFail($id);

        if (\Auth::id() === $stock->user_id) {
            $stock->delete();
        }
        
        return back();
    }
}
