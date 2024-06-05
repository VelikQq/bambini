<?php
//@group paracept_4
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check filter by designers');
$I->openHomePage();
$I->goToMenuCategory(\Pages\Menu::NEW_IN);
$I->sortByDesigners(false, 'All Brands');
$I->clearFilter();