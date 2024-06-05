<?php
//@group paracept_4
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('separate billing address and express delivery');
$I->openHomePage();
$I->goToMenuCategory(\Pages\Menu::BOY);
$I->goToProductFromListing();
$I->addToCartFromProductPage();
$I->goToCheckout();
$I->guestCheckout();
$I->separateBillingAddress();
$I->fillContacts();
$I->chooseShippingMethod('Express');
