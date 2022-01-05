<?php

namespace App\Repositories\Backend;

use App\Models\Page;
use Illuminate\Support\Str;
use App\Interface\Backend\PageInterface;

class PageRepository implements PageInterface
{
    /**
     * Store page information in database
     *
     * @param  mixed $request
     * @return void
     */
    public function storeUpdatePageInfo($request, $page = null)
    {
        if($page) {
            $page->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'excerpt' => $request->excerpt,
                'body' => $request->body,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'status' => $request->filled('status')
            ]);

            // upload images
            if ($request->hasFile('featured_image')) {
                $fileName = rand() . time() . '.' . $request->file('featured_image')->extension();
                $page
                    ->addMedia($request->featured_image)
                    ->usingFileName($fileName)
                    ->toMediaCollection('featured_image');
            }
        }else {
            $page = Page::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'excerpt' => $request->excerpt,
                'body' => $request->body,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'status' => $request->filled('status')
            ]);

            // upload images
            if ($request->hasFile('featured_image')) {
                $fileName = rand() . time() . '.' . $request->file('featured_image')->extension();
                $page
                    ->addMedia($request->featured_image)
                    ->usingFileName($fileName)
                    ->toMediaCollection('featured_image');
            }
        }
    }
}
