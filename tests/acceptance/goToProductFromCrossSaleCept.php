<?php
//@group paracept_4
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('choose product from cross sale at product page');
$I->openHomePage();
$I->selectCategoryHomePage();
$I->goToProductFromListing(1);
$I->moveOnCarousel();
$I->goToProductFromCrossSale();