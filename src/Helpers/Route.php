<?php

namespace League\Paystack\Helpers;

use \Closure;

class Route {

    private $route_class;

    const ID_KEY = 'id';

    // public static function put_non_array_values_into_array(&$arr,$key=Route::ID_KEY){
//     $argscount = count($arr);
//     for($i=0;$i<$argscount;$i++){
//
//     if(!is_array($arr[$i])){
//       $arr[$i] = [$key=>$arr[$i]];
//     }
//
//
//     }
//
//     }

    function __call($method, $args) {
        if (is_callable($this->methods[$method])) {
            return call_user_func_array($this->methods[$method], $args);
        }
    }

    function __construct($route_class, $secret_key) {
        $this->route_class = 'League\\Paystack\\Routes\\' . ucwords($route_class);
        $mets = get_class_methods($this->route_class);
        // add methods to this object for each, except the root
        foreach ($mets as $mtd) {
            if ($mtd !== 'root') {
                $mtdFunc = function (Array $params = [], Array $args = []) use ($mtd, $secret_key) {
                    print_r($params);
                    print_r($args);
                    $interface = call_user_func($this->route_class . '::' . $mtd);
                    // TODO: use interface to make curl request and return result

                    return $interface;
                };
                $this->methods[$mtd] = \Closure::bind($mtdFunc, $this, get_class());
            }
        }
    }

}
