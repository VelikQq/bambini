<?php
//@group paracept_2

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check restriction brand zone at designers page');
$I->openHomePage();
$I->setDefaultCountry();
$data = $I->getRestrictionZoneData('manufacturers');
$I->changeCountry($data['country']);
$I->goToMenuCategory(\Pages\Menu::DESIGNERS);
$I->dontSee($data['name']);