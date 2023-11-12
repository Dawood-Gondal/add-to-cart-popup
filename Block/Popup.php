<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AddToCartPopup
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AddToCartPopup\Block;

use Magento\Framework\View\Asset\Repository as AssetRepository;
use Magento\Framework\View\Element\Template;

class Popup extends Template
{
    /**
     * @var AssetRepository
     */
    protected $assetRepository;

    /**
     * @param Template\Context $context
     * @param AssetRepository $assetRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        AssetRepository $assetRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->assetRepository = $assetRepository;
    }

    /**
     * @return string
     */
    public function getShoppingCartUrl()
    {
        return $this->getUrl('checkout/cart');
    }

    /**
     * @return string
     */
    public function getCartMessage()
    {
        $message  = __('A new item has been added to your Shopping Cart. ');
        $message .= __('You now have %s items in your Shopping Cart.');

        return sprintf($message, "<span id='cart-popup-total-count'></span>");
    }

    /**
     * Returns an icon to be displayed
     * @return string
     */
    public function getSuccessIcon()
    {
        return $this->assetRepository->getUrl('M2Commerce_AddToCartPopup::images/success_icon.png');
    }
}
