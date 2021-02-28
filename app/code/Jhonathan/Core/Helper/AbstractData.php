<?php
/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 * @package Jhonathan_Core
 */

namespace Jhonathan\Core\Helper;

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
    /**
     * @var string
     */
    protected $_module;

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
     * @param string $module
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(Context $context, string $module, ObjectManagerInterface $objectManager) {
        parent::__construct($context);
        $this->objectManager = $objectManager;
        $this->_module = $module;
    }

    /**
     * @param string $code
     * @param string $group
     * @param null $storeId
     * @return array|mixed
     */
    public function isEnabled(string $code, string $group, $storeId = null) {
        return $this->getConfigGeneral($code, $group, $storeId);
    }

    /**
     * @param string $code
     * @param string $group
     * @param null $storeId
     * @return array|mixed
     */
    public function content(string $code, string $group, $storeId = null) {
        return $this->getConfigGeneral($code, $group, $storeId);
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
        return $this->getConfigValue($this->_module . $group . $code, $storeId);
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
