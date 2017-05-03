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

    function list()
    {
        $resp = $this->rpc->get();
        $r = [];
        $r['items'] = $resp['arguments']['torrents'];
        foreach ($r['items'] as $i => $t) {
            $r['items'][$i]['status'] = $this->rpc->getStatusString($r['items'][$i]['status']);
            $r['items'][$i]['doneDate'] = Decorator::date($r['items'][$i]['doneDate']);
            $r['items'][$i]['haveValid'] = Decorator::size($r['items'][$i]['haveValid']);
            $r['items'][$i]['totalSize'] = Decorator::size($r['items'][$i]['totalSize']);
        }
        return response()->json($r);
    }
}