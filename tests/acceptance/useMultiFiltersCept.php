<?php
//@group paracept_3
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check using multi filters');
$I->openHomePage();
$I->amOnPage(\Pages\Menu::NEW_IN);
$I->multiFilter();