<?php
//@group paracept_2

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('reusing voucher in cart');
$I->openHomePage();
//$amount = 0;
//do {
    $I->amOnPage(\Pages\Menu::SALE);
    $I->goToProductFromListing(null, \Pages\Listing::PROMOTION);
    $voucher = $I->getAppliedVoucherAtProductPage();
    $itemData = $I->addToCartFromProductPage();
  //  $amount += $itemData['price'];
//} while ($amount < 350);
$I->useVoucher($voucher);
$I->useVoucher($voucher, 'This voucher has been already applied', false);