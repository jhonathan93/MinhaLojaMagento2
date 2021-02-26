<?php
/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 * @package Jhonathan_RibbonMarketing
 */

namespace Jhonathan\RibbonMarketing\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 * @package Jhonathan\RibbonMarketing\Helper
 */
class Data extends AbstractData {

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Data constructor.
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(Context $context, ObjectManagerInterface $objectManager, StoreManagerInterface $storeManager) {
        parent::__construct($context, $objectManager);
        $this->_storeManager = $storeManager;
    }

    /**
     * @param null $storeId
     * @return array|false|mixed
     */
    public function isEnabled($storeId = null) {
        try {
            if (empty($storeId)) {
                $storeId = $this->_storeManager->getStore()->getWebsiteId();
            }
            return parent::isEnabled($storeId);
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }
}
