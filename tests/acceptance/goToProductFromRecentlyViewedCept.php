<?php
//@group paracept_2

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('choose product from recently viewed at product page');
$I->openHomePage();
for ($i = 1; $i < 4; $i++ ) {
    $I->goToMenuCategory(\Pages\Menu::NEW_IN);
    $I->goToProductFromListing($i);
}

$I->moveOnCarousel();
$I->switchTab('Recently viewed');
$I->goToProductFromRecentlyViewed();