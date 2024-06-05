<?php
//@group paracept_4
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check restriction products zone at search page');
$I->openHomePage();
$I->setDefaultCountry();
$data = $I->getRestrictionZoneData('tags');
$product = array_rand($I->getTagProducts($data['id']));
$I->changeCountry($data['country']);
$I->searchString($product);
$I->see('No products');