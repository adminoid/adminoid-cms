<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use Auth;

class ReviewsController extends Controller
{

    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|max:5000',
        ]);

        $parent = Page::where('template', 'pages.reviews')->first();
        $reviewsTotal= $parent->children()->count();
        $reviewData = [];
        $reviewData['content'] = strip_tags($request->get('content'));
        $reviewData['slug'] = $reviewsTotal;
        $user = Auth::user();
        $userName = $user->name;
        $reviewData['name'] = $userName;
        $reviewData['title'] = 'Отзыв о центре';
        $reviewData['show_in_main_menu'] = 0;
        $newReview = Page::create($reviewData);
        $newReview->appendToNode($parent)->save();
        $user->pages()->save($newReview);
        $date = date('d.m.Y', strtotime($newReview->created_at));
        return response()->json(['msg' => 'Отзыв создан', 'review' => [
            'name' => $userName,
            'created_at' => $date,
            'content' => $newReview->paragraphedContent(),
        ]], 200);
    }

}
