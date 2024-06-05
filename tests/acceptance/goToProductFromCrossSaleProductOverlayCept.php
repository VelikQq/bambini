<?php
//@group paracept_2

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('go to product from cross sale products overlay');
$I->openHomePage();
$I->selectCategoryHomePage();
$I->goToProductFromListing(1);
$I->addToCartFromProductPage('skipp');
$I->goToProductFromCarousel('//div[@id="__overlay"]');