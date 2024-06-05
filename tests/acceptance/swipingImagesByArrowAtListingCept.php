<?php
//@group paracept_2
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check swiping images by arrow at listing');
$I->openHomePage();
$I->selectCategoryHomePage();
$I->checkSwipingImagesByArrowAtListing();