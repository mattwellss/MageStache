<?php

namespace MattWellss\MageStache;

use Mage;
use Mage_Core_Helper_Data;
use Phly\Mustache\Exception\InvalidPartialsException;
use Phly\Mustache\Mustache;
use Phly\Mustache\Pragma\ImplicitIterator;
use Phly\Mustache\Resolver\AggregateResolver;

/**
 * @author Matthew Wells <ttamsllew@gmail.com>
 * @package MattWellss\MageStache
 *
 * Wrapper for Mustache functionality
 * Hides the actual implementation used
 */
class Helper_Mustache extends Mage_Core_Helper_Data
{
    const CACHE_ID = 'mustache_tokens';

    private $cacheDirty = false;

    /**
     * @var array
     */
    private $cache = [];

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
        $this->mustache = new Mustache(new AggregateResolver());

        if (Mage::app()->useCache('mustache')) {
            $this->cache = unserialize(Mage::app()->loadCache(static::CACHE_ID) ?: 'a:0:{}');
        }

        $this->mustache->restoreTokens($this->cache);

        $this->mustache->getResolver()
            ->attach(new MagentoResolver(), 1)
            ->attach(new TextResolver(), 0);

        $pragmas = $this->mustache->getPragmas();
        $pragmas->add(new MagentoPragma(Mage::getSingleton('core/layout')));
        $pragmas->add(new ImplicitIterator());
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

    /**
     * Returns true if the token was cached, false otherwise
     * @param $name
     * @return bool
     */
    public function cacheToken($name)
    {
        $tokens = $this->mustache->getAllTokens();
        if (array_key_exists($name, $tokens) && !array_key_exists($name, $this->cache)) {
            $this->cache[$name] = $tokens[$name];
            $this->cacheDirty = true;
            return true;
        }

        return false;
    }

    function __destruct()
    {
        if ($this->cacheDirty && Mage::app()->useCache('mustache')) {
            Mage::app()->saveCache(serialize($this->cache), self::CACHE_ID, [self::CACHE_ID]);
        }
    }
}