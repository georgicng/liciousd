<?php

namespace Gaiproject\Theme\Http\Controllers\Admin;

use Gaiproject\Theme\Http\Controllers\Controller;
use Gaiproject\Theme\Service\MenuService;
use Webkul\CMS\Repositories\PageRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected MenuService $menuService,
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
        $data = $this->menuService->getMenus();

        $categories = $this->menuService->getCategories($this->categoryRepository, [
            'status' => 1,
            'locale' => app()->getLocale(),
            'limit' => 30
        ]);
        $pages = $this->menuService->getPages($this->pageRepository, $data['menus']);

        return view(
            'licious::admin.settings.menu.index',
            compact(
                'data',
                'pages',
                'categories'
            )
        );
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
        $payload = ['menus' => json_decode(request('data')['menus']), 'custom' => json_decode(request('data')['custom'])];
        $this->menuService->saveMenus($payload);
        session()->flash('success', trans('admin::app.settings.menu.create-success'));
        return redirect()->route('admin.settings.menu.index');
    }
}
