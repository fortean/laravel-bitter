# Laravel 4 Service Provider for Bitter, a PHP port of bitmapist (Python Redis analytics)

laravel-bitter is a Laravel 4 service provider for [free-agent/bitter](https://github.com/jeremyFreeAgent/Bitter)
which is a PHP port of the Python [bitmapist](https://github.com/Doist/bitmapist/) library.  Both libraries use
Redis to implement real-time, highly scalable analytics that can answer following questions:

* Has user 123 been online today? This week? This month?
* Has user 123 performed action "X"?
* How many users have been active have this month? This hour?
* How many unique users have performed action "X" this week?
* How many % of users that were active last week are still active?
* How many % of users that were active last month are still active this month?

Using Redis bitmaps you can store events for millions of users in a very little amount of memory (megabytes).
You should be careful about using huge ids (e.g. 2^32 or bigger) as this could require larger amounts of memory.

### Contents
 
- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Using BitOp](#using-bitop)
- [Custom date period stats](#custom-date-period-stats)
- [Thanks](#thanks)
- [License](#license)

## Installation

Add laravel-bitter to your composer.json file:

```
"require": {
  "laravel-bitter": "dev-master"
}
```

Use composer to install this package.

```
$ composer update
```

### Registering the Package

Register the service provider within the ```providers``` array found in ```app/config/app.php```:

```php
'providers' => array(
	// ...
	
	'Fortean\Bitter\BitterServiceProvider'
)
```

Add an alias within the ```aliases``` array found in ```app/config/app.php```:


```php
'aliases' => array(
	// ...
	
	'Bitter' => 'Fortean\Bitter\Facade\Bitter',
)
```

## Basic Usage

One you have registered the Bitter Service Provider and Facade in app/config/app.php, you can
create a Bitter instance like so:

```php
$bitter = Bitter::connection();
```

The Bitter instance returned will use the default Laravel Redis connection.  If you want to use
the 'foo' Redis connection, make sure the connection is defined in your app/config/database.php file:

```php
	'redis' => array(

		'cluster' => true,

		// ...

		'foo' => array(
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 2,
		),

	),
```

and pass the 'foo' connection name to the connection method:

```php
$bitter = Bitter::connection('foo');
```

You can also pass a Redis client instance to connection:

```php
$client = Redis::connection('foo');
$bitter = Bitter::connection($client);
```

Once you have a Bitter instance, you can do the following:

Mark user 123 as active and has played a song:

```php
$bitter
	->mark('active', 123)
	->mark('song:played', 123);
```

**Note**: Please don't use huge ids (e.g. 2^32 or bigger) cause this will require large amounts of memory.

Pass a DateTime (or derviative, e.g. Carbon) as third argument:

```php
$bitter->mark('song:played', 123, new \DateTime('yesterday'));
$bitter->mark('song:played', 123, new \Carbon\Carbon('yesterday'));
```

To facilitate easier use of the library, I've extended Bitter with a `markEvent` method that will take either
DateTime objects as above, or a string which will be converted into a Carbon object automatically:

```php
// These calls:
$yesterday = new \Carbon\Carbon('yesterday');
$bitter->markEvent('song:played', 123, $yesterday);
$bitter->markEvent('song:played', 123, new \Carbon\Carbon('yesterday'));
$bitter->markEvent('song:played', 123, 'yesterday');

// Are all equivalent to this:
$bitter->mark('song:played', 123, new \Carbon\Carbon('yesterday'));
```

To test if user 123 has played a song this week:

```php
$currentWeek = Bitter::weekEvents('song:played');

if ($bitter->in(123, $currentWeek) {
	echo 'User with id 123 has played a song this week.';
} else {
	echo 'User with id 123 has not played a song this week.';
}
```

How many users were active yesterday:

```php
$yesterday = new Bitter::dayEvents('active', new \DateTime('yesterday'));

echo $bitter->count($yesterday) . ' users were active yesterday.';
```

All of the Bitter *Events helpers can take either a DateTime object or a string date descriptor:

```php
// Equivalent calls
$day = Bitter::dayEvents('active', new \DateTime('yesterday'));
$day = Bitter::dayEvents('active', 'yesterday');
```

## Using BitOp

How many users that were active yesterday are also active today:

```php
$today     = Bitter::dayEvents('active');
$yesterday = Bitter::dayEvents('active', new \DateTime('yesterday'));

$count = $bitter
	->bitOpAnd('bit_op_example', $today, $yesterday)
	->count('bit_op_example');

echo $count . ' users were active yesterday and today.';
```

**Note**: The ``bit_op_example`` key will expire after 60 seconds.

Test if user 123 was active yesterday and is active today:

```php
$today     = new Bitter::dayEvents('active');
$yesterday = new Bitter::dayEvents('active', new \DateTime('yesterday'));

$active = $bitter
	->bitOpAnd('bit_op_example', $today, $yesterday)
	->in(123, 'bit_op_example');

if ($active) {
	echo 'User with id 123 was active yesterday and today.';
} else {
	echo 'User with id 123 was not active yesterday and today.';
}
```

**Note**: Please look at [Redis BITOP Command](http://redis.io/commands/bitop) for performance considerations.

## Custom date period stats

How many users that were active during a given date period:

```php
$from = new \DateTime('2010-02-14 20:15:30');
$to   = new \DateTime('2012-12-21 13:30:45');

$count = $bitter
	->bitDateRange('active', 'active_period_example', $from, $to)
	->count('active_period_example');

echo $count . ' users were active from "2010-02-14 20:15:30" to "2012-12-21 13:30:45".';
```

To facilitate easier use of the library, I've extended Bitter with a `bitCustomDateRange` method that will take either
DateTime objects as above, or a string which will be converted into a Carbon object automatically:

```php
$count = $bitter
	->bitCustomDateRange('active', 'active_period_example', '2010-02-14 20:15:30', '2012-12-21 13:30:45')
	->count('active_period_example');

echo $count . ' users were active from "2010-02-14 20:15:30" to "2012-12-21 13:30:45".';
```

## Thanks

Thanks to both [Amir Salihefendic](http://amix.dk/) for the original bitmapist library and
[Jérémy Romey](https://github.com/jeremyFreeAgent) for his PHP port.

## License

This library is licensed under the [MIT license](LICENSE).