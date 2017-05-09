<?php
/**
 * Media resizer controller
 * @package media
 * @version 0.0.1
 * @upgrade true
 */

namespace Media\Controller;
use \Eventviva\ImageResize;

class ResizerController extends \Controller
{
    public function altImage(){
        die('the file not there');
    }
    
    public function initAction(){
        $file = $this->req->uri;
        $file_abs = BASEPATH . $file;
        
        // Whoah, I should not be here
        if(is_file($file_abs))
            die;
        
        // get target file
        $file_name = basename($file_abs);
        $file_dir  = dirname($file_abs);
        
        $opts = explode('_', $file_name);
        if(count($opts) < 2 || count($opts) > 3)
            return $this->altImage();
        
        $sizes = explode('.', $opts[1]);
        
        $file_original = $file_dir . '/' . $opts[0] . '.' . $sizes[1];
        if(!is_file($file_original))
            return $this->altImage();
        
        $file_mime = mime_content_type($file_original);
        
        if(!fnmatch('image/*', $file_mime))
            die;
        
        list($t_width, $t_height) = explode('x', $sizes[0]);
        list($i_width, $i_height) = getimagesize($file_original);
        
        if(!$t_width && !$t_height)
            return $this->altImage();
        
        if(!$t_width)
            $t_width = ceil( $t_height * $i_width / $i_height );
        if(!$t_height)
            $t_height = ceil( $t_width * $i_height / $i_width );
        
        $image = new ImageResize($file_original);
        $image->crop($t_width, $t_height);
        
        $image->save($file_abs)->output();
    }
}