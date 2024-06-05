<?php
//@group paracept_2

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('go to designer in live search');
$I->openHomePage();
$I->searchString('fendi', false);
$I->goToResultFromLiveSearch('brands');