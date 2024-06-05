<?php
//@group paracept_3
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('go to product in live search');
$I->openHomePage();
$I->searchString('Outdoor Fun Peppa Pig Long-sleeved Blouse in Blue', false);
$I->goToResultFromLiveSearch('products');