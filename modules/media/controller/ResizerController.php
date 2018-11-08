<?php
/**
 * Media resizer controller
 * @package media
 * @version 0.0.1
 * @upgrade true
 */

namespace Media\Controller;
use Gumlet\ImageResize;

class ResizerController extends \Controller
{
    private function downloadImage($file_uri){
        // Should we download the image from live server?
        $media = $this->config->media ?? [];
        if(!isset($media['live']))
            return;
        
        $file_url = $media['live'] . $file_uri;
        
        // download the file
        $ch = curl_init($file_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64; rv:52.0) Gecko/20100101 Firefox/52.0');
        $file_bin = curl_exec($ch);
        
        if(curl_errno($ch))
            return;
        
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($http_code != 200)
            return;
        
        curl_close($ch);
        
        $file_dirs = trim(dirname($file_uri), '/');
        $file_dirs = explode('/', $file_dirs);
        
        $file_dir = BASEPATH;
        foreach($file_dirs as $dir){
            $file_dir.= '/' . $dir;
            if(!is_dir($file_dir))
                mkdir($file_dir);
        }
        
        $file_abs = BASEPATH . $file_uri;
        
        $f = fopen($file_abs, 'w');
        fwrite($f, $file_bin);
        fclose($f);
        
        return new ImageResize($file_abs);
    }
    
    public function altImage(){
        return $this->show404();
    }
    
    public function initAction(){
        $file = $this->req->uri;
        $file_abs = BASEPATH . $file;
        $file_abs = urldecode($file_abs);
        
        // Whoah, I should not be here
        if(is_file($file_abs))
            return $this->show404();
        
        // get target file
        $file_name = basename($file_abs);
        $file_dir  = dirname($file_abs);
        
        $compress = false;
        
        // should we webp compress the image?
        if(preg_match('!\.webp$!', $file_name)){
            if(!$this->config->media['webp'])
                return $this->show404();
            
            $compress = [
                'file_name' => $file_name,
                'file_abs'  => $file_abs
            ];
            
            $file_name = preg_replace('!\.webp$!', '', $file_name);
            $file      = preg_replace('!\.webp$!', '', $file);
            $file_abs  = $file_dir . '/' . $file_name;
        }
        
        if(preg_match('!(.+)_([0-9]*x[0-9]*)?\.([a-z0-9A-Z]+)$!', $file_name, $match)){
            $file_original = $file_dir . '/' . $match[1] . '.' . $match[3];
        }else{
            $file_original = $file_dir . '/' . $file_name;
        }
        
        if(!is_file($file_original)){
            $image = $this->downloadImage($file);
            
        }elseif($match){
            $file_mime = mime_content_type($file_original);
            if(!fnmatch('image/*', $file_mime)){
                header('Content-Type: ' . $file_mime);
                readfile($file_abs);
                die;
            }
        
            list($t_width, $t_height) = explode('x', $match[2]);
            list($i_width, $i_height) = getimagesize($file_original);
        
            if(!$t_width && !$t_height)
                return $this->altImage();
        
            if(!$t_width)
                $t_width = ceil( $t_height * $i_width / $i_height );
            if(!$t_height)
                $t_height = ceil( $t_width * $i_height / $i_width );
            
            $image = new ImageResize($file_original);
            $image->crop($t_width, $t_height, true);
        
            $image->save($file_abs);
        }else{
            $image = new ImageResize($file_original);
        }
        
        if(!$image)
            return $this->show404();
        
        if($compress){
            $image->save($compress['file_abs'], IMAGETYPE_WEBP);
            $file_abs = $compress['file_abs'];
        }
        
        $file_mime = mime_content_type($file_abs);
        header('Content-Type: ' . $file_mime);
        readfile($file_abs);
    }
}