<?php

namespace Gaiproject\Theme\Service;
use Gaiproject\Theme\Shortcode;

class ShortcodeService
{
    public function render($content)
    {
        $compiled = Shortcode::compile($content);
        logger()->channel('custom')->info(json_encode(compact('content', 'compiled')));
        return $compiled ?? '';
    }
}
