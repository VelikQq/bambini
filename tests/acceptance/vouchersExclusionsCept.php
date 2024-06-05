<?php
//@skip true
//@group paracept_2

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check vouchers exclusions');
$I->openHomePage();
$I->amOnPage(\Pages\Menu::NEW_IN);
$I->goToProductFromListing(1);
$voucher = $I->getAppliedVoucherAtProductPage();
$I->searchString(array_rand($I->getVouchersExclusions($voucher)));
$I->goToProductFromListing(1);
$I->addToCartFromProductPage();
$I->useVoucher($voucher, 'voucher is not applicable', false);