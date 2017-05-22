<?php
/**
 * Server spec test
 * @package media
 * @version 0.0.1
 * @upgrade true
 */

namespace Media\Library;

class Server
{
    static function gdlib(){
        $result = [
            'success' => function_exists('gd_info'),
            'info' => 'Not installed'
        ];
        
        if($result['success'])
            $result['info'] = gd_info()['GD Version'];
        
        return $result;
    }
}