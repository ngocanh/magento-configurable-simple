<?php
/**
 * The MIT License (MIT)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @category  OrganicInternet
 * @package   OrganicInternet_SimpleConfigurableProducts
 * @author    Ngoc Anh Doan <ngoc@nhdoan.de>
 * @license   http://opensource.org/licenses/MIT     MIT License (MIT)
 * @link      https://github.com/ngocanh/magento-configurable-simple
 */

/**
 * Rewrite of Mage_Checkout_Block_Cart_Crosssell to consider module's cpid.
 *
 * @category  OrganicInternet
 * @package   OrganicInternet_SimpleConfigurableProducts
 * @author    Ngoc Anh Doan <ngoc@nhdoan.de>
 * @license   http://opensource.org/licenses/MIT     MIT License (MIT)
 * @link      https://github.com/ngocanh/magento-configurable-simple
 */
class OrganicInternet_SimpleConfigurableProducts_Checkout_Block_Cart_Crosssell
    extends Mage_Checkout_Block_Cart_Crosssell
{
    /**
     * Get ids of products that are in cart
     *
     * Rewrite to consider 'cpid' as items in cart to have its assigned crosssells shown.
     *
     * @return array
     */
    protected function _getCartProductIds()
    {
        $ids = $this->getData('_cart_product_ids');
        if (is_null($ids)) {
            $ids = array();
            foreach ($this->getQuote()->getAllItems() as $item) {
                /** @var $item Mage_Sales_Model_Quote_Item */
                // modification start
                $itemOption = $item->getOptionByCode('info_buyRequest');
                $infoBuyRequest = unserialize($itemOption->getValue());

                if (!empty($infoBuyRequest['cpid'])) {
                    $ids[] = $infoBuyRequest['cpid'];
                }
                // modification end
                if ($product = $item->getProduct()) {
                    $ids[] = $product->getId();
                }
            }
            $this->setData('_cart_product_ids', $ids);
        }
        return $ids;
    }
}
