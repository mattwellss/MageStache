<?php

namespace MattWellss\MageStache;

use Mage;
use Mage_Core_Helper_Data;
use Phly\Mustache\Exception\InvalidPartialsException;
use Phly\Mustache\Mustache;

/**
 * @author Matthew Wells <ttamsllew@gmail.com>
 * @package MattWellss\MageStache
 *
 * Wrapper for Mustache functionality
 * Hides the actual implementation used
 */
class Helper_Mustache extends Mage_Core_Helper_Data
{
    /**
     * @var Mustache
     */
    private $mustache;

    /**
     * Initializes Mustache with a custom Resolver and Pragma
     * @see MagentoResolver
     * @see MagentoPragma
     */
    public function __construct()
    {
        $this->mustache = new Mustache();

        $this->mustache->getResolver()
            ->attach(new MagentoResolver(), 100);

        $this->mustache->getPragmas()
            ->add(new MagentoPragma(Mage::getSingleton('core/layout')));
    }

    /**
     * Renders the template using Mustache
     *
     * @param $template
     * @param $view
     * @param array $partials
     * @return string
     * @throws InvalidPartialsException
     */
    public function render($template, $view, array $partials = [])
    {
        return $this->mustache->render($template, $view, $partials);
    }
}