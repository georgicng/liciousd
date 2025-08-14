<?php

namespace Gaiproject\Theme\Service;

use Illuminate\Support\Facades\Storage;

class MenuService
{
    public function getCategories($categoryRepository, $params)
    {
        //Maybe filter existing categories well
        return $categoryRepository->getAll($params)
            ->map(fn($category) => [
                'key' => 'category_' . $category->id,
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'url' => url($category->slug),
                'type' => 'category',
                'children' => [],
            ]);
    }

    public function getPages($pageRepository, $menus)
    {
        logger()->channel('custom')->info(json_encode(['ids' => $this->getExistingItems($menus, 'page')]));

        return $pageRepository->findWhereNotIn('id', $this->getExistingItems($menus, 'page'))
            ->map(fn($page) => [
                'key' => 'page_' . $page->id,
                'id' => $page->id,
                'name' => $page->page_title,
                'slug' => $page->url_key,
                'url' => route('shop.cms.page', $page->url_key),
                'type' => 'page',
                'children' => [],
            ]);
    }

    public function getMenus()
    {
        $menus = Storage::json('menus.json') ?? ['custom' => [], 'menus' => []];
        $menus['menus'] = $this->transformCategories($menus['menus']);
        return $menus;
    }

    public function saveMenus($payload)
    {
        return Storage::put('menus.json', json_encode($payload));
    }

    /**
     * Recursively get IDs and group per type from the menu structure.
     *
     * @param array $n
     * @param array $ids
     * @return void
     */
    private function transformCategories($menus)
    {
        if (!is_array($menus)) {
            return [];
        }

        return array_map(
            fn($item) => [
                ...$item,
                'url' => $item['type'] == 'category' ? url($item['slug']) : $item['url'],
                'children' => !empty($item['children']) ? $this->transformCategories($item['children']) : [],
            ],
            $menus
        );
    }

    /**
     * Recursively get IDs and group per type from the menu structure.
     *
     * @param array $n
     * @param array $ids
     * @return array
     */
    private function getExistingItems($menus, $type)
    {
        if (!is_array($menus)) {
            return [];
        }

        return array_reduce($menus, fn($ids, $item) => [
            ...$ids,
            ...($item['type'] == $type ? [$item['id']] : []),
            ...(!empty($item['children']) ? $this->getExistingItems($item['children'], $type) : []),
        ], []);
    }
}
