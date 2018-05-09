<?php

    namespace Yabacon\Paystack\Routes;

    use Yabacon\Paystack\Contracts\RouteInterface;

    class Invoice implements RouteInterface
    {
            public static function root()
            {
                
                    return '/paymentrequest';
            }
        
            public static function create()
            {
            
                    return [RouteInterface::METHOD_KEY   => RouteInterface::POST_METHOD,
                    RouteInterface::ENDPOINT_KEY => Invoice::root(),
                    RouteInterface::PARAMS_KEY   => [
                            'line_items',
                            'description',
                            'amount',
                            'customer',
                            'send_notification',
                            'tax',
                            'due_date',
                            'metadata',
                            'draft',
                            'currency',
                            'has_invoice',
                            'invoice_number'
                        ]
                    ];
            }
                            
            public static function fetch()
            {
                    return [RouteInterface::METHOD_KEY   => RouteInterface::GET_METHOD,
                    RouteInterface::ENDPOINT_KEY => Invoice::root() . '/{invoice_id_or_code}',
                    RouteInterface::ARGS_KEY     => ['invoice_id_or_code' ] ];
            }
        
            public static function getList()
            {
            
                    return [ RouteInterface::METHOD_KEY   => RouteInterface::GET_METHOD,
                    RouteInterface::ENDPOINT_KEY => Invoice::root(),
                    RouteInterface::PARAMS_KEY   => [
                            'customer',
                            'status',
                            'currency',
                            'paid',
                            'include_archive'
                        ]
                    ];
            }
    }

?>
