<?php namespace Fortean\Bitter;

/**
 * @author     Bruce Walter <walter@fortean.com>
 * @copyright  Copyright (c) 2014
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

use Carbon\Carbon;
use FreeAgent\Bitter\Bitter as CoreBitter;

class ExtendedBitter extends CoreBitter {

	/**
	 * Wrap the Bitter mark function allowing users to pass a date string in addition to a DateTime instance.
	 * Chainable, returns the object as a result.
	 *
	 * @param  string   $eventName
	 * @param  integer  $id
	 * @param  mixed    $dateTime
	 * @return Fortean\Bitter\ExtendedBitter
	 */
	public function markEvent($eventName, $id, $dateTime = null)
	{
		if (isset($dateTime)) $dateTime = ($dateTime instanceof DateTime) ? $dateTime : new Carbon($dateTime);
		return $this->mark($eventName, $id, $dateTime);
	}

	/**
	 * Wrap the Bitter bitDateRange function allowing users to pass date strings in addition to DateTime instances.
	 * Chainable, returns the object as a result.
	 *
	 * @param  mixed   $from
	 * @param  mixed   $to
	 * @return Fortean\Bitter\ExtendedBitter
	 */
	public function bitCustomDateRange($key, $destKey, $from, $to)
	{
		$from = ($from instanceof DateTime) ? $from : new Carbon($from);
		$to = ($to instanceof DateTime) ? $to : new Carbon($to);
		return $this->bitDateRange($key, $destKey, $from, $to);
	}
}