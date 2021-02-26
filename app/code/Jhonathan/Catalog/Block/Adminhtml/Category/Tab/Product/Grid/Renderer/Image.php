<?php

namespace Jhonathan\Catalog\Block\Adminhtml\Category\Tab\Product\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Backend\Block\Context;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Framework\DataObject;

class Image extends AbstractRenderer {
    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @param Context $context
     * @param ImageHelper $imageHelper
     * @param array $data
     */
    public function __construct(Context $context, ImageHelper $imageHelper,  array $data = []) {
        $this->imageHelper = $imageHelper;
        parent::__construct($context, $data);
    }

    /**
     * @param DataObject $row
     * @return string
     */
    public function render(DataObject $row): string {
        $image = 'product_listing_thumbnail';
        $imageUrl = $this->imageHelper->init($row, $image)->getUrl();
        return '<img src="'.$imageUrl.'" width="150"/>';
    }
}
