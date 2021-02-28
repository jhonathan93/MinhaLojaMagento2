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
        if ($this->_HelperData->isEnabled('enabled', $this->_HelperData::GROUPGENERAL)) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        if ($this->_HelperData->isEnabled('title_active', $this->_HelperData::GROUPSETTINGS)) {
            return "<div class='rm-title'><h1><span>" .$this->_HelperData->getContent('ribbon_title'). "</span></h1></div>";
        }

        return '';
    }

    /**
     * @return string
     */
    public function getContent(): string {
        if ($this->_HelperData->isEnabled('content_active', $this->_HelperData::GROUPSETTINGS)) {
            return "<div>".$this->_HelperData->getContent('ribbon_content')."<div/>";
        }

        return '';
    }
}
