<?php
//@group paracept_1

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('go to category in live search');
$I->openHomePage();
$I->searchString('ba', false);
$I->goToResultFromLiveSearch('categories');