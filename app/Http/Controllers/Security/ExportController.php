<?php

namespace App\Http\Controllers\Security;

use App\Exports\ModelExport;
use App\Http\Controllers\CoreController;
use App\Models\Security\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends CoreController
{
    public function export(Request $request): BinaryFileResponse
    {
        $params = $request->all();

        $model = array_key_first($params);
        $modelClass = resolve('App\Models\\'.$model);

        $columns = array_keys($params[$model]['columns']);
        $friendlyColumns = $params[$model]['columns'];
        $ids = $params[$model]['ids'];

        $export = new ModelExport($modelClass, $columns, $friendlyColumns, $ids);

        return Excel::download($export, 'export.xlsx');
    }
}
