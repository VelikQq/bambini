<?php
//@group paracept_1
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check using two fixed amount vouchers');
$I->openHomePage();
$I->changeCurrency('EUR');
//$amount = 0;
//do {
    $I->amOnPage(\Pages\Menu::NEW_IN);
    $I->goToProductFromListing(null, \Pages\Listing::PROMOTION);
    $itemData = $I->addToCartFromProductPage();
//    $amount += $itemData['price'];
//} while ($amount < 350);
$I->useVoucher($I->generateFixedAmountVoucher(25), '', true, 25);
$I->useVoucher($I->generateFixedAmountVoucher(25), '', true, 25);