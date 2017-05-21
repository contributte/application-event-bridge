# Event Dispatcher > Nette Application Bridge

## Content :gift:

- [Usage - how to register](#usage)
- [Bridge - nette application](#bridge)
- [Command - example command](#subscriber)

## Usage :tada:

```yaml
extensions:
    events: Contributte\EventDispatcher\DI\EventDispatcherExtensions
    events2application: Contributte\Application\Bridges\Events\DI\EventDispatcherExtensions
```

## Bridge :wrench:

There are several Nette Application events on which you can listen to.

```php
use Contributte\Events\Bridges\Application\Event\ApplicationEvents;
use Contributte\Events\Bridges\Application\Event\ErrorEvent;
use Contributte\Events\Bridges\Application\Event\PresenterEvent;
use Contributte\Events\Bridges\Application\Event\RequestEvent;
use Contributte\Events\Bridges\Application\Event\ResponseEvent;
use Contributte\Events\Bridges\Application\Event\ShutdownEvent;
use Contributte\Events\Bridges\Application\Event\StartupEvent;
```

- `StartupEvent::NAME` && `ApplicationEvents::ON_STARTUP`
- `ShutdownEvent::NAME` && `ApplicationEvents::ON_SHUTDOWN`
- `RequestEvent::NAME` && `ApplicationEvents::ON_REQUEST`
- `PresenterEvent::NAME` && `ApplicationEvents::ON_PRESENTER`
- `ResponseEvent::NAME` && `ApplicationEvents::ON_RESPONSE`
- `ErrorEvent::NAME` && `ApplicationEvents::ON_ERROR`

## Subscriber :bulb:

```php
use Contributte\EventDispatcher\EventSubscriber;
use Contributte\Events\Bridges\Application\Event\RequestEvent;

final class LogRequestSubscriber implements EventSubscriber
{

	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [RequestEvent::NAME => 'onLog'];
	}

	/**
	 * @param RequestEvent $event
	 * @return void
	 */
	public function onLog(RequestEvent $event)
	{
	    // Do magic..
	}
}
```