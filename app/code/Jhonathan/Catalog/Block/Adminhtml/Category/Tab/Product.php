<?php
namespace Jhonathan\Catalog\Block\Adminhtml\Category\Tab;

use Jhonathan\Catalog\Block\Adminhtml\Category\Tab\Product\Grid\Renderer\Image;
use Jhonathan\Catalog\Block\Adminhtml\Category\Tab\Product\Grid\Renderer\Salable;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Framework\Data\Collection;

class Product extends \Magento\Catalog\Block\Adminhtml\Category\Tab\Product {
    /**
     * @param Collection $collection
     */
    public function setCollection($collection) {
        $collection->setFlag('has_stock_status_filter', true);
        $collection = $collection->joinField('qty',
            'cataloginventory_stock_item',
            'qty',
            'product_id=entity_id',
            '{{table}}.stock_id=1',
            'left'
        )->joinTable('cataloginventory_stock_item', 'product_id=entity_id', array('stock_status' => 'is_in_stock'))
            ->addAttributeToSelect('qty')
            ->addAttributeToSelect('thumbnail')
            ->addAttributeToSelect('salable')
            ->load();
        parent::setCollection($collection);
    }

    /**
     * @return $this|Extended
     */
    protected function _prepareColumns() {
        parent::_prepareColumns();
        $this->addColumnAfter('qty', array(
            'header' => __('Quantidade'),
            'index' => 'qty',
        ), 'sku');

        $this->addColumnAfter('salable', array(
            'header' => __('Vendável'),
            'index' => 'salable',
            'renderer' => Salable::class,
        ), 'qty');

        $this->addColumnAfter('Thumbnail', array(
            'header' => __('Miniatura'),
            'index' => 'Thumbnail',
            'renderer' => Image::class,
            'align' => 'center',
            'filter' => false,
            'sortable' => false,
            'column_css_class' => 'data-grid-thumbnail-cell'
        ), 'entity_id');

        $this->sortColumnsByOrder();
        return $this;
    }
}
