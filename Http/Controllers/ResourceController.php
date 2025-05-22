<?php

namespace Http\Controllers;

class ResourceController
{
    public function index()
    {
        $baseDir = base_path('storage/resources');
        $folders = array_filter(glob($baseDir . '/*'), 'is_dir');

        $resources = [];
        foreach ($folders as $folder) {
            $folderName = basename($folder);
            $files = array_filter(glob("{$folder}/*"), 'is_file');

            $resources[$folderName] = array_map(function ($file) {
                return basename($file);
            }, $files);
        }

        view('resources/index.view.php', ['resources' => $resources]);
    }
    

    public function download($folder, $file)
    {
        $filePath = base_path("files/resources/{$folder}/{$file}");

        if (!file_exists($filePath) || !is_readable($filePath)) {
            abort(404, "File not found.");
        }

        // Force download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    }

}
