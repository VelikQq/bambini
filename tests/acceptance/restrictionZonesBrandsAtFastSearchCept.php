<?php
//@group paracept_1

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check restriction brand zone at fast search');
$I->openHomePage();
$I->setDefaultCountry();
$data = $I->getRestrictionZoneData('manufacturers');
$I->changeCountry($data['country']);
$I->searchString($data['name'], false);
$I->dontSee($data['name'], '.search-list-item');