<?php

use Tracy\Debugger;
use Nette\Forms\Form;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/loader.php';

Debugger::enable(Debugger::DEVELOPMENT, FALSE);


$siteKey = 'YOUR_SITE_KEY';
$secretKey = 'YOUR_SECRET_KEY';


ReCaptchaControl\ReCaptchaControl::register(
		(new Nette\Http\RequestFactory)->createHttpRequest(),
		new ReCaptchaControl\ReCaptcha($siteKey, $secretKey)
);


$form = new Form;
$form->addReCaptcha('recaptcha', NULL, "Please prove you're not a robot.");
$form->addSubmit('send', 'Send form')->setAttribute('class', 'btn btn-block btn-lg btn-success');

$form->onSuccess[] = function () {
	echo 'Passed!'; die();
};

$form->fireEvents();

?>
<!doctype html>
<html>
	<head>
		<title>ReCAPTCHA test</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/readable/bootstrap.min.css" rel="stylesheet">
	</head>

	<body>
		<div class="container">
			<h1 class="page-header">reCAPTCHA for Nette\Forms</h1>
			<?php echo $form; ?>
		</div>

		<script type="text/javascript" src="../vendor/nette/forms/src/assets/netteForms.js"></script>
		<script type="text/javascript" src="../assets/recaptcha.js"></script>
	</body>
</html>
