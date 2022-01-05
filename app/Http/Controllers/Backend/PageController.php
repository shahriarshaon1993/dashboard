<?php

namespace App\Http\Controllers\Backend;

use Throwable;
use App\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Interface\Backend\PageInterface;
use App\Http\Requests\Backend\StorePageRequest;
use App\Http\Requests\Backend\UpdatePageRequest;
use Illuminate\Support\Facades\Request;

class PageController extends Controller
{
    public $pageRepo;

    public function __construct(PageInterface $pageRepo)
    {
        $this->pageRepo = $pageRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('admin.pages.access');
        $pages = Page::latest('id')->get();
        return view('backend.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('admin.pages.create');
        return view('backend.pages.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePageRequest $request)
    {
        Gate::authorize('admin.pages.create');
        try {
            $this->pageRepo->storeUpdatePageInfo($request);
            notify()->success("Page Successfully Added.","Added","topCenter");
            return back();
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }
    }

    /**
     * For upload text editor image
     *
     * @return void
     */
    public function storeImage()
    {
        Gate::authorize('admin.pages.create');
        $page = new Page();
        $page->id = 0;
        $page->exists = true;

        $image = $page
                    ->addMediaFromRequest('upload')
                    ->toMediaCollection('page-image');

        return response()->json([
            'url' => $image->getUrl() //'https://i.ibb.co/PNvkdNF/original.jpg'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        Gate::authorize('admin.pages.edit');
        return view('backend.pages.form', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        Gate::authorize('admin.pages.edit');
        try {
            $this->pageRepo->storeUpdatePageInfo($request, $page);
            notify()->success("Page successfully updated.","Updated","topCenter");
            return redirect()->route('admin.pages.edit', $page->slug);
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        Gate::authorize('admin.pages.destroy');
        try {
            $page->delete();
            notify()->success("Page deleted","Deleted","topCenter");
            return back();
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }
    }

}