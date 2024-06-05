<?php
//@group paracept_1

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('select image at page and in overlay');
$I->openHomePage();
$I->selectCategoryHomePage();
$I->goToProductFromListing();
$I->selectImage();
$I->selectBigImage();