<?php

/**
 * Test: DI\EventApplicationBridgeExtension
 */

use Contributte\EventDispatcher\DI\EventDispatcherExtension;
use Contributte\Events\Bridges\Application\DI\EventApplicationBridgeExtension;
use Nette\Application\Application;
use Nette\Bridges\ApplicationDI\ApplicationExtension;
use Nette\Bridges\HttpDI\HttpExtension;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\FileMock;
use Tests\Fixtures\FakeStartupSubscriber;

require_once __DIR__ . '/../../bootstrap.php';

test(function () {
	Assert::exception(function () {
		$loader = new ContainerLoader(TEMP_DIR, TRUE);
		$loader->load(function (Compiler $compiler) {
			$compiler->addExtension('events2application', new EventApplicationBridgeExtension());
		}, 1);
	}, LogicException::class, 'Service of type "Nette\Application\Application" is needed. Please register it.');
});

test(function () {
	$loader = new ContainerLoader(TEMP_DIR, TRUE);
	$class = $loader->load(function (Compiler $compiler) {
		$compiler->loadConfig(FileMock::create('
			services:
				- Nette\Application\Routers\RouteList
				fake.startup.subscriber: Tests\Fixtures\FakeStartupSubscriber
		', 'neon'));
		$compiler->addExtension('application', new ApplicationExtension());
		$compiler->addExtension('http', new HttpExtension());
		$compiler->addExtension('events', new EventDispatcherExtension());
		$compiler->addExtension('events2application', new EventApplicationBridgeExtension());
	}, 2);

	/** @var Container $container */
	$container = new $class;

	// Subscriber is still not created
	Assert::false($container->isCreated('fake.startup.subscriber'));

	/** @var Application $application */
	$application = $container->getByType(Application::class);
	Assert::count(1, $application->onStartup);

	/** @var FakeStartupSubscriber $subscriber */
	$subscriber = $container->getByType(FakeStartupSubscriber::class);
	$application->run();

	Assert::count(1, $subscriber->onCall);
	Assert::equal($application, $subscriber->onCall[0]->getApplication());
});
