<?php

namespace Yabacon\Paystack\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;

/**
 * Subscription
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
class Subscription implements RouteInterface
{

    /**
      Root
     */
    public static function root()
    {
        return '/subscription';
    }
    /*
      Create subscription
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
            RouteInterface::ENDPOINT_KEY => Subscription::root(),
            RouteInterface::PARAMS_KEY   => [
                'customer',
                'plan',
                'authorization' ]
        ];
    }
    /*
      Get subscription
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
            RouteInterface::ENDPOINT_KEY => Subscription::root() . '/{id}',
            RouteInterface::ARGS_KEY     => ['id' ] ];
    }

    /*
      List subscription
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
            RouteInterface::ENDPOINT_KEY => Subscription::root() ];
    }
    /*
      Disable subscription
     */

    /**
     * disable
     * Insert description here
     *
     * @return
     *
     * @access
     * @static
     * @see
     * @since
     */
    public static function disable()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Subscription::root(). '/disable',
            RouteInterface::PARAMS_KEY   => [
                'code',
                'token'] ];
    }
    
    /*
      Enable subscription
     */

    /**
     * enable
     * Insert description here
     *
     * @return
     *
     * @access
     * @static
     * @see
     * @since
     */
    public static function enable()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Subscription::root() . '/enable',
            RouteInterface::PARAMS_KEY   => [
                'code',
                'token'] ];
    }
}
