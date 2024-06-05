<?php
//@group paracept_1
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check swiping carousel in product overlay');
$I->openHomePage();
$I->selectCategoryFromFooter();
$I->goToProductFromListing(1);
$I->addToCartFromProductPage('skipp');
$I->checkSwipingProductsCarousel('//div[@id="__overlay"]');