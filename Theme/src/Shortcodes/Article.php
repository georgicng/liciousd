<?php

namespace Gaiproject\Theme\Shortcodes;

use Gaiproject\Theme\Shortcode;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\AnonymousComponent;
use Illuminate\Support\Str;

class Article extends Shortcode
{
    /**
     * The tag to match in content.
     *
     * @var string
     */
    protected $tag = 'article';

    /**
     * The code to run when the Shortcode is being compiled.
     *
     * You may return a string from here, that will then
     * be inserted into the content being compiled.
     *
     * @return string|null
     */
    public function handle(): ?string
    {

        $html = Str::of($this->body)
            ->pipe(fn($str) => preg_replace('/<p[^>]*>(?:\\s|&nbsp;)*<\\/p>/', '', $str))
            ->pipe(
                fn($str) => Str::replace('<p>', '<p class="mb-[24px] text-[14px] font-Poppins text-[#7a7a7a] leading-[1.75] max-[991px]:block">', $str)
            );
        return Blade::renderComponent(
            new AnonymousComponent(
                view('licious::components.shortcodes.blocks.article'),
                [...($this->attributes ?: []), 'content' => $html]
            )
        );
    }
}
