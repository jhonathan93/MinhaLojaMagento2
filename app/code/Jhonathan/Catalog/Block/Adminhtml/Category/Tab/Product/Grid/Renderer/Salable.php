<?php

namespace Jhonathan\Catalog\Block\Adminhtml\Category\Tab\Product\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Backend\Block\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\InputException;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Magento\InventorySalesApi\Api\IsProductSalableInterface;
use Magento\CatalogInventory\Api\StockStateInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class Salable extends AbstractRenderer {
    /**
     * @var GetProductSalableQtyInterface
     */
    protected $_salableQty;

    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;

    /**
     * @var IsProductSalableInterface
     */
    protected $_isProductSalable;

    /**
     * @var StockStateInterface
     */
    protected $_stockState;

    /**
     * Salable constructor.
     * @param Context $context
     * @param GetProductSalableQtyInterface $salableQty
     * @param ProductRepositoryInterface $productRepository
     * @param IsProductSalableInterface $isProductSalable
     * @param StockStateInterface $stockState
     * @param array $data
     */
    public function __construct(Context $context,
                                GetProductSalableQtyInterface $salableQty,
                                ProductRepositoryInterface $productRepository,
                                IsProductSalableInterface $isProductSalable,
                                StockStateInterface $stockState,
                                array $data = []
    ) {
        $this->_salableQty = $salableQty;
        $this->_productRepository = $productRepository;
        $this->_isProductSalable = $isProductSalable;
        $this->_stockState = $stockState;
        parent::__construct($context, $data);
    }

    /**
     * @param $productId
     * @return float|string
     */
    protected function getStockItem($productId) {
        try {
            $product = $this->_productRepository->getById($productId);

            if ($this->_stockState->getStockQty($productId, $product->getStoreid()) > 0) {
                if ($this->_isProductSalable->execute($product->getSku(), $product->getStoreId())) {
                    $Qty = $this->_salableQty->execute($product->getSku(), $product->getStoreId());

                    if ($Qty > 0) {
                        return $Qty;
                    } else {
                        return '0';
                    }
                }
            }

            return '0';
        }catch (LocalizedException | InputException $e) {
            $this->logger($e->getLogMessage());
            return 'Error';
        }
    }

    /**
     * @param DataObject $row
     * @return float|string
     */
    public function Render(DataObject $row) {
        return $this->getStockItem($row->getData('entity_id'));
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
