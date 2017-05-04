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
        return response()->json($resp);
    }

    function list()
    {
        $resp = $this->rpc->get();
        $r = [];
        $r['items'] = $resp['arguments']['torrents'];
        foreach ($r['items'] as $i => $t) {
            $r['items'][$i]['status'] = $this->rpc->getStatusString($r['items'][$i]['status']);
            $r['items'][$i]['status_f'] = $this->rpc->getStatusString($r['items'][$i]['status']);
            $r['items'][$i]['addedDate_f'] = Decorator::date($r['items'][$i]['addedDate']);
            $r['items'][$i]['haveValid_f'] = Decorator::size($r['items'][$i]['haveValid']);
            $r['items'][$i]['sizeWhenDone_f'] = Decorator::size($r['items'][$i]['haveValid']);
            $r['items'][$i]['totalSize_f'] = Decorator::size($r['items'][$i]['totalSize']);
        }
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
        /*$torrentFile = '/tmp/' . $_FILES['file']['name'];
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $torrentFile)) {
            return response()->json(['Move file error'], 501);
        }*/
        $resp = $this->rpc->add($_FILES['file']['tmp_name'], $sessionProps['arguments']['download-dir']);
        $resp['tmp_name'] = $_FILES['file']['tmp_name'];
        //unlink($torrentFile);
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