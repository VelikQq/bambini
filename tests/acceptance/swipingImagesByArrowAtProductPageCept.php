<?php
//@group paracept_1

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check swiping images by arrow at page and in overlay');
$I->openHomePage();
$I->selectCategoryHomePage();
$I->goToProductFromListing(1);
$I->checkSwipingImagesByArrow();
$I->checkSwipingBigImagesByArrow();