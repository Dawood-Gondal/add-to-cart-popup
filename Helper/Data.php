<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AddToCartPopup
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AddToCartPopup\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Helper Data
 */
class Data extends AbstractHelper
{
    const XML_IS_POPUP_ENABLED = "addToCartPopup/general/enabled";

    /**
     * @return bool
     */
    public function isPopupEnable()
    {
        return (bool)$this->scopeConfig->getValue(self::XML_IS_POPUP_ENABLED, ScopeInterface::SCOPE_STORE);
    }
}
