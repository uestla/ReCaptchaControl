<?php


/**
 * Nette\Forms reCAPTCHA compiler extension
 *
 * @author vojtech-dobes (https://github.com/vojtech-dobes)
 */
class ReCaptchaExtension extends Nette\Config\CompilerExtension
{

	/** @var string[] */
	private $defaults = array(
		'methodName' => 'addReCaptcha',
	);



	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig();

		$container->addDefinition($this->prefix('recaptcha'))
			->setClass('ReCaptcha\ReCaptcha', array(
				$config['publicKey'], $config['privateKey'],
			));
	}



	public function afterCompile(Nette\Utils\PhpGenerator\ClassType $class)
	{
		$initialize = $class->methods['initialize'];
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$initialize->addBody('$context = $this;');
		$initialize->addBody('Nette\Forms\Container::extensionMethod(?, function ($container, $name, $label = NULL) use ($context) {
			return $container[$name] = new ReCaptchaControl($context->getService(?), $context->getByType(\'Nette\Http\IRequest\'), $label);
		});', array($config['methodName'], $this->prefix('recaptcha')));
	}

}
