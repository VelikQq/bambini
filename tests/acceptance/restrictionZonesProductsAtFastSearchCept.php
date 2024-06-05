<?php
//@group paracept_3
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check restriction products zone at fast search');
$I->openHomePage();
$I->setDefaultCountry();
$data = $I->getRestrictionZoneData('tags');
$product = array_rand($I->getTagProducts($data['id']));
$I->changeCountry($data['country']);
$I->searchString($product, false);
$I->dontSee($product, '.search-list-item');