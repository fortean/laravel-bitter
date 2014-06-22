<?php namespace Fortean\Bitter;

/**
 * @author     Bruce Walter <walter@fortean.com>
 * @copyright  Copyright (c) 2014
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

use DateTime;
use Carbon\Carbon;
use Redis as Redis;
use Predis\Client as PredisClient;
use FreeAgent\Bitter\Event\Year;
use FreeAgent\Bitter\Event\Month;
use FreeAgent\Bitter\Event\Week;
use FreeAgent\Bitter\Event\Day;
use FreeAgent\Bitter\Event\Hour;
use FreeAgent\Bitter\Date\DatePeriod;

class Bitter {

	/**
	 * Return a Bitter instance from the provided Redis connection.
	 * Use the 'default' Redis connection.
	 *
	 * @param  mixed  $connection
	 * @return \FreeAgent\Bitter\Bitter
	 */
	public function connection($connection = null)
	{
		$client = ($connection instanceof PredisClient) ? $connection : Redis::connection($connection ?: 'default');
		return new ExtendedBitter($client);
	}

	/**
	 * Return a DatePeriod instance from the provided options.
	 *
	 * @param  mixed   $from
	 * @param  mixed   $to
	 * @param  string  $fromOrTo
	 * @return \FreeAgent\Bitter\Date\DatePeriod
	 */
	public function periodForHour(DateTime $from, DateTime $to, $fromOrTo)
	{
		$from = ($from instanceof DateTime) ? $from : new Carbon($from);
		$to = ($to instanceof DateTime) ? $to : new Carbon($to);
		return DatePeriod::createForHour($from, $to, $fromOrTo);
	}

	/**
	 * Return a DatePeriod instance from the provided options.
	 *
	 * @param  mixed   $from
	 * @param  mixed   $to
	 * @param  string    $fromOrTo
	 * @return \FreeAgent\Bitter\Date\DatePeriod
	 */
	public function periodForDay(DateTime $from, DateTime $to, $fromOrTo)
	{
		$from = ($from instanceof DateTime) ? $from : new Carbon($from);
		$to = ($to instanceof DateTime) ? $to : new Carbon($to);
		return DatePeriod::createForDay($from, $to, $fromOrTo);
	}

	/**
	 * Return a DatePeriod instance from the provided options.
	 *
	 * @param  mixed   $from
	 * @param  mixed   $to
	 * @param  string    $fromOrTo
	 * @return \FreeAgent\Bitter\Date\DatePeriod
	 */
	public function periodForMonth(DateTime $from, DateTime $to, $fromOrTo)
	{
		$from = ($from instanceof DateTime) ? $from : new Carbon($from);
		$to = ($to instanceof DateTime) ? $to : new Carbon($to);
		return DatePeriod::createForMonth($from, $to, $fromOrTo);
	}

	/**
	 * Return a DatePeriod instance from the provided options.
	 *
	 * @param  mixed   $from
	 * @param  mixed   $to
	 * @return \FreeAgent\Bitter\Date\DatePeriod
	 */
	public function periodForYear(DateTime $from, DateTime $to)
	{
		$from = ($from instanceof DateTime) ? $from : new Carbon($from);
		$to = ($to instanceof DateTime) ? $to : new Carbon($to);
		return DatePeriod::createForYear($from, $to);
	}

	/**
	 * Return a Year instance from the provided event name and date.
	 *
	 * @param  string  $eventName
	 * @param  mixed   $dateTime
	 * @return \FreeAgent\Bitter\Event\Year
	 */
	public function yearEvents($eventName, $dateTime = null)
	{
		$dateTime = ($dateTime instanceof DateTime) ? $dateTime : new Carbon($dateTime);
		return new Year($eventName, $dateTime);
	}

	/**
	 * Return a Month instance from the provided event name and date.
	 *
	 * @param  string  $eventName
	 * @param  mixed   $dateTime
	 * @return \FreeAgent\Bitter\Event\Month
	 */
	public function monthEvents($eventName, $dateTime = null)
	{
		$dateTime = ($dateTime instanceof DateTime) ? $dateTime : new Carbon($dateTime);
		return new Month($eventName, $dateTime);
	}

	/**
	 * Return a Week instance from the provided event name and date.
	 *
	 * @param  string  $eventName
	 * @param  mixed   $dateTime
	 * @return \FreeAgent\Bitter\Event\Week
	 */
	public function weekEvents($eventName, $dateTime = null)
	{
		$dateTime = ($dateTime instanceof DateTime) ? $dateTime : new Carbon($dateTime);
		return new Week($eventName, $dateTime);
	}

	/**
	 * Return a Day instance from the provided event name and date.
	 *
	 * @param  string  $eventName
	 * @param  mixed   $dateTime
	 * @return \FreeAgent\Bitter\Event\Day
	 */
	public function dayEvents($eventName, $dateTime = null)
	{
		$dateTime = ($dateTime instanceof DateTime) ? $dateTime : new Carbon($dateTime);
		return new Day($eventName, $dateTime);
	}

	/**
	 * Return a Hour instance from the provided event name and date.
	 *
	 * @param  string  $eventName
	 * @param  mixed   $dateTime
	 * @return \FreeAgent\Bitter\Event\Hour
	 */
	public function hourEvents($eventName, $dateTime = null)
	{
		$dateTime = ($dateTime instanceof DateTime) ? $dateTime : new Carbon($dateTime);
		return new Hour($eventName, $dateTime);
	}

}