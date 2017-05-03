<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.04.2017
 * Time: 8:56
 */

namespace App\Http\Controllers;

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
        $resp = $this->rpc->get();
        $data = [];
        $data['torrents'] = $resp['arguments']['torrents'];
        foreach ($data['torrents'] as $i => $t) {
            $data['torrents'][$i]['status'] = $this->rpc->getStatusString($data['torrents'][$i]['status']);
        }

        return view('torrent', $data);
    }

}