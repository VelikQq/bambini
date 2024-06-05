<?php
require_once('vendor/autoload.php');
use Facebook\WebDriver\Remote\RemoteWebDriver;

function executeTestCase($caps) {
    $web_driver = RemoteWebDriver::create(
        $caps
    );

    include('./tests/acceptance/addToCartFromWishListCept.php');
    //shell_exec('sh ./browserstack.sh acceptance atomic');
}

$caps = array(
    array(
        "os" => "Windows",
        "os_version" => "10",
        "browser" => "chrome",
        "browser_version" => "latest",
        "acceptSslCerts" => "true",
        "build" => "browserstack-build-1",
        "name" => "Parallel test 1"
    )
);

foreach ( $caps as $cap ) {
    executeTestCase($cap);
}
