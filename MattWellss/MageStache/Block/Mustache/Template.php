<?php


/**
 * @author Matthew Wells <ttamsllew@gmail.com>
 * @package MattWellss\MageStache
 *
 * Ideally there'd be namespaces for this class as well.
 * Unfortunately, Magento's block loading functionality makes that impossible
 *   ... (without some core-hackery) ...
 * So we just use 'ol Magento-style class names
 * @see \Mage_Core_Model_Layout::_getBlockInstance
 */
class MattWellss_MageStache_Block_Mustache_Template extends Mage_Core_Block_Template
{
    /**
     * @var Mage_Core_Block_Template|null
     */
    private $dataBlock = null;

    /**
     * @var string|null
     */
    private $fieldsetName = null;


    /**
     * @param string $fieldsetName
     */
    public function setFieldsetName($fieldsetName)
    {
        $this->fieldsetName = $fieldsetName;
    }

    /**
     * @param string $dataBlock
     */
    public function setDataBlock($dataBlock)
    {
        $this->dataBlock = $this->getChild($dataBlock);
    }

    protected function _toHtml()
    {
        /** @var Helper_Mustache $mustache */
        $mustache = Mage::helper('magestache/mustache');

        return $mustache->render($this->getNameInLayout(), $this->_prepareData());
    }

    /**
     * An override to *ensure* that the output from fetchView
     *  is always captured, not `echo`ed
     *
     * @see Mage_Core_Block_Template::fetchView()
     * @return bool
     */
    public function getDirectOutput()
    {
        return false;
    }

    /**
     * Prepare, return data for the block
     *
     * @return array
     */
    protected function _prepareData()
    {
        // Set the block used for source data.
        // First preference is "dataBlock,"
        //  but `$this` is used secondarily
        $dataSourceBlock = $this->dataBlock ?: $this;

        // If the fieldset name isn't set
        //  we cannot use fieldset conversion
        //  so we assume the us er doesn't care to
        //  do so. Use the block's data
        if (is_null($this->fieldsetName)) {
            return $dataSourceBlock->getData();
        }

        /** @var Mage_Core_Helper_Data $helper */
        $helper = Mage::helper('core');

        // Use fieldset copying to convert block source data into target
        $helper->copyFieldset(
            $this->fieldsetName,
            'to_array',
            $dataSourceBlock,
            $data = []);

        return $data;
    }

    protected function _saveCache($data)
    {
        Mage::helper('magestache/mustache')->cacheToken($this->getNameInLayout());
        return parent::_saveCache($data);
    }
}