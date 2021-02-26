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
use Magento\Customer\Helper\Address;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;

class Taxvat extends AbstractWidget {
    /**
     * @var Session
     */
    protected $_session;

    /**
     * Constructor.
     * @param Context $context
     * @param Address $addressHelper
     * @param CustomerMetadataInterface $customerMetadata
     * @param Session $session
     * @param array $data
     */
    public function __construct(Context $context, Address $addressHelper, CustomerMetadataInterface $customerMetadata, Session $session, array $data = []) {
        parent::__construct($context, $addressHelper, $customerMetadata, $data);
        $this->_isScopePrivate = true;
        $this->_session = $session;
    }

    /**
     * Sets the template
     * @return void
     */
    public function _construct() {
        parent::_construct();
        $this->setTemplate('Jhonathan_Customer::widget/taxvat.phtml');
    }

    /**
     * Get is enabled.
     * @return bool
     */
    public function isEnabled():bool {
        return $this->_getAttribute('taxvat') ? (bool)$this->_getAttribute('taxvat')->isVisible() : false;
    }

    /**
     * Get is required.
     * @return bool
     */
    public function isRequired():bool {
        return $this->_getAttribute('taxvat') ? (bool)$this->_getAttribute('taxvat')->isRequired() : false;
    }

    /**
     * Retrieve store attribute label
     * @param string $attributeCode
     * @return string
     */
    public function getStoreLabel(string $attributeCode):string {
        $attribute = $this->_getAttribute($attributeCode);
        return $attribute ? __($attribute->getStoreLabel()) : '';
    }

    /**
     * @return string
     */
    public function ckeckIsLoggedIn():string {
        if ($this->_session->isLoggedIn()) {
            $doc = $this->_session->getCustomer()->getData('taxvat');
            if (is_null($doc)) {
                return '000.000.000-00';
            } else {
                return preg_replace('/[0-9]/', '0',$doc);
            }
        }
        return '000.000.000-00';
    }
}
