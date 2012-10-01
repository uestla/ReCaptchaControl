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

	/** @var string */
	protected $prefix;



	/** @param  string */
	function __construct($prefix)
	{
		$this->prefix = $prefix;
	}



	function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig();

		$container->addDefinition( $this->prefix( $this->prefix ) )
				->setClass( 'ReCaptcha\ReCaptcha', array( $config['publicKey'], $config['privateKey'] ) );
	}



	function afterCompile(Nette\Utils\PhpGenerator\ClassType $class)
	{
		$initialize = $class->methods['initialize'];
		$config = $this->getConfig( $this->defaults );

		$initialize->addBody('$context = $this;');
		$initialize->addBody('Nette\Forms\Container::extensionMethod(?, function ($container, $name, $label = NULL) use ($context) {
			return $container[$name] = new ReCaptchaControl($context->getService(?), $context->getByType(\'Nette\Http\IRequest\'), $label);
		});', array( $config['methodName'], $this->prefix( $this->prefix ) ));
	}



	static function register(Nette\Config\Configurator $configurator, $prefix = 'recaptcha')
	{
		$configurator->onCompile[] = function ($configurator, $compiler) use ($prefix) {
			$compiler->addExtension( $prefix, new ReCaptchaExtension($prefix) );
		};
	}
}
