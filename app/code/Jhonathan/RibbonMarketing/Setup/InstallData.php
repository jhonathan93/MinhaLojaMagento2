<?php
/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 * @package Jhonathan_Core
 */

namespace Jhonathan\RibbonMarketing\Setup;

use Exception;
use Magento\Framework\App\Area;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\View\DesignInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Widget\Model\Widget\InstanceFactory;
use Magento\Framework\App\State;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class InstallData
 * @package Jhonathan\RibbonMarketing\Setup
 */
class InstallData implements InstallDataInterface {

    const MODULE_TITTLE = 'Ribbon Marketing';

    /**
     * @var BlockFactory
     */
    protected $_blockFactory;

    /**
     * @var InstanceFactory
     */
    protected $_instanceFactory;

    /**
     * @var BlockRepositoryInterface
     */
    protected $_blockRepository;

    /**
     * @var State
     */
    protected $_state;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * InstallData constructor.
     * @param BlockFactory $blockFactory
     * @param BlockRepositoryInterface $blockRepository
     * @param InstanceFactory $instanceFactory
     * @param State $state
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        BlockFactory $blockFactory,
        BlockRepositoryInterface $blockRepository,
        InstanceFactory $instanceFactory,
        State $state,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->_blockFactory = $blockFactory;
        $this->_blockRepository = $blockRepository;
        $this->_instanceFactory = $instanceFactory;
        $this->_state = $state;
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {
        try {
            $this->_state->setAreaCode(Area::AREA_ADMINHTML);

            $setup->startSetup();

            $blockSetup = $this->_blockFactory->create(['setup' => $setup]);
            $blockSetup->setData([
                'title'         => self::MODULE_TITTLE,
                'identifier'    => 'RibbonMarketing',
                'content'       => '{{block class="Jhonathan\RibbonMarketing\Block\Ribbon" name="Ribbon_Marketing" template="Jhonathan_RibbonMarketing::ribbon.phtml"}}',
                'is_active'     => 1,
                'stores'        => [0],
            ]);

            $cmsBlock = $this->_blockRepository->save($blockSetup);

            $this->_instanceFactory->create()->setData([
                'instance_type' => 'Magento\Cms\Block\Widget\Block',
                'instance_code' => 'cms_static_block',
                'theme_id' => $this->getCurrentThemeId(),
                'title' => self::MODULE_TITTLE,
                'store_ids' => $this->getCurrentStoreId(),
                'widget_parameters' => '{"block_id":"'.$cmsBlock->getId().'"}',
                'sort_order' => 0,
                'page_groups' => [[
                    'page_id' => 1,
                    'page_group' => 'all_pages',
                    'layout_handle' => 'default',
                    'for' => 'all',
                    'all_pages' => [
                        'page_id' => null,
                        'layout_handle' => 'default',
                        'block' => 'page.top',
                        'for' => 'all',
                        'template' => 'widget/static_block/default.phtml'
                    ]
                ]]
            ])->save();

            $setup->endSetup();
        } catch (Exception $e) {

        }
    }

    /**
     * @return mixed
     */
    private function getCurrentThemeId() {
        try {
            return $this->_scopeConfig->getValue(
                DesignInterface::XML_PATH_THEME_ID,
                ScopeInterface::SCOPE_STORE,
                $this->_storeManager->getStore()->getId()
            );
        } catch (NoSuchEntityException $e) {

        }
    }

    /**
     * @return int
     */
    private function getCurrentStoreId(): int {
        try {
            return $this->_storeManager->getStore()->getWebsiteId();
        } catch (NoSuchEntityException $e) {

        }
    }
}
