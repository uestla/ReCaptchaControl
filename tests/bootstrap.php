<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/keys.php';

Tester\Environment::setup();


function test(callable $t) {
	$t();
}
