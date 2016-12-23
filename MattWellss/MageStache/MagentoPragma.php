<?php

namespace MattWellss\MageStache;

use Mage;
use MattWellss_MageStache_Block_Mustache_Template;
use Phly\Mustache\Exception\InvalidPartialsException;
use Phly\Mustache\Lexer;
use Phly\Mustache\Mustache;
use Phly\Mustache\Pragma\PragmaInterface;
use Phly\Mustache\Pragma\PragmaNameAndTokensTrait;

/**
 * @author Matthew Wells <ttamsllew@gmail.com>
 * @package MattWellss\MageStache
 *
 * Handler for mustache -> mustache rendering
 * Rendering phtml -> mustache uses MagentoResolver
 * Rendering mustache -> phtml uses MagentoResolver
 */
class MagentoPragma implements PragmaInterface
{
    // shortcut for common pragma functionality
    // uses $name, $tokensHandled
    use PragmaNameAndTokensTrait;

    /**
     * Pragma name
     *
     * @var string
     */
    private $name = 'MAGENTO-TEMPLATE';

    /**
     * Tokens handled by this pragma
     * @var array
     */
    private $tokensHandled = [
        Lexer::TOKEN_PARTIAL,
    ];

    /**
     * The method MUST return a token struct on completion; if the pragma does
     * not need to do anything, it can simply `return $tokenStruct`.
     *
     * @param array $tokenStruct
     * @return array
     */
    public function parse(array $tokenStruct)
    {
        return $tokenStruct;
    }

    /**
     * Render a given token.
     *
     * Returning an empty value returns control to the renderer.
     *
     * $tokenStruct is an array consisting minimally of:
     *
     * - 0: int token (from Lexer::TOKEN_* constants)
     * - 1: mixed data (data associated with the token)
     *
     * @param  array $tokenStruct
     * @param  mixed $view
     * @param  array $options
     * @param  Mustache $mustache Mustache instance handling rendering.
     * @return mixed
     * @throws InvalidPartialsException
     */
    public function render(array $tokenStruct, $view, array $options, Mustache $mustache)
    {
        /** @var array $childMeta */
        list($_, $childMeta) = $tokenStruct;

        /** @var \Mage_Core_Block_Template $child */
        $child = Mage::getSingleton('core/layout')->getBlock($childMeta['partial']);

        // The view, being a mustache template, will now be rendered as HTML
        if ($child && $child instanceof MattWellss_MageStache_Block_Mustache_Template) {
            /** @see Block_Mustache_Template::toHtml() */
            return $child->toHtml();
        }

        return null; // mustache will use normal renderer
    }
}
