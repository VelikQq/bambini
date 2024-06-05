<?php
//@group paracept_4
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check using cash promo with percent promo vouchers');
$I->openHomePage();
$I->changeCurrency('EUR');
$amount = 0;
do {
    $I->amOnPage(\Pages\Menu::SALE);
    $I->goToProductFromListing(null, \Pages\Listing::PROMOTION);
    $voucher = $I->getAppliedVoucherAtProductPage();
    $itemData = $I->addToCartFromProductPage();
    $amount += $itemData['price'];
} while ($amount < 450);
$I->useVoucher($voucher);
$I->useVoucher(CASH_PROMO_VOUCHER, '', true, 50);