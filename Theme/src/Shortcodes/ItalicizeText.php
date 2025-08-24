<?php

namespace Gaiproject\Theme\Shortcodes;

use Gaiproject\Theme\Shortcode;

class ItalicizeText extends Shortcode
{
    /**
     * The tag to match in content.
     *
     * @var string
     */
    protected $tag = 'italics';

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
        if (isset($this->attributes['escape_html']) && $this->attributes['escape_html'] === 'true') {
            return sprintf('<i>%s</i>', htmlspecialchars($this->body));
        }

        return sprintf('<i>%s</i>', $this->body);
    }
}
