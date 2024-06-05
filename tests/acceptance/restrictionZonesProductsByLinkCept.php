<?php
//@group paracept_1

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check restriction products zone at product page');
$I->openHomePage();
$I->setDefaultCountry();
$data = $I->getRestrictionZoneData('tags');
$product = array_rand($I->getTagProducts($data['id']));
$I->searchString($product);
$I->goToProductFromListing();
$url = $I->getCurrentUrlJS();
$I->changeCountry($data['country']);
$I->amOnUrl($url);
$I->dontSee($data['country']);