<?php

namespace Gaiproject\Theme\Http\Controllers\Admin;

use Gaiproject\Theme\Http\Controllers\Controller;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\CMS\Repositories\PageRepository;
use Webkul\Category\Repositories\CategoryRepository;

class MenuController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CoreConfigRepository $coreConfigRepository,
        protected PageRepository $pageRepository,
        protected CategoryRepository $categoryRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = core()->getConfigData('store.information.menu.data') ?? ['custom' => [], 'menus' => [[ 'name' => 'Root', 'key' => 'root', 'children' => [] ]]];
        $categories = $this->categoryRepository->getAll([
            'status' => 1,
            'locale' => app()->getLocale(),
            'limit' => 30
        ])->map(function ($category) {
            return [
                'key' => 'category_' . $category->id,
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'url' => $category->url,
                'type' => 'category',
                'children' => [],
            ];
        })->toArray();
        $pages = $this->pageRepository->all()->map(function ($page) {
            return [
                'key' => 'page_' . $page->id,
                'id' => $page->id,
                'name' => $page->page_title,
                'slug' => $page->url_key,
                'url' => route('shop.cms.page', $page->url_key),
                'type' => 'page',
                'children' => [],
            ];
        })->toArray();
        return view('licious::admin.settings.menu.index', compact('data', 'categories', 'pages'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'data'    => 'required',
        ]);

        $data = request()->all();
        $this->coreConfigRepository->create($data);

        session()->flash('success', trans('admin::app.settings.menu.create-success'));

        return redirect()->route('admin::settings.menu.index');
    }
}
