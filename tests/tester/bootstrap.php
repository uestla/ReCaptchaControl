<?php

require_once __DIR__ . '/../../vendor/autoload.php';

Tester\Environment::setup();

function dd($var) {
	array_map('dump', func_get_args());
	exit(1);
}
