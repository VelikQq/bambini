<?php
//@group paracept_4
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check using two percent promo vouchers');
$I->openHomePage();
$I->changeCurrency('EUR');
//$amount = 0;
//do {
    $I->amOnPage(\Pages\Menu::SALE);
    $I->goToProductFromListing(null, \Pages\Listing::PROMOTION);
    $voucher = $I->getAppliedVoucherAtProductPage();
    $itemData = $I->addToCartFromProductPage();
//    $amount += $itemData['price'];
//} while ($amount < 350);
$I->useVoucher($voucher);
$I->useVoucher($I->generatePercentageVoucher(10), 'This voucher is not compatible with other vouchers applied in your cart', false);