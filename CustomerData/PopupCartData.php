<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AddToCartPopup
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AddToCartPopup\CustomerData;

use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Checkout\Helper\Cart as CartHelper;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;

/**
 * PopupCart source
 */
class PopupCartData implements SectionSourceInterface
{
    const CONFIG_PRODUCT_LIMIT = 4;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Image
     */
    protected $catalogImage;

    /**
     * @var PricingHelper
     */
    protected $pricingHelper;

    /**
     * @var Status
     */
    protected $productStatus;

    /**
     * @var Visibility
     */
    protected $productVisibility;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var CartHelper
     */
    protected $cartHelper;

    /**
     * @param CollectionFactory $collectionFactory
     * @param Image $catalogImage
     * @param PricingHelper $pricingHelper
     * @param Status $productStatus
     * @param Visibility $productVisibility
     * @param CartHelper $cartHelper
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Image $catalogImage,
        PricingHelper $pricingHelper,
        Status $productStatus,
        Visibility $productVisibility,
        CartHelper $cartHelper
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->catalogImage = $catalogImage;
        $this->pricingHelper = $pricingHelper;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
        $this->cartHelper = $cartHelper;
        $this->_initCollection();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSectionData()
    {
        $this->_randomProducts();
        return [
            'cartTotalCount' => $this->cartHelper->getSummaryCount(),
            'products' => $this->_getCollection()
        ];
    }

    /**
     * @return void
     */
    private function _initCollection()
    {
        $this->collection = $this->collectionFactory->create()
            ->addAttributeToSelect('*')
            ->addStoreFilter()
            ->addAttributeToFilter(
                'status',
                ['in' => $this->productStatus->getVisibleStatusIds()]
            )->addAttributeToFilter(
                'visibility',
                ['in' => $this->productVisibility->getVisibleInSiteIds()]
            );
        $this->collection->addUrlRewrite();
        $this->collection->addMinimalPrice();
    }

    /**
     * Select random products
     * @return void
     */
    private function _randomProducts()
    {
        $this->collection->getSelect()->orderRand();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function _getCollection()
    {
        $this->collection->setPageSize(self::CONFIG_PRODUCT_LIMIT);
        foreach ($this->collection as $i => $product)
        {
            $product->setData(
                'product_name',
                $product->getName()
            );
            $product->setData(
                'product_url',
                $product->getProductUrl()
            );
            $product->setData(
                'product_image',
                $this->catalogImage->init($product, 'product_base_image')->getUrl()
            );
            $product->setData(
                'product_price',
                $this->pricingHelper->currency(
                    $product->getMinimalPrice(),
                    true,
                    false
                )
            );
            $this->collection->removeItemByKey($i);
            $this->collection->addItem($product);
        }

        return $this->collection->toArray();
    }
}
