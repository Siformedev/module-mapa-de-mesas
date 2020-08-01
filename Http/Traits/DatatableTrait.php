<?php


namespace Modules\MapaDeMesas\Http\Traits;


use DataTables\Database;
use Illuminate\Support\Facades\DB;

trait DatatableTrait
{
    protected $db = [];

    public function __construct()
    {
        $this->injectDatatable();
    }

    private function injectDatatable()
    {
        $pdo = DB::connection()->getPdo();

        $this->db = new Database( array(
            "type" => "Mysql",
            "pdo" => $pdo
        ) );
    }

}