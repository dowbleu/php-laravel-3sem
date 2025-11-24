<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index() {
        $articles = json_decode(file_get_contents(public_path().'/articles.json'));
        return view('main.atricle', ['articles'=>$articles]); 
    }

    public function show($img) {
        return view('main.gallery', ['img'=>$img]);
    }
}
