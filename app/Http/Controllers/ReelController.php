<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class ReelController extends Controller
{
    public function index()
    {
        // Fetch video posts marked as reels
        $reels = Post::where('type', 'video')->where('is_reel', true)->orderBy('created_at', 'desc')->paginate(10);

        return view('reels.index', compact('reels'));
    }
}