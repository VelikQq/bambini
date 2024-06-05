<?php
//@group paracept_3
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check using Age filter');
$I->openHomePage();
$I->amOnPage('girl/dresses');
$I->sortBy('Age');
$I->clearFilter();