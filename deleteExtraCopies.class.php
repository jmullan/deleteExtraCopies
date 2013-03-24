<?php
class deleteExtraCopies {
    public function doIt ($path) {
        if (is_dir($path)) {
            $this->checkDir($path);
        } elseif (is_file($path)) {
            $this->checkFile($path);
        }
    }
    private function listDir($dir) {
        $dirs = array();
        $files = array();
        $dh = opendir($dir);
        while ($dh && false !== ($entry = readdir($dh))) {
            if ('.' === $entry || '..' === $entry) {
                continue;
            } elseif (preg_match('/\.(mp3|m4a)$/i', $entry)) {
                $files[] = $entry;
            } elseif (is_dir($dir . '/' . $entry)) {
                $dirs[] = $entry;
            }
        }
        return array('files' => $files, 'dirs' => $dirs);
    }
    private function checkDir($dir) {
        $contents = $this->listDir($dir);
        $neighbors = $contents['files'];
        foreach ($contents['files'] as $file) {
            $this->checkFile($neighbors, $dir, $file);
            $new_contents = $this->listDir($dir);
            $neighbors = $new_contents['files'];
        }
        foreach ($contents['dirs'] as $subdir) {
            $this->checkDir($dir . '/' . $subdir);
        }
    }
    private function checkFile($files, $dir, $file) {
        $matches = array();
        if (preg_match('/^(.*)( \([0-9]+\))(.mp3|.m4a)$/i', $file, $matches)) {
            /*
             * array (
             *   0 => '12 Goin\' On (1).mp3',
             *   1 => '12 Goin\' On',
             *   2 => ' (1)',
             *   3 => '.mp3',
             * )
             */
            $root = $matches[1];
            $extension = $matches[3];
            $better_name = $root . $extension;
            echo "$file\n";
            if (in_array($better_name, $files)) {
                $better_name_size = filesize($dir . '/' . $better_name);
                $file_size = filesize($dir . '/' . $file);
                if ($better_name_size <= $file_size) {
                    echo "rename $file to $better_name in $dir\n";
                    rename($dir . '/' . $file, $dir . '/' . $better_name);
                    return;
                 } else {
                    echo "delete $file in favor of $better_name in $dir\n";
                    unlink($dir . '/' . $file);
                    return;
                }
            } else if (in_array(strtolower($better_name), array_map('strtolower', $files))) {
                echo "Can't move $file to $better_name in '$dir'\n";
            } else {
                rename($dir . '/' . $file, $dir . '/' . $better_name);
                return;
            }
        }
        if (preg_match('/^(.*).m4a/i', $file, $matches)) {
            $root = $matches[1];
            $better_name = $root . '.mp3';
            if (in_array($better_name, $files)) {
                echo "removing m4a in favor of mp3: $file in $dir\n";
                unlink($dir . '/' . $file);
                return;
            }
        }

    }
}