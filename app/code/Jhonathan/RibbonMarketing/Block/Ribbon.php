<?php
/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 * @package Jhonathan_RibbonMarketing
 */

namespace Jhonathan\RibbonMarketing\Block;

use Magento\Framework\View\Element\Template;
use Jhonathan\RibbonMarketing\Helper\Data;

class Ribbon extends Template {

    /**
     * @var Data
     */
    protected $_HelperData;

    /**
     * Ribbon constructor.
     * @param Template\Context $context
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(Template\Context $context, Data $helperData, array $data = []) {
        parent::__construct($context, $data);
        $this->_HelperData = $helperData;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool {
        if ($this->_HelperData->isEnabled()) {
            return true;
        }
        return false;
    }

    public function getTitle(): string {
        return $this->_HelperData->title();
    }
}
