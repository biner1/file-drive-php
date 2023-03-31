<?php

// Path: config\file_functions.php
// this file contains all the functions that are used to manipulate files and folders

function renameFile($fileToRename, $newName){
    $filePath = '../' . $fileToRename;
    $directory = dirname($filePath);
    $newFilePath = $directory . '/' . $newName;

    if(file_exists($filePath)) {
        if (rename($filePath, $newFilePath)) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}



function createFolder($folderName, $parentFolder = UPLOADS_PATH) {
    $newFolderPath = $parentFolder . $folderName;

    if (!file_exists($newFolderPath)) {
        if (mkdir($newFolderPath, 0777, true)) {
            return true;
        } else {
            return false;
        }
    }

    return false;
}



function deleteFiles($filesToDelete) {
    foreach($filesToDelete as $file) {
        $filePath = '../' . $file;
        if(file_exists($filePath)) {
            if (is_file($filePath)) {
                unlink($filePath);
            } else if (is_dir($filePath)) {
                removeDir($filePath);
            }
        }
    }
}



function removeDir($dirPath) {
    if (!is_dir($dirPath)) {
        return false;
    }
    
    $objects = scandir($dirPath);
    
    foreach ($objects as $object) {
        if (in_array($object, ['.', '..'])) {
            continue;
        }
        
        $fullPath = $dirPath . DIRECTORY_SEPARATOR . $object;
        
        if (is_dir($fullPath)) {
            removeDir($fullPath);
        } else {
            unlink($fullPath);
        }
    }
    
    reset($objects);
    return rmdir($dirPath);
}


function uploadFile($file, $dirPath = UPLOADS_PATH){
    $target_file = $dirPath . basename($file['name']);
    return move_uploaded_file($file['tmp_name'], $target_file);
}

function isValidPath($str) {
    // Define the regular expression pattern
    $pattern = "/^((\/[a-zA-Z0-9_\-\.]+)+)?$/";

    // Test the string against the pattern
    return preg_match($pattern, $str);
}

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}


function scandirToJson($dirPath = UPLOADS_PATH) {
    $files = scandir($dirPath);
    $files = array_diff($files, array('.', '..'));
    $result = array();
    foreach ($files as $file) {
        $filePath = $dirPath. $file;
        if (is_dir($filePath)) {
            $type = 'directory';
            $size = '-';
        } else {
            $type = 'file';
            $size = formatSizeUnits(filesize($filePath));
        }

        $result[] = array(
            'name' => $file,
            'type' => $type,
            'size' => $size,
            'modified' => filemtime($filePath),
            'dirpath' => str_replace('../', '', $filePath),
        );
    }

    return json_encode($result);
}

