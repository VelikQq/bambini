<?php
//@group paracept_4
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check restriction brand zone at search page');
$I->openHomePage();
$I->setDefaultCountry();
$data = $I->getRestrictionZoneData('manufacturers');
$I->changeCountry($data['country']);
$I->searchString($data['name']);
$I->assertEmpty($I->getNumberOfElements('//div[@class="product-card-brand"][.="'.$data['name'].'"]'), 'restricted brand still available');