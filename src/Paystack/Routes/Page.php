<?php

namespace Yabacon\Paystack\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;

/**
 * Page
 * Insert description here
 *
 * @category
 * @package
 * @author
 * @copyright
 * @license
 * @version
 * @link
 * @see
 * @since
 */
class Page implements RouteInterface
{

    /**
      Root
     */
    public static function root()
    {
        return '/page';
    }
    /*
      Create page
     */

    /**
     * create
     * Insert description here
     *
     * @return
     *
     * @access
     * @static
     * @see
     * @since
     */
    public static function create()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Page::root(),
            RouteInterface::PARAMS_KEY   => [
                'name',
                'description',
                'amount' ]
        ];
    }
    /*
      Get page
     */

    /**
     * fetch
     * Insert description here
     *
     * @return
     *
     * @access
     * @static
     * @see
     * @since
     */
    public static function fetch()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Page::root() . '/{id}',
            RouteInterface::ARGS_KEY     => ['id' ] ];
    }

    /*
      List page
     */

    /**
     * getList
     * Insert description here
     *
     * @return
     *
     * @access
     * @static
     * @see
     * @since
     */
    public static function getList()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Page::root() ];
    }
    /*
      Update page
     */

    /**
     * update
     * Insert description here
     *
     * @return
     *
     * @access
     * @static
     * @see
     * @since
     */
    public static function update()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::PUT_METHOD,
            RouteInterface::ENDPOINT_KEY => Page::root() . '/{id}',
            RouteInterface::PARAMS_KEY   => [
                'name',
                'description' ],
            RouteInterface::ARGS_KEY     => ['id' ] ];
    }
}
