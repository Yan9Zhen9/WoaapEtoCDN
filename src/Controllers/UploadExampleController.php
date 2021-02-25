<?php

namespace Yan9\Etocdn\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadExampleController extends Controller
{
    public function index(Request $request)
    {
        $image = $request->file('file');

        if (!$image->isValid()) {
            return '上传失败';
        }

        $ext = $image->getClientOriginalExtension();
        $realPath = $image->getRealPath();
        $filename = str_random() . '.' . $ext;
        $filename = trim($filename, '/');

        $storage = \Illuminate\Support\Facades\Storage::disk('etocdn');
        Log::debug('OSS config:', [$filename]);

        // 上传文件
        $result = $storage->put($filename, $realPath);
        if (!$result) return false;
        var_dump($storage->url($filename));
    }
}
