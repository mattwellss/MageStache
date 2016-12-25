<?php
namespace MattWellss\MageStache;

use Phly\Mustache\Resolver\ResolverInterface;

class TextResolver implements ResolverInterface
{

    /**
     * Resolve a template name
     *
     * Resolve a template name to mustache content or a set of tokens.
     *
     * @param  string $template
     * @return string|array
     */
    public function resolve($template)
    {
        return $template;
    }
}