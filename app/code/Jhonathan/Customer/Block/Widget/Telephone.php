<?php
/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 */

namespace Jhonathan\Customer\Block\Widget;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\Data\AttributeMetadataInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Block\Widget\AbstractWidget;
use Magento\Customer\Helper\Address as AddressHelper;
use Magento\Customer\Model\Options;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template\Context;

class Telephone extends AbstractWidget {

    const ATTRIBUTE_CODE = 'telephone';

    /**@var AddressMetadataInterface */
    protected $addressMetadata;

    /**@var Options */
    protected $options;

    /**
     * @param Context $context
     * @param AddressHelper $addressHelper
     * @param CustomerMetadataInterface $customerMetadata
     * @param Options $options
     * @param AddressMetadataInterface $addressMetadata
     * @param array $data
     */
    public function __construct(Context $context, AddressHelper $addressHelper, CustomerMetadataInterface $customerMetadata, Options $options, AddressMetadataInterface $addressMetadata, array $data = []) {
        parent::__construct($context, $addressHelper, $customerMetadata, $data);
        $this->options = $options;
        $this->addressMetadata = $addressMetadata;
        $this->_isScopePrivate = true;
    }

    /**
     * @return void
     */
    public function _construct() {
        parent::_construct();
        $this->setTemplate('Jhonathan_Customer::widget/telephone.phtml');
    }

    /**
     * Can show config value
     * @param string $key
     * @return bool
     */
    protected function _showConfig(string $key):bool {
        return (bool)$this->getConfig($key);
    }

    /**
     * Can show prefix
     * @return bool
     */
    public function showTelephone():bool {
        return $this->_isAttributeVisible(self::ATTRIBUTE_CODE);
    }

    /**
     * @param string $attributeCode
     * @return AttributeMetadataInterface|null
     * @throws LocalizedException
     */
    protected function _getAttribute($attributeCode) {
        if ($this->getForceUseCustomerAttributes() || $this->getObject() instanceof CustomerInterface) {
            return parent::_getAttribute($attributeCode);
        }

        try {
            $attribute = $this->addressMetadata->getAttributeMetadata($attributeCode);
        } catch (NoSuchEntityException $e) {
            return null;
        }

        if ($this->getForceUseCustomerRequiredAttributes() && $attribute && !$attribute->isRequired()) {
            $customerAttribute = parent::_getAttribute($attributeCode);
            if ($customerAttribute && $customerAttribute->isRequired()) {
                $attribute = $customerAttribute;
            }
        }

        return $attribute;
    }

    /**
     * Retrieve store attribute label
     * @param string $attributeCode
     * @return string
     */
    public function getStoreLabel(string $attributeCode):string {
        try {
            $attribute = $this->_getAttribute($attributeCode);
            return $attribute ? __($attribute->getStoreLabel()) : '';
        } catch (LocalizedException $error) {
            return $this->_logger->critical($error->getMessage());
        }
    }

    /**
     * Get string with frontend validation classes for attribute
     * @param $attributeCode
     * @return string
     */
    public function getAttributeValidationClass(string $attributeCode):string {
        try {
            return $this->_addressHelper->getAttributeValidationClass($attributeCode);
        } catch (LocalizedException $error) {
            $this->_logger->critical($error->getMessage());
        }
    }

    /**
     * @param string $attributeCode
     * @return bool
     */
    private function _isAttributeVisible(string $attributeCode):bool {
        try {
            $attributeMetadata = $this->_getAttribute($attributeCode);
            return $attributeMetadata ? (bool)$attributeMetadata->isVisible() : false;
        } catch (LocalizedException $error) {
            $this->_logger->critical($error->getMessage());
        }
    }

    /**
     * Check if telephone attribute enabled in system
     * @return bool
     */
    public function isEnabled():bool {
        try {
            return $this->_getAttribute(self::ATTRIBUTE_CODE) ? (bool)$this->_getAttribute(self::ATTRIBUTE_CODE)->isVisible() : false;
        } catch (LocalizedException $error) {
            $this->_logger->critical($error->getMessage());
        }
    }

    /**
     * Check if telephone attribute marked as required
     * @return bool
     */
    public function isRequired():bool {
        try {
            return $this->_getAttribute(self::ATTRIBUTE_CODE) ? (bool)$this->_getAttribute(self::ATTRIBUTE_CODE)->isRequired() : false;
        } catch (LocalizedException $error) {
            $this->_logger->critical($error->getMessage());
        }
    }
}
