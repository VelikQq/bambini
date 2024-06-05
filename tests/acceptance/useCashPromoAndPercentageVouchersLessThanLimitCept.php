<?php
//@group paracept_1

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check using cash promo and percentage vouchers less than limits');
$I->openHomePage();
$amount = 0;
do {
    $I->amOnPage(\Pages\Menu::NEW_IN);
    $I->goToProductFromListing();
    $itemData = $I->addToCartFromProductPage();
    $amount += $itemData['price'];
} while ($amount < 400);
$I->useVoucher(CASH_PROMO_VOUCHER, '', true, 50);
$I->useVoucher($I->generatePercentageVoucher(50), '', false);
$I->waitForNotVisible(\Pages\Cart::DESKTOP_VOUCHER.'//div[@class="voucher-display-info-title"][contains(., "'.CASH_PROMO_VOUCHER.'")]', 'cash promo voucher');
$I->totalAmountCalculation();