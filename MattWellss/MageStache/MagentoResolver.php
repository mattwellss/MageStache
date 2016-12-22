<?php

namespace MattWellss\MageStache;

use Mage;
use Mage_Core_Model_Layout;
use MattWellss_MageStache_Block_Mustache_Template;
use Phly\Mustache\Resolver\ResolverInterface;

/**
 * @package MattWellss\MageStache
 * @author Matthew Wells <ttamsllew@gmail.com>
 *
 * Implementaiton of a phly-mustache template resolver
 *
 * Built specifically to resolve a partial (a-la `{{> content}}`)
 * To its Magento block (Mustache or PHTML!)
 *
 * A bit of hackery is introduced to handle template-not-found issues
 */
class MagentoResolver implements ResolverInterface
{

    /**
     * Resolve a template name to mustache content or a set of tokens.
     *
     * @param  string $template
     * @return string|array
     */
    public function resolve($template)
    {
        /** @var Mage_Core_Model_Layout $layout */
        $layout = Mage::getSingleton('core/layout');

        /** @var \Mage_Core_Block_Abstract $block */
        $block = $layout->getBlock($template);

        // Could not resolve, return empty
        // Note that we don't return a "coerces-to-false" empty string!
        if (!$block) {
            return ' ';
        }

        // If it's a mustache template, we return the contents.
        // Note: mustache subtemplates inside phtml WILL be rendered
        //  through their toHtml calls
        if ($block instanceof MattWellss_MageStache_Block_Mustache_Template) {
            $block->setScriptPath(Mage::getBaseDir('design'));
            return $block->fetchView($block->getTemplateFile());
        }

        // Like before, we ensure that all blocks
        //  EVEN childless `core/text_list` blocks
        //  will have a non-falsy output
        return $block->toHtml() . ' ';
    }
}