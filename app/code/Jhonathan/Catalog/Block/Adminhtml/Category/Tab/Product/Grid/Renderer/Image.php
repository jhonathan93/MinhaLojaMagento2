<?php

namespace Jhonathan\Catalog\Block\Adminhtml\Category\Tab\Product\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Backend\Block\Context;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Framework\DataObject;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Image
 * @package Jhonathan\Catalog\Block\Adminhtml\Category\Tab\Product\Grid\Renderer
 */
class Image extends AbstractRenderer {
    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepositoryInterface;

    /**
     * @var Data
     */
    protected $_backendHelper;

    /**
     * Image constructor.
     * @param Context $context
     * @param ImageHelper $imageHelper
     * @param ProductRepositoryInterface $productRepositoryInterface
     * @param Data $backendHelper
     * @param array $data
     */
    public function __construct(Context $context,
                                ImageHelper $imageHelper,
                                ProductRepositoryInterface $productRepositoryInterface,
                                Data $backendHelper,
                                array $data = []
    ) {
        $this->imageHelper = $imageHelper;
        $this->_productRepositoryInterface = $productRepositoryInterface;
        $this->_backendHelper = $backendHelper;
        parent::__construct($context, $data);
    }

    /**
     * @param DataObject $row
     * @return string
     */
    public function render(DataObject $row): string {
        try {
            $product = $this->_productRepositoryInterface->getById($row->getData('entity_id'));
            $imageUrl = $this->imageHelper->init($product, 'product_listing_thumbnail')->getUrl();
            $url = $this->_backendHelper->getUrl('catalog/product/edit', ['id' => $row->getData("entity_id")]);
            return '<a href="'.$url.'" target="_blank">
                        <img src="'.$imageUrl.'" width="150" alt="'.$product->getName().'" title="'.$product->getName().'"/>
                    </a>';
        } catch (NoSuchEntityException $e) {
            return 'Error';
        }
    }
}
