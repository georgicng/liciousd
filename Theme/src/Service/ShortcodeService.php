<?php

namespace Gaiproject\Theme\Service;
use Gaiproject\Theme\Shortcode;

class ShortcodeService
{
    public function render($content)
    {
        return Shortcode::compile($content);
    }
}
