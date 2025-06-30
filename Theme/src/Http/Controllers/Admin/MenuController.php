<?php

namespace Gaiproject\Theme\Http\Controllers\Admin;

use Gaiproject\Theme\Http\Controllers\Controller;
use Webkul\Core\Repositories\CoreConfigRepository;
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
        //$menuData = core()->getConfigData('store.information.menu.data');
        $data = Storage::json('menus.json');
        if (!$data) {
            $data = ['custom' => [], 'menus' => []];
        }

        $Ids = ['category' => [], 'page' => []];
        $this->getIds($data['menus'], $Ids);
        logger()->channel('custom')->info(json_encode(['ids' => $Ids]));

        $categories = $this->categoryRepository->getAll([
            'status' => 1,
            'locale' => app()->getLocale(),
            'limit' => 30
        ])
            ->map(fn($category) => [
                'key' => 'category_' . $category->id,
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'url' => $category->url,
                'type' => 'category',
                'children' => [],
            ])
            //->filter(fn($item) => ! in_array($item['id'], $Ids['category'] ?? []))
            ->toArray();
        $pages = $this->pageRepository->findWhereNotIn('id', $Ids['page'])
            ->map(fn($page) => [
                'key' => 'page_' . $page->id,
                'id' => $page->id,
                'name' => $page->page_title,
                'slug' => $page->url_key,
                'url' => route('shop.cms.page', $page->url_key),
                'type' => 'page',
                'children' => [],
            ]);
        return view(
            'licious::admin.settings.menu.index',
            compact(
                'data',
                'pages',
                'categories'
            )
        );
    }

    public function getIds($n, &$ids)
    {
        foreach ($n as $item) {
            if ($item['type'] == 'custom') {
                continue;
            }

            $ids[$item['type']][] = $item['id'];

            if (empty($item['children'])) {
                continue;
            }
            $this->getIds($item['children'], $ids);
        }
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
        Storage::put('menus.json', json_encode($payload));
        //$this->coreConfigRepository->create([ 'code' => 'store.information.menu.data', 'value' => json_encode($payload) ]);
        session()->flash('success', trans('admin::app.settings.menu.create-success'));
        return redirect()->route('admin.settings.menu.index');
    }
}
