<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Page;
use App\PageOldUri;

class PagesController extends Controller
{

    public function show($path = '/')
    {
        if ($oldUri = PageOldUri::where('uri', '=', $path)->first()) {
            $actualUri = $oldUri->page->uri;
            return redirect($actualUri, 301);
        }
        $page = Page::where('uri', '=', $path)->firstOrFail();
        $roots = Page::whereNull('parent_id')->orderBy('_lft')->get();
        switch ($page->slug) {
            case 'reviews':
                return $this->showReviews($page, $roots);
            case 'news':
                return $this->showNews($page, $roots);
            default:
                return $this->showStandard($page, $roots);
        }
    }

    public function showStandard($page, $roots)
    {
        return view($page->template, compact('page', 'roots'));
    }

    public function showReviews($page, $roots)
    {
        $reviews = $page->children()->get()->sortByDesc('created_at');
        return view($page->template, compact('page', 'reviews', 'roots'));
    }

    public function showNews($page, $roots)
    {
        $news = $page->children()->get()->sortByDesc('created_at');
        return view($page->template, compact('page', 'news', 'roots'));
    }
}
