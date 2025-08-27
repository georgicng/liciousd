<?php

namespace Gaiproject\Theme\Shortcodes;

use Gaiproject\Theme\Shortcode;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\AnonymousComponent;
use Illuminate\Support\Str;

class SideLayout extends Shortcode
{
    /**
     * The tag to match in content.
     *
     * @var string
     */
    protected $tag = 'sidelayout';

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
        $attributes = [
            'image' => isset($this->attributes['path']) ? bagisto_asset($this->attributes['path']) : (isset($this->attributes['url']) ? $this->attributes['url']: '//placehold.co/600x500'),
            'alt' => $this->attributes['alt'] ?? '',
            'align' => $this->attributes['align'] ?? 'left',
        ];
        return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.layout.image'), [ ...$attributes, 'slot' => $this->body]));
    }
}
