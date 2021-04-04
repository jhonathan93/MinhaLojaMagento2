<?php
/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 * @package Jhonathan_RibbonMarketing
 */

namespace Jhonathan\RibbonMarketing\Helper;

use Jhonathan\Core\Helper\AbstractData;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 * @package Jhonathan\RibbonMarketing\Helper
 */
class Data extends AbstractData {

    const CONFIG_MODULE_PATH = 'jhonathan_ribbonmarketing';

    const GROUPGENERAL = 'general';

    const GROUPSETTINGS = 'settings';

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
        parent::__construct($context, static::CONFIG_MODULE_PATH, $objectManager);
        $this->_storeManager = $storeManager;
    }

    /**
     * @param string $code
     * @param string $group
     * @param null $storeId
     * @return array|false|mixed
     */
    public function isEnabled(string $code, string $group, $storeId = null) {
        $storeId = $this->getStoreId($storeId);
        return parent::isEnabled($code, $group, $storeId);
    }

    /**
     * @param string $code
     * @param null $storeId
     * @return array|mixed
     */
    public function getContent(string $code, $storeId = null) {
        $storeId = $this->getStoreId($storeId);
        return parent::Content($code, static::GROUPSETTINGS, $storeId);
    }

    /**
     * @param $storeId
     * @return false|int
     */
    public function getStoreId($storeId) {
        try {
            if (empty($storeId)) {
                return $this->_storeManager->getStore()->getWebsiteId();
            }
        } catch (NoSuchEntityException $e) {
            return false;
        }

        return false;
    }
}
