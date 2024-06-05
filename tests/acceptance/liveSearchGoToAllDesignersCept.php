<?php
//@group paracept_3

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('go to all designers in live search');
$I->openHomePage();
$I->searchString('fendi', false);
$I->goToAllResultFromLiveSearch('Designers');
$I->waitForVisible('//div[@class="designers-container"]', 'all designers');