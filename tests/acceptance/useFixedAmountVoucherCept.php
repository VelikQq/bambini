<?php
//@group paracept_3
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check using fixed amount voucher');
$I->openHomePage();
$I->changeCurrency('EUR');
$I->goToMenuCategory(\Pages\Menu::NEW_IN);
$I->goToProductFromListing(1);
$I->addToCartFromProductPage();
$I->useVoucher($I->generateFixedAmountVoucher(25), '', true, 25);