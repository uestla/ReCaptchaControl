<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/keys.php';

Tester\Environment::setup();

function dd($var) {
	array_map('dump', func_get_args());
	exit(1);
}
