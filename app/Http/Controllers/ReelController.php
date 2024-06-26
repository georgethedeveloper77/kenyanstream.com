<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class ReelController extends Controller
{
    public function index()
    {
        $reels = Media::where('is_reel', true)->latest()->paginate(10);
        return view('reels.index', compact('reels'));
    }

    public function show($id)
    {
        $reel = Media::where('is_reel', true)->where('id', $id)->firstOrFail();
        return view('reels.show', compact('reel'));
    }
}