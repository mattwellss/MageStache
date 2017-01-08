<?php

class MattWellss_MageStache_Block_Mustache_Template_Registry extends Mage_Core_Block_Text
{
    /**
     * @var string
     */
    protected $_childOpenTag;

    /**
     * @var string
     */
    protected $_childCloseTag;

    /**
     * Set default child frame tags
     */
    protected function _construct()
    {
        $this->setChildFrameTags('script type="text/x-mustache-template"', '/script');
        parent::_construct();
    }

    /**
     * Ensure all mustache templates are included in HTML output
     * @return mixed|string
     */
    protected function _toHtml()
    {
        $this->setText('');
        foreach ($this->_children as $block) {
            /** @var $block MattWellss_MageStache_Block_Mustache_Template */
            $block->setFrameTags($this->_childOpenTag, $this->_childCloseTag);
            $block->setScriptPath(Mage::getBaseDir('design'));
            $this->addText(
                implode(PHP_EOL, [
                    $this->getChildOpenTag($block),
                    $this->getChildContent($block),
                    $this->getChildCloseTag()
                ]));
        }
        return parent::_toHtml();
    }

    /**
     * @param string $open
     * @param false|string $close
     * @return $this
     */
    public function setChildFrameTags($open, $close = false)
    {
        $this->_childOpenTag = $open;
        $this->_childCloseTag = $close ?: "/{$open}";
        return $this;
    }

    /**
     * @param MattWellss_MageStache_Block_Mustache_Template $block
     * @return string
     */
    protected function getChildOpenTag(MattWellss_MageStache_Block_Mustache_Template $block)
    {
        $dataId = $block->getTemplate();
        return "<{$this->_childOpenTag} data-id=\"$dataId\">";
    }

    /**
     * @return string
     */
    protected function getChildCloseTag()
    {
        return "<{$this->_childCloseTag}>";
    }

    /**
     * @param MattWellss_MageStache_Block_Mustache_Template $block
     * @return string
     */
    private function getChildContent(MattWellss_MageStache_Block_Mustache_Template $block)
    {
        return $block->fetchView($block->getTemplateFile());
    }


}
