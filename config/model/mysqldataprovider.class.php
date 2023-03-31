<?php

class MysqlDataProvider extends DataProvider {

    public function getFiles($parentId) {

        $db =  $this->connect();
        if($db == null){
            return;
        }

        $sql = "SELECT * FROM file_system WHERE parent_id = :parent_id";
        $smt = $db->prepare($sql);

        $smt->execute([
            ':parent_id' => $parentId,
        ]);

        $data = $smt->fetchAll(PDO::FETCH_CLASS, 'FileSystem');
        
        if(empty($data)){
            return;
        }
        // close the connection
        $smt = null;
        $db = null;
        
        return $data;
    }
    

    public function getFIlesByPath($path) {

        $db =  $this->connect();
        if($db == null){
            return;
        }

        $sql = "SELECT * FROM file_system WHERE path = :path";
        $smt = $db->prepare($sql);

        $smt->execute([
            ':path' => $path,
        ]);

        $data = $smt->fetchAll(PDO::FETCH_CLASS, 'FileSystem');
        
        if(empty($data)){
            return;
        }

        $smt = null;
        $db = null;
        
        return $data;
    }

    public function deleteFiles($paths) {
        $sql = "DELETE FROM file_system WHERE path IN (".implode(",", array_fill(0, count($paths), "?")).")";
        return $this->execute($sql,$paths);
    }

    public function getFile($path) {
        $db =  $this->connect();
        if($db == null){
            return;
        }

        $sql = "SELECT * FROM file_system WHERE path = :path";
        $smt = $db->prepare($sql);

        $smt->execute([
            ':path' => $path,
        ]);

        $data = $smt->fetchAll(PDO::FETCH_CLASS, 'FileSystem');
        
        if(empty($data)){
            return;
        }

        $smt = null;
        $db = null;
        
        return $data[0];
    }

    public function deleteFile($id) {
        return $this->execute('DELETE FROM file_system WHERE id = :id',[':id'=>$id]);
    }

    public function renameFile($path, $name) {
        $sql = "UPDATE file_system SET name = :name, path=:new_path WHERE path = :path;";

        return $this->execute($sql,[
            ':path' => $path,
            ':name' => $name,
            ':new_path' => str_replace(basename($path), $name, $path)
        ]);
    }

    public function createFolder($parentId, $name) {
        return $this->execute("INSERT INTO file_system (name, type, parent_id, path) VALUES (:name, 'folder', :parent_id, '')",[
            ':parent_id' => $parentId,
            ':name' => $name
        ]);
    }

    public function createFile($parentId, $name, $path, $size) {
        return $this->execute("INSERT INTO file_system (name, type, parent_id, path, size) VALUES (:name, 'file', :parent_id, :path, :size)",[
            ':parent_id' => $parentId,
            ':name' => $name,
            ':path' => $path,
            ':size' => $size
        ]);

    }

    public function createFileByParentPath($parentPath, $name, $path, $size, $type) {

        return $this->execute("INSERT INTO file_system (name, type, parent_id, path, size) VALUES (:name, :type, :parent_id, :path, :size)",[
            ':parent_id' => $this->getIdByPath($parentPath),
            ':name' => $name,
            ':path' => $path,
            ':size' => $size,
            ':type' => $type
        ]);

    }

    public function getIdByPath($path = '') {
        $db =  $this->connect();
        if($db == null){
            return;
        }

        $sql = "SELECT id FROM file_system WHERE path = :path";
        $smt = $db->prepare($sql);

        $smt->execute([
            ':path' => $path,
        ]);

        $data = $smt->fetchAll(PDO::FETCH_CLASS, 'FileSystem');
        
        if(empty($data)){
            return;
        }

        $smt = null;
        $db = null;
        
        return $data[0]->id;
    }
    
    private function query($sql, $sql_parms = []){
        $db =  $this->connect();
        if($db == null){
            return [];
        }
        
        $query = null;

        if(empty($sql_parms)){
            $query = $db->query($sql);
        }else{
            $query = $db->prepare($sql);
            $query->execute($sql_parms);
        }

        $data = $query->fetchAll(PDO::FETCH_CLASS, 'FileSystem');

        $query = null;
        $db = null;

        return $data;
    }

    private function execute($sql, $sql_parms){
        $db =  $this->connect();
        if($db == null){
            return;
        }
        $smt = $db->prepare($sql);
        $smt->execute($sql_parms);

        $smt = null;
        $db = null;
    }

    private function connect(){
        try{
            return new PDO($this->source,CONFIG['db_user'],CONFIG['db_password']);

        }catch(PDOException $e){
            return null;
        }
    }

}