<?php

    namespace Yabacon\Paystack\Routes;

    use Yabacon\Paystack\Contracts\RouteInterface;

    class Invoice implements RouteInterface
    {
            public static function root()
            {
                
                return '/paymentrequest';
            }
        
            public static function create(){
            
            }
        
            public static function getList()
            {
            
                    return [ RouteInterface::METHOD_KEY   => RouteInterface::GET_METHOD,
                    RouteInterface::ENDPOINT_KEY => Invoice::root() ];
            }
    }

?>
