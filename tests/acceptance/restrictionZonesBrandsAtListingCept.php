<?php
//@group paracept_3
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check restriction brand zone at listing');
$I->openHomePage();
$I->setDefaultCountry();
$data = $I->getRestrictionZoneData('manufacturers');
$I->changeCountry($data['country']);
$I->goToMenuCategory(\Pages\Menu::NEW_IN);
$I->expandFilter('Designers');
$I->dontSee($data['name']);