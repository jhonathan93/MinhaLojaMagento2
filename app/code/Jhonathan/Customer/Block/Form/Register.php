<?php
/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 * @package Jhonathan_Core
 */

namespace Jhonathan\Customer\Block\Form;

use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\Metadata\Form;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url;
use Magento\Directory\Helper\Data;
use Magento\Directory\Model\AllowedCountries;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Module\Manager;
use Magento\Framework\View\Element\Template\Context;
use Magento\Newsletter\Model\Config;
use Magento\Store\Model\ScopeInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

/**
 * Class Register
 * @package Jhonathan\Customer\Block\Form
 */
class Register extends \Magento\Directory\Block\Data
{
    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var Manager
     */
    protected $_moduleManager;

    /**
     * @var Url
     */
    protected $_customerUrl;

    /**
     * @var Config
     */
    private $newsLetterConfig;

    /**
     * @var AllowedCountries
     */
    private $_allowedCountryModel;

    /**
     * Register constructor.
     * @param Context $context
     * @param Data $directoryHelper
     * @param EncoderInterface $jsonEncoder
     * @param \Magento\Framework\App\Cache\Type\Config $configCacheType
     * @param CollectionFactory $regionCollectionFactory
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     * @param Manager $moduleManager
     * @param Session $customerSession
     * @param Url $customerUrl
     * @param AllowedCountries $allowedCountryModel
     * @param array $data
     * @param Config|null $newsLetterConfig
     */
    public function __construct(
        Context $context,
        Data $directoryHelper,
        EncoderInterface $jsonEncoder,
        \Magento\Framework\App\Cache\Type\Config $configCacheType,
        CollectionFactory $regionCollectionFactory,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        Manager $moduleManager,
        Session $customerSession,
        Url $customerUrl,
        AllowedCountries $allowedCountryModel,
        array $data = [],
        Config $newsLetterConfig = null
    ) {
        $this->_customerUrl = $customerUrl;
        $this->_allowedCountryModel = $allowedCountryModel;
        $this->_moduleManager = $moduleManager;
        $this->_customerSession = $customerSession;
        $this->newsLetterConfig = $newsLetterConfig ?: ObjectManager::getInstance()->get(Config::class);
        parent::__construct(
            $context,
            $directoryHelper,
            $jsonEncoder,
            $configCacheType,
            $regionCollectionFactory,
            $countryCollectionFactory,
            $data
        );
        $this->_isScopePrivate = false;
    }

    /**
     * Get config
     * @param string $path
     * @return string|null
     */
    public function getConfig(string $path) {
        return $this->_scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve form posting url
     * @return string
     */
    public function getPostActionUrl():string {
        return $this->_customerUrl->getRegisterPostUrl();
    }

    /**
     * Retrieve back url
     * @return string
     */
    public function getBackUrl():string {
        $url = $this->getData('back_url');
        if ($url === null) {
            $url = $this->_customerUrl->getLoginUrl();
        }
        return $url;
    }

    /**
     * @return array|DataObject|mixed
     */
    public function getFormData() {
        $data = $this->getData('form_data');
        if ($data === null) {
            $formData = $this->_customerSession->getCustomerFormData(true);
            $data = new DataObject();
            if ($formData) {
                $data->addData($formData);
                $data->setCustomerData(1);
            }
            if (isset($data['region_id'])) {
                $data['region_id'] = (int)$data['region_id'];
            }
            $this->setData('form_data', $data);
        }
        return $data;
    }

    /**
     * Retrieve customer country identifier
     * @return string
     */
    public function getCountryId():string {
        $countryId = $this->getFormData()->getCountryId();
        if ($countryId) {
            return $countryId;
        }
        return parent::getCountryId();
    }

    /**
     * Retrieve customer region identifier
     * @return mixed
     */
    public function getRegion() {
        if (null !== ($region = $this->getFormData()->getRegion())) {
            return $region;
        } elseif (null !== ($region = $this->getFormData()->getRegionId())) {
            return $region;
        }
        return null;
    }

    /**
     * Newsletter module availability
     * @return bool
     */
    public function isNewsletterEnabled():bool {
        return $this->_moduleManager->isOutputEnabled('Magento_Newsletter') && $this->newsLetterConfig->isActive();
    }

    /**
     * Restore entity data from session
     * Entity and form code must be defined for the form
     * @param Form $form
     * @param string|null $scope
     * @return Register
     */
    public function restoreSessionData(Form $form, $scope = null): Register {
        if ($this->getFormData()->getCustomerData()) {
            $request = $form->prepareRequest($this->getFormData()->getData());
            $data = $form->extractData($request, $scope, false);
            $form->restoreData($data);
        }

        return $this;
    }

    /**
     * Get minimum password length
     * @return string
     * @since 100.1.0
     */
    public function getMinimumPasswordLength():string {
        return $this->_scopeConfig->getValue(AccountManagement::XML_PATH_MINIMUM_PASSWORD_LENGTH);
    }

    /**
     * Get number of password required character classes
     * @return string
     * @since 100.1.0
     */
    public function getRequiredCharacterClassesNumber():string {
        return $this->_scopeConfig->getValue(AccountManagement::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER);
    }

    public function allowedCountry() {
        $country = $this->_allowedCountryModel->getAllowedCountries();
        $this->logger(print_r($country, 1));
    }

    private function logger($text) {
        $logger = new Logger();
        $logger->addWriter(new Stream(BP . '/var/log/jhonathan.log'));
        $logger->info($text);
    }
}
