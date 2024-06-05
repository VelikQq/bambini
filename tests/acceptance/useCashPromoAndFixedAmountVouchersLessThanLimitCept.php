<?php
//@group paracept_4
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check using cash promo and fixed vouchers less than limits');
$I->openHomePage();
$amount = 0;
do {
    $I->amOnPage(\Pages\Menu::SHOES);
    $I->goToProductFromListing();
    $itemData = $I->addToCartFromProductPage();
    $amount += $itemData['price'];
} while ($amount < 400);
$I->useVoucher(CASH_PROMO_VOUCHER, '', true, 50);
$I->useVoucher($I->generateFixedAmountVoucher(200), '', true, 200);
$I->waitForVisible(\Pages\Cart::DESKTOP_VOUCHER.'//div[@class="voucher-display-info-title"][contains(., "'.CASH_PROMO_VOUCHER.'")]', 'fix cash promo voucher');