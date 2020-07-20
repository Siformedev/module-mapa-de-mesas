<?php

namespace Modules\MapaDeMesas\Http\Controllers\Admin;


use DataTables\Editor\Format;
use DataTables\Editor\Options;
use DataTables\Editor\Validate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use
    DataTables\Database,
    DataTables\Editor,
    DataTables\Editor\Field;
use Illuminate\Support\Facades\DB;
use Modules\MapaDeMesas\Entities\mapas;
use Modules\MapaDeMesas\Entities\MesasTipoConfig;

class MapaManutencaoController extends Controller
{
    public function index($id)
    {
        $mapa = Mapas::find($id);
        return view('mapademesas::admin.mapa-manutencao.index', compact('mapa'));
    }

    public function upload($id)
    {
        $mapa = Mapas::find($id);
        move_uploaded_file($_FILES['imagem']['tmp_name'], public_path('uploads/mapa/' . $id . '.jpg'));
        $mapa->imagem = $id . ".jpg";
        $mapa->save();
        return ['success' => true];
    }

    public function editXY($id, $x, $y)
    {
        $mapa = Mapas::find($id);
        $mapa->imagem_x = $x;
        $mapa->imagem_y = $y;
        $mapa->save();
        return ['success' => true];
    }

    public function editConfig(Mapas $mapa, MesasTipoConfig $config)
    {
        $mapa->config_id = $config->id;
        $mapa->save();
        return $config;
    }

}
