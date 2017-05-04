<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.04.2017
 * Time: 8:56
 */

namespace App\Http\Controllers;

use App\Lib\Decorator;
use App\Lib\FileSystem;
use App\Lib\Transmission\Files;
use App\Lib\Transmission\RPC;
use Illuminate\Http\Request;

class TorrentController extends Controller
{

    private $rpc;
    private $fs;

    /**
     * DirController constructor.
     * @param RPC $rpc
     * @param FileSystem $fs
     */
    public function __construct(RPC $rpc, FileSystem $fs)
    {
        $this->rpc = $rpc;
        $this->fs = $fs;
        $this->middleware('auth');
    }

    function index()
    {
        return view('torrent');
    }

    function info(Request $request, $id)
    {
        $resp = $this->rpc->get((int)$id, [
            'activityDate',
            'addedDate',
            'bandwidthPriority',
            'comment',
            'corruptEver',
            'creator',
            'dateCreated',
            'desiredAvailable',
            'doneDate',
            'downloadDir',
            'downloadedEver',
            'downloadLimit',
            'downloadLimited',
            'error',
            'errorString',
            'eta',
            'etaIdle',
            'files',
            //'fileStats',
            'hashString',
            'haveUnchecked',
            'haveValid',
            'honorsSessionLimits',
            'id',
            'isFinished',
            'isPrivate',
            'isStalled',
            'leftUntilDone',
            'magnetLink',
            'manualAnnounceTime',
            'maxConnectedPeers',
            'metadataPercentComplete',
            'name',
            'peer-limit',
            //'peers',
            'peersConnected',
            //'peersFrom',
            'peersGettingFromUs',
            'peersSendingToUs',
            'percentDone',
            //'pieces',
            'pieceCount',
            'pieceSize',
            //'priorities',
            'queuePosition',
            'rateDownload',
            'rateUpload',
            'recheckProgress',
            'secondsDownloading',
            'secondsSeeding',
            'seedIdleLimit',
            'seedIdleMode',
            'seedRatioLimit',
            'seedRatioMode',
            'sizeWhenDone',
            'startDate',
            'status',
            //'trackers',
            //'trackerStats',
            'totalSize',
            'torrentFile',
            'uploadedEver',
            'uploadLimit',
            'uploadLimited',
            'uploadRatio',
            //'wanted',
            //'webseeds',
            'webseedsSendingToUs',
        ]);

        if (!(
            is_array($resp) && isset($resp['result']) && $resp['result'] == 'success' && isset($resp['arguments'])
            && isset($resp['arguments']['torrents']) && isset($resp['arguments']['torrents'][0])
            && isset($resp['arguments']['torrents'][0]['files'])
        )) {
            return response()->json(['No file'], 501);
        }
        $resp['arguments'] = $resp['arguments']['torrents'][0];

        $files = new Files();
        $files->addFiles($resp['arguments']['files']);
        $resp['arguments']['files'] = $files->getData();

        return response()->json($resp);
    }

    function session() {
        return response()->json($this->rpc->sget());
    }

    function list()
    {
        $r = $this->rpc->get([], [
            'id',
            'name',
            'status',
            'addedDate',
            'haveValid',
            'totalSize',
            'sizeWhenDone',
            'rateDownload',
            'rateUpload',
            'eta',
            'peersSendingToUs',
        ]);
        return response()->json($r);
    }

    function add()
    {
        if (!(isset($_FILES['file']) && isset($_FILES['file']['tmp_name']))) {
            return response()->json(['No file'], 501);
        }
        $sessionProps = $this->rpc->sget();
        if (!(isset($sessionProps['arguments']) && isset($sessionProps['arguments']['download-dir']))) {
            return response()->json(['Transmission error'], 501);
        }
        $resp = $this->rpc->add([
            'metainfo' => base64_encode(file_get_contents($_FILES['file']['tmp_name'])),
            'download-dir' => $sessionProps['arguments']['download-dir'],
            'paused' => true,
        ]);
        return response()->json($resp);
    }

    function remove()
    {
        $resp = $this->rpc->remove((int)$_POST['id']);
        return response()->json($resp);
    }

    function stop()
    {
        $resp = $this->rpc->stop((int)$_POST['id']);
        return response()->json($resp);
    }

    function start()
    {
        $resp = $this->rpc->start((int)$_POST['id']);
        return response()->json($resp);
    }
}