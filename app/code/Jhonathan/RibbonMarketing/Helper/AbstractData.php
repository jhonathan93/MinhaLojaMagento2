<?php
/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 * @package Jhonathan_Core
 */

namespace Jhonathan\RibbonMarketing\Helper;

use Magento\Backend\App\Config;
use Magento\Backend\App\ConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class AbstractData
 * @package Jhonathan\RibbonMarketing\Helper
 */
class AbstractData extends AbstractHelper {

    const CONFIG_MODULE_PATH = 'jhonathan_ribbonmarketing';

    /**
     * @var Config
     */
    protected $backendConfig;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * AbstractData constructor.
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(Context $context, ObjectManagerInterface $objectManager) {
        parent::__construct($context);
        $this->objectManager = $objectManager;
    }

    /**
     * @param null $storeId
     * @return array|mixed
     */
    public function isEnabled($storeId = null) {
        return $this->getConfigGeneral('enabled', 'general', $storeId);
    }

    /**
     * @param null $storeId
     * @return array|mixed
     */
    public function title($storeId = null) {
        return $this->getConfigGeneral('ribbon_title', 'settings', $storeId);
    }

    /**
     * @param string $code
     * @param string $group
     * @param null $storeId
     * @return array|mixed
     */
    public function getConfigGeneral(string $code, string $group, $storeId = null) {
        $code = '/' . $code;
        $group = '/' . $group;
        return $this->getConfigValue(static::CONFIG_MODULE_PATH . $group . $code, $storeId);
    }

    /**
     * @param $field
     * @param null $scopeValue
     * @param string $scopeType
     * @return array|mixed
     */
    public function getConfigValue($field, $scopeValue = null, $scopeType = ScopeInterface::SCOPE_STORE) {
        if ($scopeValue === null) {
            if (!$this->backendConfig) {
                $this->backendConfig = $this->objectManager->get(ConfigInterface::class);
            }

            return $this->backendConfig->getValue($field);
        }

        return $this->scopeConfig->getValue($field, $scopeType, $scopeValue);
    }
}
