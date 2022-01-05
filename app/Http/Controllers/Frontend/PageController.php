<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * Show single page content
     *
     * @param  mixed $slug
     * @return void
     */
    public function index($slug)
    {
        $page = Page::findBySlug($slug);
        return $page;
    }
}
