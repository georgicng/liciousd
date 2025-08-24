<?php

namespace Gaiproject\Theme\Shortcodes;

use Gaiproject\Theme\Shortcode;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\AnonymousComponent;

class TopCollection extends Shortcode
{
    /**
     * The tag to match in content.
     *
     * @var string
     */
    protected $tag = 'top_collection';

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
        return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.top-collection'), $this->attributes ?: []));
    }
}
