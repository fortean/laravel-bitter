<?php namespace Fortean\Bitter\Facade;

/**
 * @author     Bruce Walter <walter@fortean.com>
 * @copyright  Copyright (c) 2014
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

use Illuminate\Support\Facades\Facade;

class Bitter extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'bitter'; }

}