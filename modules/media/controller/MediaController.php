<?php
/**
 * Media filter
 * @package media
 * @version 0.0.1
 * @upgrade true
 */

namespace Media\Controller;
use Upload\Model\Media;

class MediaController extends \Controller
{
    public function filterAction(){
        if(!module_exists('upload'))
            return $this->ajax(['error'=>'Module upload not installed']);
        
        if(!$this->user->isLogin())
            return $this->ajax(['error'=>'Not authorized']);
        
        $cond = [];
        
        $q = $this->req->getQuery('q');
        if($q){
            $q = Media::escape($q);
            $cond[] = "`original` LIKE '%$q%'";
        }
        
        $mims = $this->req->getQuery('mime');
        if($mims){
            $mims = explode(',', $mims);
            $mim_cond = [];
            foreach($mims as $mim){
                $mim = str_replace('*', '%', $mim);
                $mi = Media::escape($mim);
                $mim_cond[] = "`mime` LIKE '$mi'";
            }
            if($mim_cond)
                $cond[] = '( ' . implode(' OR ', $mim_cond) . ' ) ';
        }
        
        $sql = implode(' AND ', $cond);
        
        $medias = Media::get($sql, 20, false, 'LENGTH(`original`)');
        if(!$medias)
            return $this->ajax(['data'=>[]]);
        
        $result = [];
        foreach($medias as $med)
            $result[] = ['path' => $med->path, 'name' => $med->original];
        
        $this->ajax(['data'=>$result]);
    }
}