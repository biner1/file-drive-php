<?php

// Path: controller\file-manager.php
// This file is used as controller, it handles the requests from the client side
// TODO: make file-manager controller work with json data
// TODO: validate the inputs

require('../config/app.php');


if (is_get()) {
    if (isset($_GET['dir'])) {
        $dirPath = $_GET['dir'];
        if ($dirPath == 'undefined') {
            $json = scandirToJson();
        } else {
            $fullPath = APP_PATH . $dirPath . '/';
            if (file_exists($fullPath)) {
                $json = scandirToJson($fullPath);
            } else {
                echo 'Invalid directory path';
                exit();
            }
        }
        echo $json;
    } else {
        echo 'No directory specified';
        exit();
    }
}

if (is_post() && isset($_FILES['file'])) {
    // Upload file
    // path: /folder1/folder2
    // path is innerHTML of the element with id="directory"
    $path = $_POST['path']; 
    $parentPath = 'uploads'.$path;
    $fileName = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];

    if($path == ''){
        $path = 'uploads/' . $fileName;
        uploadFile($_FILES['file']);
    }else{
        $path = 'uploads' . $_POST['path'] . '/' . $fileName;
        uploadFile($_FILES['file'], ''.UPLOADS_PATH.$_POST['path'].'/');
    }

    Data::createFileByParentPath($parentPath, $fileName, $path, $size, 'file');
}

// Create folder
if (is_post() && isset($_POST['folder'])) {
    $path = $_POST['path'];
    $parentPath = 'uploads'.$path;
    $folderName = $_POST['folder'];

    if($path == ''){
        $path = 'uploads/' . $folderName;
        createFolder($_POST['folder']);
    }else{
        // uploads . /folder/folder . / . folderName
        $path = 'uploads' . $_POST['path'] . '/' . $folderName;
        createFolder($folderName, UPLOADS_PATH.$_POST['path'].'/');
    }

    Data::createFileByParentPath($parentPath, $folderName, $path, 0, 'folder');
}


// if (is_post()) {
//     // Upload file
//     if (isset($_FILES['file'])) {
//         $path = $_POST['path']; // path: /folder1/folder2
//         $parentPath = 'uploads'.$path;
//         $fileName = $_FILES['file']['name'];
//         $size = $_FILES['file']['size'];

//         if($path == ''){
//             $path = 'uploads/' . $fileName;
//             uploadFile($_FILES['file']);
//         }else{
//             $path = 'uploads' . $_POST['path'] . '/' . $fileName;
//             uploadFile($_FILES['file'], ''.UPLOADS_PATH.$_POST['path'].'/');
//         }

//         Data::createFileByParentPath($parentPath, $fileName, $path, $size, 'file');
//     }

//     // Create folder
//     // $_POST['folder']) is the name of the folder
//     else if (isset($_POST['folder'])) {
//         $path = $_POST['path'];
//         $parentPath = 'uploads'.$path;

//         if($path == ''){
//             $path = 'uploads/' . $_POST['folder'];
//             createFolder($_POST['folder']);
//         }else{
//             $path = 'uploads' . $_POST['path'] . '/' . $_POST['folder'] ;
//             createFolder($_POST['folder'], UPLOADS_PATH.$_POST['path'].'/');
//         }

//         Data::createFileByParentPath($parentPath, $_POST['folder'], $path, 0, 'folder');
//     }
// }




if (is_delete()) {
    // get the request body
    $requestBody = file_get_contents('php://input');
    $data = json_decode($requestBody, true);

    if (isset($data['files']) && is_array($data['files'])) {
        $files_to_delete = $data['files'];
        deleteFiles($files_to_delete);
        Data::deleteFiles($files_to_delete);
    } else {
        // Handle invalid request body
        http_response_code(400);
        echo json_encode(['message' => 'Invalid request body']);
    }
}
    

// rename file
if (is_put()) {
    $requestBody = file_get_contents('php://input');
    $data = json_decode($requestBody, true);
    if (!isset($data['file']) || !isset($data['rename'])) {
        // Handle missing parameters error
        http_response_code(400);
        echo "Error: missing parameters";
        exit();
    }
    $file_to_rename = $data['file'];
    $new_name = $data['rename'];
    renameFile($file_to_rename, $new_name);
    Data::renameFile($file_to_rename, $new_name);
}
