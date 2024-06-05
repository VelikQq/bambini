<?php
//@group paracept_2

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check using invalid voucher in order');
$I->openHomePage();
$I->goToMenuCategory(\Pages\Menu::BOY);
$I->goToProductFromListing(1);
$I->addToCartFromProductPage();
$I->useVoucher(INVALID_VOUCHER, 'voucher not found', false);