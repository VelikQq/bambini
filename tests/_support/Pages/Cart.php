<?php
namespace Pages;

use Exception;

class Cart
{
    // include url of current page
    public static $URL = '/shopping-bag/';

    //constants
    const CART_TOP = 'a.top-user-cart';
    const CART_ITEM = '//li[@class="cart-item"]';
    const DESKTOP_VOUCHER = '//div[contains(@class, "cart-summary-voucher--desktop") or contains(@class, "checkout-section--voucher")]';
    const DESKTOP_TOTAL = '//div[contains(@class, "cart-total--desktop") or contains(@class, "cart-total")]/div[contains(@class,"is-expanded")]';
    const PERCENTAGE_DISCOUNT_PATH = self::DESKTOP_TOTAL.'//dl[contains(@class,"cart-total-item--discount")]';
    const FIXED_DISCOUNT_PATH = self::DESKTOP_TOTAL.'//dl[contains(@class,"cart-total-item--credit")]';
    const USED_VOUCHER = self::DESKTOP_VOUCHER.'//div[@class="cart-voucher-entries"]';

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @var AcceptanceTester
     */
    protected $tester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }

    /**
     * Filling in the voucher field.
     *
     * @param string $voucher
     * @param string $message error message
     * @param bool $active active or inactive voucher
     * @param int $fixedVoucher
     * @return array order data
     * @throws Exception
     */
    public function useVoucher($voucher, $message, $active, $fixedVoucher): array
    {
        $I = $this->tester;

        $orderData = [];

        $I->waitForVisible(self::DESKTOP_VOUCHER.' | //div[contains(@class, "checkout-section--voucher")]', 'voucher block');
        $previousPercentageDiscount = $I->getNumberOfElements(self::PERCENTAGE_DISCOUNT_PATH);
        $percentageDiscount = 0;
        if (!empty($previousPercentageDiscount)) {
            $percentageDiscount = $I->getNumberFromLink(self::PERCENTAGE_DISCOUNT_PATH, 'percentage discount');
        }

        $previousFixedDiscount = $I->getNumberOfElements(self::FIXED_DISCOUNT_PATH);
        $fixedDiscount = 0;
        if (!empty($previousFixedDiscount)) {
            $fixedDiscount = $I->getNumberFromLink(self::FIXED_DISCOUNT_PATH, 'fixed discount');
        }

        $previousDiscount = array('percent' => $percentageDiscount, 'fixed' => $fixedDiscount);
        $I->waitAndFill(self::DESKTOP_VOUCHER.'//input', 'voucher', $voucher);
        $I->waitAndClick(self::DESKTOP_VOUCHER.'//button[contains(.,"Submit")]', 'apply voucher');
        $I->wait(MIDDLE_WAIT_TIME);
        if ($active) {
            $I->waitForVisible(self::USED_VOUCHER, 'used voucher');
            $I->waitForVisible(self::DESKTOP_VOUCHER.'//div[@class="voucher-display-header"]', 'used voucher');
            $I->waitForVisible(self::PERCENTAGE_DISCOUNT_PATH.' | '.self::FIXED_DISCOUNT_PATH, 'cart discount');
            $orderData = $I->totalAmountCalculation(null, $fixedVoucher, $previousDiscount);
        } elseif (!empty($message)) {
            $I->dismissAlert($message);
        }

        return $orderData;
    }

    /**
     * Calculation of the total amount.
     *
     * @param int $delivery
     * @param int $fixedVoucher
     * @param int $duties
     * @param array $previousDiscount
     * @return array order data
     * @throws Exception
     */
    public function totalAmountCalculation($delivery, $fixedVoucher, $previousDiscount, $duties): array
    {
        $I = $this->tester;

        $I->waitForVisible(['class' => 'cart-total'], 'cart total amount');
        $itemsTotal = $I->getNumberFromLink(self::DESKTOP_TOTAL.'//dl[@class="cart-total-item"]', 'items total');

        if (is_null($delivery)) {
            $delivery = 0;
        }

        $deliveryActive = $I->getNumberOfElements(self::DESKTOP_TOTAL.'//dl[contains(@class,"cart-total-item--delivery")]', 'delivery');
        if (!empty($deliveryActive)) {
            $delivery = $I->getNumberFromLink(self::DESKTOP_TOTAL.'//dl[contains(@class,"cart-total-item--delivery")]', 'delivery');
        }

        if (empty($previousDiscount)) {
            $previousDiscount = array('percent' => 0, 'fixed' => 0);
        }

        $percentVoucherActive = $I->getNumberOfElements('//div[@class="voucher-display-header"][contains(.,"Extra") or contains(.,"EXTRA") or contains(.,"percent") or contains(.,"%")]', 'percent voucher');
        $percentVoucherDiscount = 0;
        if (!empty($percentVoucherActive) && empty($fixedVoucher)) {
            $text = preg_replace("/[E2E]/", "", $I->grabTextFrom(self::DESKTOP_VOUCHER.'//div[@class="voucher-display-info-description"][contains(.,"Extra") or contains(.,"EXTRA") or contains(.,"percent") or contains(.,"%")]'));
            $percent = preg_replace("/\D/", "", $text);
            $percentVoucherDiscount = $I->getNumberFromLink(self::PERCENTAGE_DISCOUNT_PATH, 'percent cart discount');
            $I->assertTrue((round(($itemsTotal * $percent)/100, 0) - ($percentVoucherDiscount - $previousDiscount['percent'])) <= 1, 'incorrect discount percent');
        }

        if (empty($percentVoucherDiscount)) {
            $percentVoucherDiscount = $previousDiscount['percent'];
        }

        $fixedVoucherActive = $I->getNumberOfElements('(//div[@class="voucher-display-header"])[last()][contains(.,"fixed") or contains(.,"'.CASH_PROMO_VOUCHER.'")]', 'fixed voucher');
        $fixedVoucherAmount = 0;
        if (!empty($fixedVoucherActive)) {
            $voucherPath = '('.self::DESKTOP_VOUCHER.'//div[@class="voucher-display-info-title"])[last()]';
            $fixedVoucher = preg_replace("/\D/", "", str_replace('E2E', '', $I->grabTextFrom($voucherPath.'[contains(.,"fixed") or contains(.,"'.CASH_PROMO_VOUCHER.'")]')));
            if (!empty($I->getNumberOfElements($voucherPath.'[contains(.,"'.CASH_PROMO_VOUCHER.'")]'))) {
                $fixedVoucherAmount = ($I->getNumberFromLink(self::PERCENTAGE_DISCOUNT_PATH, 'total cash promo voucher discount') - $percentVoucherDiscount);
            } else {
                $fixedVoucherAmount = $I->getNumberFromLink(self::FIXED_DISCOUNT_PATH, 'total fixed voucher discount');
            }
        }

        $I->wait(SHORT_WAIT_TIME);
        $totalSum = $I->getNumberFromLink(self::DESKTOP_TOTAL.'/following-sibling::dl[contains(@class,"cart-total-item--total")]', 'total amount');
        $expectedSum = $itemsTotal + $delivery - $percentVoucherDiscount - $fixedVoucherAmount + $duties;
        if ($expectedSum == -1) $expectedSum = 0; //for correct rounding, available only for -1
        $I->assertEquals($totalSum, $expectedSum, 'wrong total amount');

        return array('totalSum' => $totalSum, 'itemsTotal' => $itemsTotal, 'delivery' => $delivery, 'fixedVoucher' => $fixedVoucher, 'previousDiscount' => array_sum($previousDiscount), 'duties' => $duties);
    }

    /**
     * Calculation of the subtotal amount.
     *
     * @throws Exception
     */
    public function subTotalAmountCalculation()
    {
        $I = $this->tester;

        $I->waitForVisible(self::CART_ITEM, 'items');
        $productsCount = $I->getNumberOfElements(self::CART_ITEM);
        $totalProductsPrice = 0;
        for ($i = 1; $i <= $productsCount; $i++) {
            $price = $I->getNumberFromLink(self::CART_ITEM.'['.$i.']//span[contains(@class,"product-price-regular") and not(contains(@class,"is-reduced")) or contains(@class,"product-price-reduced")]', 'product price');
            $totalProductsPrice += $price;
        }

        //$subTotal = $I->getNumberFromLink(['class' => 'cart-total-item--subtotal'], 'sub-total');
        $itemsTotal = $I->getNumberFromLink(self::DESKTOP_TOTAL.'//dl[@class="cart-total-item"]', 'items total');
        $I->assertEquals($itemsTotal, $totalProductsPrice, 'wrong sub-total amount');
    }

    /**
     * Dismiss voucher.
     *
     * @throws Exception
     */
    public function dismissVoucher()
    {
        $I = $this->tester;

        $I->waitAndClick(self::DESKTOP_VOUCHER.'//button[@class="alert-dismiss"]', 'dismiss voucher');
        $I->waitForNotVisible(self::USED_VOUCHER, 'used voucher');
        $I->waitForNotVisible(self::PERCENTAGE_DISCOUNT_PATH, 'discount');
        $I->totalAmountCalculation();
    }

    /**
     * Clean cart.
     *
     * @throws Exception
     */
    public function cleanCart()
    {
        $I = $this->tester;

        $I->waitForVisible(self::CART_TOP, 'cart in header');
        $itemsCount = $I->getNumberFromLink(self::CART_TOP, 'item count in cart');
        if (!empty($itemsCount)) {
            $I->goToCart();
            for ($i = 1; $i <= $itemsCount; $i++) {
                $I->waitAndClick('button.cart-item-quantity-remove', 'remove from bag');
            }

            $I->waitForVisible('button.cart-empty-continue', 'continue button');
            $I->amOnPage('/');
        }
    }

    /**
     * Delete item.
     *
     * @throws Exception
     */
    public function deleteItem()
    {
        $I = $this->tester;

        $items = $I->grabMultiple(self::CART_ITEM.'//h3');
        $item = $items[mt_rand(0, count($items)-1)];
        $I->waitAndClick(self::CART_ITEM.'[contains(.,"'.$item.'")]//button[contains(.,"Remove")]', 'remove from bag');
        $I->waitForNotVisible(self::CART_ITEM.'[contains(.,"'.$item.'")]//button[contains(.,"Remove")][@disabled="disabled"]', 'disabled Remove button');
    }

    /**
     * Go to checkout.
     *
     * @throws Exception
     */
    public function goToCheckout()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[@class="cart-payment-methods"]//button', "go to checkout");
        $I->waitPage();
    }

    /**
     * Go to product.
     *
     * @throws Exception
     */
    public function goToProduct()
    {
        $I = $this->tester;

        $I->waitForVisible(self::CART_ITEM, 'items');
        $itemsCount = $I->getNumberOfElements(self::CART_ITEM, 'items count');
        $rndNum = mt_rand(1, $itemsCount);
        $itemName = $I->grabTextFrom(self::CART_ITEM.'['.$rndNum.']//h3');
        $I->waitAndClick(self::CART_ITEM.'['.$rndNum.']//a', 'go to product');
        $I->waitForVisible('//h1[.="'.$itemName.'"]', 'item '.$itemName);
    }

    /**
     * Check delivery & return.
     *
     * @throws Exception
     */
    public function checkDeliveryAndReturn()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(.,"Delivery & Return")]', 'open delivery and return');
        $this->checkDeliveryCalculator();
    }

    /**
     * Check delivery calculator.
     *
     * @throws Exception
     */
    public function checkDeliveryCalculator()
    {
        $I = $this->tester;

        $I->waitForVisible('//select[@id="delivery-calculator-country-input-select"]', 'delivery calculator');
        $country = $I->grabTextFrom('//select[@id="delivery-calculator-country-input-select"]');
        $I->waitAndClick('//select[@id="delivery-calculator-country-input-select"]', 'open country list');
        $countries = $I->grabMultiple('//select[@id="delivery-calculator-country-input-select"]//option[not(@disabled)]');
        unset($countries[array_search($country, $countries)]);
        $rndCountry = $countries[mt_rand(0, count($countries)-1)];
        $I->waitAndClick('//select[@id="delivery-calculator-country-input-select"]//option[.="'.$rndCountry.'"]', 'select country');
    }

    /**
     * Continue shopping.
     *
     * @throws Exception
     */
    public function continueShopping()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(.,"Continue shopping")]', 'continue shopping');
        $I->waitForVisible(Listing::PRODUCT_CARD, 'product card');
    }

    /**
     * Check countdown appear.
     *
     * @throws Exception
     */
    public function checkCountdownAppear()
    {
        $I = $this->tester;

        $I->waitForVisible('//span[contains(.,"Your Bag will be reserved for")]', 'countdown');
    }

    /**
     * Check countdown disappear.
     *
     * @throws Exception
     */
    public function checkCountdownDisappear()
    {
        $I = $this->tester;

        $I->waitForNotVisible('//span[contains(.,"Your Bag will be reserved for")]', 'countdown');
        $I->waitForVisible('div.countdown-finished', 'countdown finish');
    }

    /**
     * Change item quantity.
     *
     * @throws Exception
     */
    public function changeItemQuantity()
    {
        $I = $this->tester;

        $itemsCount = $I->getNumberOfElements(self::CART_ITEM);
        $rndItem = mt_rand(1, $itemsCount);
        $quantityXpath = self::CART_ITEM.'['.$rndItem.']//select';
        $I->waitAndClick($quantityXpath, 'expand quantity list');
        $optionsCount = $I->getNumberOfElements($quantityXpath.'//option');
        $I->assertTrue($optionsCount <= 10, 'available quantity more than 10');
        $rndNum = mt_rand(1, $optionsCount);
        $I->waitAndClick($quantityXpath.'//option['.$rndNum.']', 'select quantity');
        $I->waitForNotVisible('//select[@id="cart-item-quantity-input-select"][@disabled="disabled"]', 'disabled quantity input');
    }
}