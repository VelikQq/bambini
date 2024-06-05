<?php
//@group paracept_1
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check filter by designers with search');
$I->openHomePage();
$I->goToMenuCategory(\Pages\Menu::NEW_IN);
$I->sortByDesigners(true);
$I->clearFilter();