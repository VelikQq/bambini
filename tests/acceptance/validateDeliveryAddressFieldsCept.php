<?php
//@group paracept_1

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check second address validation');
$I->openHomePage();
$I->amOnPage(\Pages\Menu::NEW_IN);
$I->goToProductFromListing();
$I->addToCartFromProductPage();
$I->goToCheckout();
$I->guestCheckout();
$chars = ['@', '1@', 'Museumsplatz @', 'Museumspl@tz'];
foreach ($chars as $char) {
    $I->fillAddress($char);
    $I->waitAndClick('//button[contains(.,"Continue")]', 'save address');
    $I->waitForVisible('//button[contains(.,"Continue")][@disabled]', 'disabled button');
}