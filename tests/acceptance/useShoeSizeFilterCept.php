<?php
//@group paracept_2
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check using Shoe Size filter');
$I->openHomePage();
$I->amOnPage('shoes');
$I->sortBy('Shoe Size');
$I->clearFilter();