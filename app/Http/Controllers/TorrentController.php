<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.04.2017
 * Time: 8:56
 */

namespace App\Http\Controllers;

use App\Lib\Decorator;
use App\Lib\Transmission\RPC;
use Illuminate\Http\Request;

class TorrentController extends Controller
{

    private $rpc;

    /**
     * DirController constructor.
     * @param RPC $rpc
     */
    public function __construct(RPC $rpc)
    {
        $this->rpc = $rpc;
        $this->middleware('auth');
    }

    function index()
    {
        return view('torrent');
    }

    function info(Request $request, $id)
    {
        $resp = $this->rpc->get((int) $id, [
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
        $resp = $this->rpc->add($_POST['id']);
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