<?php
namespace Wasm\FsBundle\File;

use Wasm\UtilBundle\Util\Str;

class Crud
{
    private $dirMode = null;
    private $fileMode = null;

    private function toOctal($num)
    {
        if(gettype($num) == "string") {
            if(Str::len($num) > 0)
                $num = octdec(trim($num));
            else
                throw new \Exception("Empty string(not an octal number): ''");
        }

        $checkNum = "0" . decoct($num);
        $validDigits = array("0", "1", "2", "3", "4", "5", "6", "7");
        $isValid = true;

        if(Str::len($checkNum) != 4)
            $isValid = false;
        else {
            for($i = 0; $i < 4; $i++) {
                if(!in_array($checkNum[$i], $validDigits))
                    $isValid = false;
            }
        }

        if(!$isValid)
            throw new \Exception("Wrong octal number: $checkNum");

        return $num;
    }

    public function setDirMode($dirMode = null)
    {
        if($dirMode === null) {
            $this->dirMode = null;
            return;
        }

        $this->dirMode = $this->toOctal($dirMode);
    }

    public function setFileMode($fileMode = null)
    {
        if($fileMode === null) {
            $this->fileMode = null;
            return;
        }

        $this->fileMode = $this->toOctal($fileMode);
    }

    public function createDir($path)
    {
        if(file_exists($path))
            return;

        if($this->dirMode == null) {
            mkdir($path);
            return;
        }

        $mask = umask(0);
        mkdir($path, $this->dirMode);
        chmod($path, $this->dirMode);
        umask($mask);
    }

    public function createFile($path, $data = "")
    {
        $h = fopen($path, "w+");
        if(Str::len($data) > 0)
            fwrite($h, $data);

        fclose($h);

        if($this->fileMode == null)
            return;

        $mask = umask(0);
        chmod($path, $this->fileMode);
        umask($mask);
    }

    public function exists($path)
    {
        return file_exists($path);
    }

    public function rm($path)
    {
        if(is_dir($path)) {
            rmdir($path);
            return;
        }

        unlink($path);
    }

    public function rename($oldPath, $newPath)
    {
        rename($oldPath, $newPath);
    }

    public function copy($srcPath, $targetPath)
    {
        copy($srcPath, $targetPath);
    }

    public function isDirEmpty($path)
    {
        $files = $this->readFiles($path);
        return count($files) == 0;
    }

    public function readDirs($path, $recursive = false)
    {
        $di = ($recursive)
            ? new \RecursiveIteratorIterator(
                  new \RecursiveDirectoryIterator(
                      $path, \FilesystemIterator::SKIP_DOTS
                  )
              )
            : new \DirectoryIterator($path);
        $dirs = array();

        foreach($di as $fileInfo) {
            if(!$recursive && $fileInfo->isDot()) continue;
            if(!$fileInfo->isDir()) continue;

            $dirs[] = $fileInfo->getFilename();
        }

        return $dirs;
    }

    public function readDirsRecursive($path)
    {
        return $this->readDirs($path, true);
    }

    public function readFiles(
        $path,
        $recursive = false,
        $collectFileInfo = false
    ) {
        $di = ($recursive)
            ? new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $path, \FilesystemIterator::SKIP_DOTS
                )
              )
            : new \DirectoryIterator($path);
        $files = array();

        foreach($di as $fileInfo) {
            if(!$recursive && $fileInfo->isDot()) continue;
            if($fileInfo->isDir()) continue;

            if($collectFileInfo) {
                $files[] = array(
                    "name" => $fileInfo->getFilename(),
                    "lastModified" => $fileInfo->getMTime(),
                    "lastAccessed" => $fileInfo->getATime()
                );
                if($recursive)
                    $files[count($files) - 1]["path"] = $fileInfo->getPath();
            }
            else {
                $files[] = $fileInfo->getFilename();
                if($recursive)
                    $files[count($files) - 1] = array(
                        "name" => $files[count($files) - 1],
                        "path" => $fileInfo->getPath(),
                    );
            }
        }

        return $files;
    }

    public function readFilesRecursive($path)
    {
        return $this->readFiles($path, true);
    }

    public function createFilesByArrayRecursive($filesMap = array(), $path)
    {
        foreach($filesMap as $filename => $maybeNestedMap) {
            if(strpos($filename, '.') !== false) {
                $this->createFile($path . $filename);
                continue;
            }

            $this->createDir($path . $filename);
            if(is_array($maybeNestedMap) && count($maybeNestedMap) > 0)
                $this->createFilesByArrayRecursive(
                    $maybeNestedMap, 
                    $path . $filename . "/"
                );
        }
    }
}