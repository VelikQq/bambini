<?php
//@group paracept_2

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check restriction brand zone at designer page');
$I->openHomePage();
$I->setDefaultCountry();
$data = $I->getRestrictionZoneData('manufacturers');
$I->changeCountry($data['country']);
$I->amOnPage(mb_strtolower(preg_replace('/ /', '-', $data['name'])));
$I->see('No products');