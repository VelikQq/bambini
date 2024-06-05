<?php
namespace Pages;

use Exception;

class Checkout
{
    // include url of current page
    public static $URL = '/checkout/';

    //constants
    const CARD = 'card';
    const PAYPAL = 'paypal';
    const SOFORT = 'sofort';

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
     * Guest checkout.
     *
     * @return string $email
     * @throws Exception
     */
    public function guestCheckout(): string
    {
        $I = $this->tester;

        $str = $I->generateString(5);
        $email = $str.'@bambinifashion.com';
        $I->waitForVisible('div.checkout-sections', 'checkout login');
        $I->waitAndFill(['name' => 'email'], 'email', $email);
        $I->waitAndClick('//button[@class="form-cta--primary"][not(contains(.,"Sign"))]', 'guest checkout');

        return $email;
    }

    /**
     * Enter separate billing address.
     *
     * @throws Exception
     */
    public function separateBillingAddress()
    {
        $I = $this->tester;

        $I->waitAndClick('//span[contains(.,"Enter separate billing address")]', 'enter separate billing address');
        $I->waitAndClick('button.address-form-submit', 'go to shipping');
        $I->wait(SHORT_WAIT_TIME);
        //$I->waitOverlayLoader();
    }

    /**
     * Edit or add shipping/billing address.
     *
     * @param string $type
     * @param string $func
     * @throws Exception
     */
    public function editOrAddAddress($type, $func)
    {
        $I = $this->tester;

        $I->waitAndClick('//section[contains(.,"'.$type.'")]//button[contains(.,"'.$func.'")]', $func.' '.$type.' address');
        if ($func == 'Edit') {
            $I->waitForVisible('//button[contains(.,"Save")]', 'Save button');
        }

        $str = $I->generateString(5);
        $I->waitAndFill(['name' => 'fname'], 'first name', $str);
        $I->waitAndFill(['name' => 'lname'], 'last name', $str);
        $I->waitAndFill(['name' => 'address1'], 'address line 1', $str);
        $I->waitAndFill(['name' => 'city'], 'city', BUYER_CITY);
        $I->waitAndFill(['name' => 'zip'], 'last name', BUYER_POSTCODE);
        $I->waitAndFill(['name' => 'phone'], 'phone', BUYER_PHONE);
        $I->waitAndClick('//button[contains(.,"Continue")] | //button[contains(.,"Save")]', 'save address');
        $I->waitForVisible('//section[contains(.,"'.$type.'")]//div[@class="address-display-selected"][contains(.,"'.$str.'")]', 'new address');
    }

    /**
     * Change preferred shipping/billing address.
     *
     * @param string $type
     * @throws Exception
     */
    public function changePreferredAddress($type)
    {
        $I = $this->tester;

        $I->waitAndClick('//section[contains(.,"'.$type.'")]//div[@class="form-input--select"]', 'open '.$type.' address list');
        $addresses = $I->grabMultiple('//section[contains(.,"'.$type.'")]//option[not(@disabled)]');
        /*$selected = $I->grabTextFrom('//section[contains(.,"'.$type.'")]//div[@class="address-display-selected"]');
        codecept_debug($selected);
        codecept_debug($addresses);

        foreach ($addresses as $address) {
            if (strpos($address, $selected) !== 0) {
                unset($addresses[array_search($selected, $addresses)]);
                $addresses = array_values($addresses);
            }
        }

        codecept_debug($addresses);*/
        $rndAddress = $addresses[mt_rand(0, count($addresses)-1)];
        $I->waitAndClick('//section[contains(.,"'.$type.'")]//option[contains(.,"'.$rndAddress.'")]', 'change preferred '.$type.' address');
        //$I->waitForElementVisible('//section[contains(.,"'.$type.'")]//div[@class="address-display-selected"][contains(.,"'.$rndAddress.'")]');
    }

    /**
     * Filling in personal data.
     *
     * @param string $address
     * @param string $country
     * @param string $city
     * @param string $postcode
     * @param bool $submit go to shipping
     * @throws Exception
     */
    public function fillContacts($country, $address, $city, $postcode, $submit)
    {
        $I = $this->tester;

        $I->wait(SHORT_WAIT_TIME);
        $I->waitForVisible('//div[contains(@class,"is-active")][contains(.,"Shipping")] | //form[@class="address-form"]', 'shipping or address form');
        $I->wait(SHORT_WAIT_TIME);
        $defaultAddress = $I->getNumberOfElements('div.address-display-selected');
        if (empty($defaultAddress)) {
            $this->fillAddress($country, $address, '', $city, $postcode);
            if ($submit) {
                $I->waitAndClick('//button[contains(.,"Continue")] | //button[contains(.,"Save")]', 'save address');
                //$I->waitOverlayLoader();
            }
        }
    }

    /**
     * Fill in address.
     *
     * @param string $address
     * @param string $address2
     * @param string $country
     * @param string $city
     * @param string $postcode
     * @throws Exception
     */
    public function fillAddress($country, $address, $address2, $city, $postcode)
    {
        $I = $this->tester;

        $I->waitAndFill(['name' => 'fname'], 'first name', BUYER_NAME);
        $I->waitAndFill(['name' => 'lname'], 'last name', BUYER_LAST_NAME);
        $I->waitAndFill(['name' => 'address1'], 'address line 1', $address);
        $I->waitAndFill(['name' => 'address2'], 'address line 2', $address2);
        $this->chooseCountry($country);
        $I->waitAndFill(['name' => 'city'], 'city', $city);
        $I->waitAndFill(['name' => 'zip'], 'last name', $postcode);
        $I->waitAndFill(['name' => 'phone'], 'phone', BUYER_PHONE);
    }

    /**
     * Choose country.
     *
     * @param string $country country
     * @throws Exception
     */
    public function chooseCountry($country)
    {
        $I = $this->tester;

        $I->waitAndClick(['name' => 'country'], 'open country list');
        $I->waitAndClick('//select[@name="country"]//option[.="'.$country.'"]', 'select country');
    }

    /**
     * Choose shipping method.
     *
     * @param string $type
     * @return int price
     * @throws Exception
     */
    public function chooseShippingMethod($type): int
    {
        $I = $this->tester;

        $I->waitForVisible('section.shipping-method-form-options', 'shipping methods');
        $I->waitAndClick('//div[@class="form-field"][contains(.,"'.$type.'")]', 'choose shipping method', true);
        $I->waitForVisible('//h2[contains(.,"Shipping method")]', 'Shipping methods block');
        $I->wait(SHORT_WAIT_TIME);
        $price = $I->getNumberFromLink('//label[contains(.,"'.$type.'")]//span', 'delivery price');
        $duties = $I->changeDDP();
        $I->waitAndClick('button.shipping-method-form-submit', 'continue');
        $I->totalAmountCalculation($price, 0, 0, $duties);

        return $price;
    }

    /**
     * Check delivery limits.
     *
     * @param string $country Iso-code
     * @param string $currency Iso-code
     * @throws Exception
     */
    public function checkDeliveryLimits($country, $currency)
    {
        $I = $this->tester;

        $delivery = $I->getNumberFromLink(Cart::DESKTOP_TOTAL.'//dl[contains(@class,"cart-total-item--delivery")]', 'delivery');
        $totalSum = $I->getNumberFromLink(Cart::DESKTOP_TOTAL.'/following-sibling::dl[contains(@class,"cart-total-item--total")]', 'total amount');
        $freeDeliveryLimit = $I->getCountryDeliveryLimits($country, $currency);
        if ($totalSum < $freeDeliveryLimit) {
            $I->assertNotEquals(0, $delivery, 'free shipping not equals delivery limits for '.$country);
        }
    }

    /**
     * Filling in card data.
     *
     * @param string $cardNumber
     * @throws Exception
     */
    public function fillCardData($cardNumber)
    {
        $I = $this->tester;

        $I->waitForVisible('//h2[contains(.,"Payment")]', 'payments block');
        $I->waitForVisible('//div[contains(@class, "payment-method-form-disclaimer--pay-securely")]', 'disclaimer');
        $I->moveMouseOver('//div[contains(@class, "payment-method-form-disclaimer--pay-securely")]');
        $name = $I->grabAttributeFrom('div.__PrivateStripeElement > iframe', 'name');
        $I->switchToIFrame($name);
        $I->waitForVisible(['name' => 'cardnumber'], 'card number input');
        $I->waitAndFill(['name' => 'cardnumber'], 'fill card data', $cardNumber);
        $I->waitAndFill(['name' => 'exp-date'], 'fill exp-date', '1250');
        $I->waitAndFill(['name' => 'cvc'], 'fill cvc', '232');
        $I->unFocus();
        $I->switchToPreviousTab();
    }

    /**
     * Save to link pay.
     *
     * @param string $cardNumber
     * @throws Exception
     */
    public function saveToLinkPay($cardNumber)
    {
        $I = $this->tester;

        $I->fillCardData($cardNumber);
        $name = $I->grabAttributeFrom('div.__PrivateStripeElement > div > iframe', 'name');
        $I->switchToIFrame($name);
        $I->waitAndClick('#link-save', 'autofill link');
        $I->wait(SHORT_WAIT_TIME);
        $I->switchToNextTab();
        $I->waitAndFill('#search-email', 'email', $I->generateString(5).'@bambinifashion.com');
        $I->waitAndFill('//input[@type="tel"]', 'phone', '1521770');
        $I->waitAndClick(['class' => 'PhoneNumberCountrySelect-iconWrapper'], 'country list');
        $I->waitAndClick('//option[@value="AT"]', 'Austria');
        $I->waitAndClick('//span[.="Save and join Link"]', 'Save and join Link button');
        $I->switchToPreviousTab();
        $name = $I->grabAttributeFrom('div.__PrivateStripeElement > div > iframe', 'name');
        $I->switchToIFrame($name);
        $I->waitForVisible('#link-manage', 'accepted card');
        $I->switchToPreviousTab();
    }

    /**
     * Sing up in link pay.
     *
     * @param string $cardNumber
     * @throws Exception
     */
    public function linkPaySignUp($cardNumber)
    {
        $I = $this->tester;

        $name = $I->grabAttributeFrom('div.__PrivateStripeElement > div > iframe', 'name');
        $I->switchToIFrame($name);
        $I->waitAndClick('#link-pay', 'autofill link');
        $I->wait(SHORT_WAIT_TIME);
        $I->switchToNextTab();
        $I->waitAndFill('#search-email', 'email', $I->generateString(5).'@bambinifashion.com');
        $I->waitAndFill('//input[@type="tel"]', 'phone', '1521770');
        $I->waitAndClick(['class' => 'PhoneNumberCountrySelect-iconWrapper'], 'country list');
        $I->waitAndClick('//option[@value="AT"]', 'Austria');
        $I->waitAndClick('//span[.="Add payment method"]', 'Add payment method button');
        $I->waitAndFill(['name' => 'cardNumber'], 'fill card data', $cardNumber);
        $I->waitAndFill(['name' => 'cardExpiry'], 'fill exp-date', '1250');
        $I->waitAndFill(['name' => 'cardCvc'], 'fill cvc', '232');
        $I->waitAndFill(['name' => 'billingAddress.name'], 'fill cvc', BUYER_NAME);
        $I->waitAndClick('//span[.="Continue to checkout"]', 'Continue to checkout button');
        $I->switchToPreviousTab();
        $name = $I->grabAttributeFrom('div.__PrivateStripeElement > div > iframe', 'name');
        $I->switchToIFrame($name);
        $I->waitForVisible('#link-manage', 'accepted card');
        $I->switchToPreviousTab();
    }

    /**
     * Save credit card.
     *
     * @throws Exception
     */
    public function saveCreditCard()
    {
        $I = $this->tester;

        $I->waitAndClick('//label[@for="stripe-card-element-save"]', 'save card');
        $I->seeCheckboxIsChecked('#stripe-card-element-save');
    }

    /**
     * Check saved credit card.
     *
     * @throws Exception
     */
    public function checkSavedCard()
    {
        $I = $this->tester;

        $I->waitForVisible('span.payment-method-form-field-label-image-lastfour', 'saved card');
        $lastFour = $I->getNumberFromLink('span.payment-method-form-field-label-image-lastfour', 'last four card numbers');
        $I->assertStringContainsString($lastFour, BUYER_STRIPES['nonAuth'], 'wrong card number');
    }

    /**
     * Delete saved credit card.
     *
     * @throws Exception
     */
    public function deleteSavedCard()
    {
        $I = $this->tester;

        $I->waitAndClick('button.payment-method-form-field-remove', 'delete saved card');
        $I->waitAndClick('//button[contains(.,"Confirm removal")]', 'Confirm removal');
        $I->waitForNotVisible('span.payment-method-form-field-label-image-lastfour', 'saved card');
    }

    /**
     * Change shipping.
     *
     * @throws Exception
     */
    public function changeShipping()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(.,"Change Shipping")]', 'change shipping');
        $I->waitForVisible('section.shipping-method-form-selector', 'shipping methods');
    }

    /**
     * Add payment method.
     *
     * @throws Exception
     */
    public function addPaymentMethod()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(.,"Add Payment Method")]', 'add payment');
        $I->waitForVisible('div.payment-method-form-methods', 'payment methods');
    }

    /**
     * Complete 3D Secure.
     *
     * @param bool $authorize
     * @throws Exception
     */
    public function complete3DSecure($authorize)
    {
        $I = $this->tester;

        $I->waitForVisible('(//iframe[contains(@name, "__privateStripeFrame")])[1]', '3D secure iframe');
        $I->wait(MIDDLE_WAIT_TIME);
        $name = $I->grabAttributeFrom('body > div:nth-child(1) > iframe', 'iframe name');
        $I->switchToIFrame($name);
        $I->waitForVisible('#challengeFrame', 'challenge iframe');
        $I->switchToIFrame('__stripeJSChallengeFrame');
        $I->waitForVisible('div.FrameWrapper', 'ascFrame');
        $I->switchToIFrame('acsFrame');
        if ($authorize) {
            $I->waitAndClick('#test-source-authorize-3ds', 'complete authentication');
        } else {
            $I->waitAndClick('#test-source-fail-3ds', 'fail authentication');
            $I->switchToPreviousTab();
            $I->dismissAlert('unable to authenticate');
        }

        $I->switchToPreviousTab();
    }

    /**
     * Dismiss alert.
     *
     * @param string $message
     * @throws Exception
     */
    public function dismissAlert($message)
    {
        $I = $this->tester;

        $I->waitForVisible(Home::OVERLAY.'[contains(.,"'.$message.'")]', 'allert message '.$message);
        $I->waitAndClick('//button[contains(@class,"overlay-dismiss")]', 'close pop up');
    }

    /**
     * Choose payment type.
     *
     * @param string $paymentType
     * @throws Exception
     */
    public function choosePayment($paymentType)
    {
        $I = $this->tester;

        $I->waitForVisible('ul.checkout-nav-expandable-list', 'expandable payments methods list');
        $I->waitForVisible('div.payment-method-form-methods', 'payments methods');
        $I->waitForVisible('//label[contains(@for,"'.$paymentType.'")]', 'payment method '.$paymentType);
        $I->waitAndClick('//label[contains(@for,"'.$paymentType.'")]', 'select payment type '.$paymentType);
    }

    /**
     * Filling in sofort form.
     *
     * @return array Sofort data
     * @throws Exception
     */
    public function fillSofort(): array
    {
        $I = $this->tester;

        //$I->waitForVisible('#Modal', 'modal form');
        $I->waitUntilJs('document.querySelector("#cookie-modal-basic")');
        $I->waitAndClick('(//div[@id="modal-button-container"]//button[contains(@class,"cookie-modal-accept-all")])[last()]', 'accept cookie');
        $I->waitForNotVisible('#cookie-modal-basic', 'modal form');
        $I->waitForVisible('#BankCodeSearch', 'bank code input');
        $I->waitAndFill('#BankCodeSearch', 'bank code', SOFORT_CODE['other']);
        $I->waitForVisible('//p[@data-label="demo-bank"]', 'demo bank');
        $I->waitAndClick('//p[@data-label="demo-bank"]', 'Next button');
        $I->waitForVisible('//h1[contains(.,"Demo Bank")]', 'Demo Bank title');
        $I->waitAndFill('#BackendFormLOGINNAMEUSERID', 'account number', SOFORT_CODE['other']);
        $I->waitAndFill('#BackendFormUSERPIN', 'pin code', SOFORT_CODE['other']);
        $I->waitAndClick('//button[contains(.,"Next")]', 'Next button');
        $I->waitForVisible('//h1[contains(.,"Select Account")]', 'Select Account title');
        $accountsCount = $I->getNumberOfElements('//label[contains(@for,"account-")]');
        $accountNum = mt_rand(1, $accountsCount);
        $I->waitAndClick('//label[contains(@for,"account-'.$accountNum.'")]', 'select account');
        $I->waitAndClick('//button[contains(.,"Next")]', 'Next button');
        $I->waitForVisible('//h1[contains(.,"Transaction confirmation")]', 'Transaction confirmation title');
        $I->waitAndFill('#BackendFormTan', 'tan', '12345');
        $amount = $I->grabTextFrom('p.amount.js-toggle-details');
        $I->waitAndClick('p.amount.js-toggle-details', 'expand');
        $I->waitForVisible('#TransactionIdRawAdditionalInfo', 'Transaction additional info');
        $transactionId = $I->grabTextFrom('#TransactionIdRawAdditionalInfo');
        $I->waitAndClick('//button[contains(.,"Next")]', 'Next button');

        return array('amount' => $amount, 'transactionId' => $transactionId);
    }

    /**
     * Filling in PayPal.
     *
     * @return string $amount
     * @throws Exception
     */
    public function fillPayPal(): string
    {
        $I = $this->tester;

        $I->waitForVisible('#acceptAllButton', 'Accept cookie button');
        $I->clickJs('document.querySelector("#acceptAllButton")');
        $I->waitAndFill('#email', 'login', PAYPAL_LOGIN);
        $I->waitAndClick('#btnNext', 'Next button');
        $I->waitAndFill('#password', 'password', PAYPAL_PASS);
        $I->waitAndClick('#btnLogin', 'Login button');
        //$I->waitForVisible('#acceptAllButton', 'Accept cookie button');
        //$I->clickJs('document.querySelector("#acceptAllButton")');
        $I->waitForVisible('//div[@data-testid="stacked-payment-options"]', 'payment options');
        $amount = $I->getNumberFromLink('#cart', 'total amount');
        $I->waitForVisible('//div[starts-with(@class,"FundingInstrument_item")]', 'payments');
        $selectedPayment = $I->grabTextFrom('//div[@aria-hidden="false"]/../div[starts-with(@class,"FundingInstrument_item")]//span[contains(@data-testid,"name")]');
        if ($selectedPayment == 'Rabobank Nederland') {
            $I->moveMouseOver('#payment-submit-btn');
            $I->waitAndClick('#payment-submit-btn', 'submit payment', true);
        } else {
            $I->moveMouseOver('#payment-submit-btn');
            $I->waitAndClick('#payment-submit-btn', 'Submit payment button', true);
        }

        return $amount;
    }

    /**
     * Confirm order.
     *
     * @return string $totalSum
     * @throws Exception
     */
    public function confirmOrder(): string
    {
        $I = $this->tester;

        $totalSum = $I->getNumberFromLink('dl.cart-total-item--total', 'total amount');
        $I->waitAndClick('button.payment-method-form-submit', 'confirm order');

        return $totalSum;
    }

    /**
     * See thank-you page.
     *
     * @throws Exception
     */
    public function seeThankYouPage()
    {
        $I = $this->tester;

        $I->waitForVisible('//h1[contains(.,"Thank you")]', '"Thank you" message');
    }

    /**
     * Getting the order number from the "thank you for order" page.
     *
     * @return string Order number
     * @throws Exception
     */
    public function getOrderNumber(): string
    {
        $I = $this->tester;

        return $I->grabTextFrom('span.order-reference');
    }

    /**
     * View order.
     *
     * @param string $orderNum
     * @throws Exception
     */
    public function viewOrder($orderNum)
    {
        $I = $this->tester;

        $I->waitAndClick('//a[.="View order"]', 'view order');
        $I->waitForVisible('//span[.="'.$orderNum.'"]', 'order '.$orderNum);
        $I->waitForVisible('//span[.="Completed"]', 'Completed status');
    }

    /**
     * Change Import & Duty Services.
     *
     * @param bool $active true = included
     * @return int $duties
     * @throws Exception
     */
    public function changeDDP($active): int
    {
        $I = $this->tester;

        $dutiesBlock = $I->getNumberOfElements('//h2[contains(.,"Import & Duty Services")]', 'Import & Duty Services block');
        $duties = 0;
        if (!empty($dutiesBlock)) {
            if (!$active) {
                $I->waitAndClick('//div[@class="form-field"][contains(.,"Not included")]', 'no included DDP');
            } else {
                $duties = $I->getNumberFromLink('//div[@class="form-field"][contains(.,"Included")]', 'import duties');
            }
        }

        return $duties;
    }
}