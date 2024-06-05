<?php
//@group paracept_4

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('go to all products in live search');
$I->openHomePage();
$I->searchString('Outdoor Fun Peppa Pig Long-sleeved Blouse in Blue', false);
$I->goToAllResultFromLiveSearch('Products');
$I->waitForVisible('//div[@class="category-list"]', 'listing of all products');