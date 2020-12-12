<?php

namespace Thumbnail;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Exception;


class Thumbnail
{
 
    public $disk;
    public $path;
    public $width;
    public $height;
    public $fileInfo;
    
    public function __construct()
    {
        
    }

    /**
     * @throws Exception
     */
    public function src(string $path, string $disk = null): self
    {
        $this->path = $path;
        $this->disk = $disk;
        
        return $this;
    }

    public function smartcrop(int $width = 150, int $height = 120)
    {
        $this->width = $width;
        $this->height = $height;
        
        return $this;
    }
    
    public function url(){
        if(!File::isFile(Storage::disk($this->disk)->path($this->path))){
            return asset('images/logo.png');
        }
        
        $this->getFileInfo();
        
        if(File::isFile($this->fileInfo['cacheFilePath'])){
            return Storage::disk($this->disk)->url($this->fileInfo['cacheFile']);
        }
        
        try {
            $this->checkDir($this->fileInfo['cacheDir']);
            
            $img = Image::make(Storage::disk($this->disk)->path($this->path));
            
            $img = $img->resize($this->width, $this->height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            $img->save(Storage::disk($this->disk)->path($this->fileInfo['cacheFile']), 70);
        } catch (Exception $e) {
            return asset('images/logo.png');
        }
        
        return Storage::disk($this->disk)->url($this->fileInfo['cacheFile']);
    }
    
    public function checkDir($dir = ''){
        return Storage::disk($this->disk)->makeDirectory($dir, '0777');
    }
    
    public function getFileInfo(){
        $filePath = Storage::disk($this->disk)->path($this->path);
        
        $name               = File::name($filePath);//文件名
        $extension          = File::extension($filePath);//扩展名
        $fileDir            = File::dirname($this->path);//文件路径
        $cacheName          = $name . (empty($this->width) ? '' : '-' . $this->width)
                                . (empty($this->height) ? '' : '-'.$this->height)
                                . '.'.$extension;
        
        $cacheDir          = 'cache/'.$fileDir;
        
        $this->fileInfo = [
            'fileDir' => $fileDir,
            'cacheDir' => 'cache/'.$fileDir,
            'cacheName' => $cacheName,
            'cacheFile' => $cacheDir . '/'.$cacheName,
            'cacheFilePath' => Storage::disk($this->disk)->path($cacheDir . '/'.$cacheName)
        ];
    }
}
