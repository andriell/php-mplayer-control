<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.04.2017
 * Time: 8:56
 */

namespace App\Http\Controllers;

use App\Lib\Eom;
use App\Lib\FileSystem;
use App\Lib\ImageMagick;
use App\Lib\XBoTool;
use Illuminate\Http\Request;

class DirController extends Controller
{

    private $fs;
    private $eom;
    private $image;
    private $xBoTool;

    /**
     * DirController constructor.
     * @param FileSystem $fs
     * @param Eom $eom
     * @param ImageMagick $image
     */
    public function __construct(FileSystem $fs, Eom $eom, ImageMagick $image, XBoTool $xBoTool)
    {
        $this->fs = $fs;
        $this->eom = $eom;
        $this->image = $image;
        $this->xBoTool = $xBoTool;
        $this->middleware('auth');
    }

    function index()
    {
        return view('dir', ['title' => 'MyNAS Файлы', 'search' => true]);
    }


    function getList(Request $request, $uri = '')
    {
        $filter = [];
        if (isset($_GET['search'])) {
            $filter['name'] = $_GET['search'];
        }
        $json = json_encode($this->fs->readDir($uri, $filter));
        $headers = [
            'Content-Type' => 'application/json;charset=utf-8',
            'Content-Length' => strlen($json),
        ];
        return response()->make($json, 200, $headers);
    }

    private function resize($request, $uri, $size)
    {
        $headers = ['Content-Type' => 'image/jpeg'];
        //<editor-fold desc="http cache">
        $serverDate = $this->fs->fileMTime($uri);
        if ($serverDate) {
            $headers['Last-modified'] = gmdate("D, d M Y H:i:s \G\M\T", $serverDate);
            $userDate = $request->headers->get('if-modified-since', false);
            if ($userDate) {
                $userDate = strtotime($userDate);
                if ($userDate == strtotime(gmdate("D, d M Y H:i:s \G\M\T", $serverDate))) {
                    return response(null, 304, $headers);
                }
            }
        }
        //</editor-fold>
        try {
            $img = $this->image->resize($uri, $size);
        } catch (\Exception $e) {
            $img = $this->image->imgError($size);
        }
        return response($img, 200, $headers);
    }

    function imgPreview(Request $request, $uri)
    {
        $imgData = $this->image->resize($uri, [100, 100, true]);
        if ($imgData) {
            $filePath = public_path($this->fs->normalizeUri('/dir-img-preview/' . $uri));
            $dir = dirname($filePath);
            if (!file_exists($dir)) {
                try {
                    mkdir(dirname($filePath), 0755, true);
                } catch (\Exception $e) {
                }
            }
            file_put_contents($filePath, $imgData);
        }
        return response($imgData, 200, ['Content-Type' => 'image/jpeg']);
    }

    function img100x100(Request $request, $uri)
    {
        return $this->resize($request, $uri, [100, 100, true]);
    }

    function img1024x768(Request $request, $uri)
    {
        if (isset($_GET['sync']) && $_GET['sync'] == 'true' && isset($_GET['action'])) {
            if ($_GET['action'] == 'left') {
                $this->xBoTool->keyLeft();
            } else if ($_GET['action'] == 'right') {
                $this->xBoTool->keyRight();
            }
        }
        return $this->resize($request, $uri, [1024, 768, false]);
    }

    function download(Request $request, $uri)
    {
        $file = $this->fs->realPath($uri);
        return response()->download($file);
    }

    function mv()
    {
        return response()->json(['status' => $this->fs->fileRename($_POST['uri_dir'], $_POST['old_name'], $_POST['new_name'])]);
    }

    function cut()
    {
        return response()->json(['status' => $this->fs->mvBackupNumbered($_POST['uri_from'], $_POST['uri_to'])]);
    }

    function newFolder()
    {
        return response()->json(['status' => $this->fs->newFolder($_POST['uri_from'], $_POST['uri_to'])]);
    }

    function symlink()
    {
        return response()->json(['status' => $this->fs->lnSf($_POST['uri_from'], $_POST['uri_to'])]);
    }

    function copy()
    {
        return response()->json(['status' => $this->fs->cpBackupNumbered($_POST['uri_from'], $_POST['uri_to'])]);
    }

    function onlyDir(Request $request, $uri = '')
    {
        $r = [];
        $list = $this->fs->readDir($uri, ['only_dir' => true], ['name']);
        if (empty($list)) {
            return response()->json($r);
        }
        foreach ($list['items'] as $item) {
            $r[] = $item['name'];
        }
        return response()->json($r);
    }

    function doDelete()
    {
        return response()->json(['status' => $this->fs->rm($_POST['items'])]);
    }

    function slide(Request $request, $uri = '')
    {
        $this->eom->openFile($uri);
    }

    function slideLeft()
    {
        $this->xBoTool->keyLeft();
    }

    function keyRight()
    {
        $this->xBoTool->keyRight();
    }

    function slideShow(Request $request, $uri = '')
    {
        $this->eom->slideShowDir($uri);
    }

    function slideStop(Request $request, $uri = '')
    {
        $this->eom->stop();
    }

    function upload(Request $request, $uri = '')
    {
        $r = [
            'status' => 'ERROR',
        ];
        if (!(isset($_FILES['file']) && $this->fs->mvUpload($_FILES['file']['tmp_name'][0], $uri . '/' . $_FILES['file']['name'][0]))) {
            return response()->json($r, 501);
        }
        $r['status'] = 'OK';
        return response()->json($r);
    }
}