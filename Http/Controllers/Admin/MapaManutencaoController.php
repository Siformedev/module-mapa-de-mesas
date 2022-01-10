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
use Modules\MapaDeMesas\Entities\Mapas;
use Modules\MapaDeMesas\Entities\MesasTipoConfig;

use Intervention\Image\ImageManagerStatic as Image;
use Modules\MapaDeMesas\Http\Traits\DatatableTrait;

class MapaManutencaoController extends Controller
{
    use DatatableTrait;

    public function index($id)
    {
        $mapa = Mapas::find($id);
        return view('mapademesas::admin.mapa-manutencao.index', compact('mapa'));
    }

    public function upload($id)
    {
        $mapa = Mapas::find($id);
        $imgName = 'uploads/mapa/' . $id . '.jpg';
        move_uploaded_file($_FILES['imagem']['tmp_name'], public_path($imgName));

        // Image::configure(array('driver' => 'imagick'));

        $image = Image::make(public_path($imgName));

        $iWidth = $image->width();
        $iHeight = $image->height();

        if($iWidth > $iHeight){
            if($iWidth > 1300){
                $image->resize(1300, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $iWidth = $image->width();
                $iHeight = $image->height();
            }
        }else{
            if($iHeight > 800){
                $image->resize(null, 800, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $iWidth = $image->width();
                $iHeight = $image->height();
            }
        }
        $mapa->imagem = $id . ".jpg";
        $mapa->imagem_x = $iWidth;
        $mapa->imagem_y = $iHeight;
        $mapa->save();
        return ['success' => true, 'x' => $iWidth, 'y' => $iHeight];
    }

    public function editXY($id, $x, $y)
    {
        $mapa = Mapas::find($id);
        $mapa->imagem_x = $x;
        $mapa->imagem_y = $y;
        $mapa->save();
        return ['success' => true];
    }



}
