<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use Illuminate\View\View;

class ExportsController extends Controller
{
    //
    public function sitemap()
    {
        $pages = Page::get();
        return response()
            ->view('exports/sitemap-xml', compact('pages'), 200)
            ->header('Content-Type', 'text/xml');
    }
}
