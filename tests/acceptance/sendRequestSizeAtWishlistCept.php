<?php
//@group paracept_2

//@skip true
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check request size form at wishlist');
$I->openHomePage();
$I->amOnPage(OUT_OF_STOCK_SIZE);
$I->addToWishList();
$I->goToWishList();
$I->requestSize(\Pages\Wishlist::PRODUCT_CARD);