<?php

require('dataprovider.class.php');

class Data {
    static private $ds;

    static public function initialize(DataProvider $data_provider){
        return self::$ds = $data_provider;
    }

     static public function getFiles($parentId){
        return self::$ds->getFiles($parentId);
     }

    static public function getFilesByPath($path){
        return self::$ds->getFilesByPath($path);
     }
     
     static public function deleteFiles($paths){
        return self::$ds->deleteFiles($paths);
     }
     static public function getFile($path){
        return self::$ds->getFile($path);
     }
     static public function deleteFile($id){
        return self::$ds->deleteFile($id);
     }

     static public function renameFile($path, $name){
        return self::$ds->renameFile($path, $name);
     }
     static public function createFolder($parentId, $name){
        return self::$ds->createFolder($parentId, $name);
     }
     static public function createFile($parentId, $name, $path, $size){
        return self::$ds->createFile($parentId, $name, $path, $size);
     }

     static public function createFileByParentPath($parentPath, $name, $path, $size, $type){
        return self::$ds->createFileByParentPath($parentPath, $name, $path, $size, $type);
     }
}