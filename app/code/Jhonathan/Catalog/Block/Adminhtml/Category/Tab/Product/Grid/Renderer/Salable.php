<?php

namespace Jhonathan\Catalog\Block\Adminhtml\Category\Tab\Product\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Backend\Block\Context;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class Salable extends AbstractRenderer {
    /**
     * @var GetProductSalableQtyInterface
     */
    protected $_stockState;

    /**
     * @var Product
     */
    protected $_product;

    /**
     * Salable constructor.
     * @param Context $context
     * @param GetProductSalableQtyInterface $salableQty
     * @param Product $product
     * @param array $data
     */
    public function __construct(Context $context, GetProductSalableQtyInterface $salableQty, Product $product, array $data = []) {
        $this->_stockState = $salableQty;
        $this->_product = $product;
        parent::__construct($context, $data);
    }

    /**
     * @param $productId
     * @return float|string
     */
    protected function getStockItem($productId) {
        try {
            $product = $this->_product->load($productId);
            $Qty = $this->_stockState->execute($product->getSku(), $product->getStoreId());

            if ($Qty > 0) {
                return $Qty;
            } else {
                return '0';
            }
        }catch (LocalizedException $e) {
            $this->logger($e->getLogMessage());
            return 'Error';
        }
    }

    /**
     * @param DataObject $row
     * @return float|string
     */
    public function Render(DataObject $row) {
        $id = $row->getData('entity_id');
        return $this->getStockItem($id);
    }

    /**
     * @param $text
     */
    private function logger($text) {
        $logger = new Logger();
        $logger->addWriter(new Stream(BP . '/var/log/jhonathan.log'));
        $logger->info($text);
    }
}
