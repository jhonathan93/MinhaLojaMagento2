<?php
/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 * @package Jhonathan_Core
 */

namespace Jhonathan\Customer\Block\Widget;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Block\Widget\AbstractWidget;
use Magento\Customer\Helper\Address as AddressHelper;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Person
 * @package Jhonathan\Customer\Block\Widget
 */
class Person extends AbstractWidget {

    /**
     * Person constructor.
     * @param Context $context
     * @param AddressHelper $addressHelper
     * @param CustomerMetadataInterface $customerMetadata
     * @param array $data
     */
    public function __construct(Context $context, AddressHelper $addressHelper, CustomerMetadataInterface $customerMetadata, array $data = []) {
        parent::__construct($context, $addressHelper, $customerMetadata, $data);
        $this->_isScopePrivate = true;
    }

    /**
     * @return void
     */
    public function _construct() {
        parent::_construct();
        $this->setTemplate('Jhonathan_Customer::widget/person.phtml');
    }

    /**
     * @return bool
     */
    public function isEnabled():bool {
        return true;
    }
}
