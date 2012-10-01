<?php


/**
 * Nette\Forms reCAPTCHA compiler extension
 *
 * @author vojtech-dobes (https://github.com/vojtech-dobes)
 */
class ReCaptchaExtension extends Nette\Config\CompilerExtension
{
	/** @var string[] */
	protected $defaults = array(
		'methodName' => 'addReCaptcha',
	);

	const PREFIX = 'recaptcha';



	function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig();

		$container->addDefinition( $this->prefix( static::PREFIX ) )
				->setClass( 'ReCaptcha\ReCaptcha', array( $config['publicKey'], $config['privateKey'] ) );
	}



	function afterCompile(Nette\Utils\PhpGenerator\ClassType $class)
	{
		$initialize = $class->methods['initialize'];
		$config = $this->getConfig( $this->defaults );

		$initialize->addBody('$context = $this;');
		$initialize->addBody('Nette\Forms\Container::extensionMethod(?, function ($container, $name, $label = NULL) use ($context) {
			return $container[$name] = new ReCaptchaControl($context->getService(?), $context->getByType(\'Nette\Http\IRequest\'), $label);
		});', array( $config['methodName'], $this->prefix( static::PREFIX ) ));
	}



	static function register(Nette\Config\Configurator $configurator)
	{
		$section = static::PREFIX;
		$configurator->onCompile[] = function ($configurator, $compiler) use ($section) {
			$compiler->addExtension( $section, new ReCaptchaExtension );
		};
	}
}
