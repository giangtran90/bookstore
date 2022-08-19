<?php
class Upload{
    public function uploadFile($fileObj, $folderUpload, $option = null){
        if ($option == null){
            if ($fileObj['tmp_name'] != null){
                $uploadDir      = UPLOAD_PATH . $folderUpload . DS;
                $newFileName    = $this->randomString($fileObj['name'], 8);
                $fileName       = $uploadDir . $newFileName;

                copy($fileObj['tmp_name'], $fileName);

            }
        }
        return $newFileName;
    }

    public function removeFile($fileName, $folderUpload){
        $fileName = UPLOAD_PATH . $folderUpload . DS . $fileName;
        @unlink($fileName);
    }

    private function randomString($filename, $length = 5){
        $ext  = pathinfo($filename, PATHINFO_EXTENSION);
        $arrayCharacter = array_merge(range('a','z'), range('0','9'));
        $arrayCharacter = implode('',$arrayCharacter);
        $arrayCharacter = str_shuffle($arrayCharacter);
        $resultString   = substr($arrayCharacter, 0, $length) . '.' . $ext;
        return $resultString;
    }
}