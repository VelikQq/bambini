<?php
//@group paracept_4
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check quick filter');
$I->openHomePage();
$I->amOnPage(\Pages\Menu::NEW_IN);
$I->quickFilters();