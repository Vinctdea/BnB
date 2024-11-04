<?php

namespace App\Http\Controllers\Auth\Ura;

use App\Http\Controllers\Controller;
use App\Models\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function view(Request $request)
    {
        // Recupera i parametri dalla richiesta
        $id = $request->query('id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Esegui la query per trovare le viste suddivise per mese
        $views = View::select(
                        DB::raw("YEAR(date_view) as year"),
                        DB::raw("MONTH(date_view) as month"),
                        DB::raw("COUNT(*) as view_count")
                    )
                    ->where('apartment_id', $id)
                    ->whereBetween('date_view', [$startDate, $endDate])
                    ->groupBy(DB::raw("YEAR(date_view)"), DB::raw("MONTH(date_view)"))
                    ->orderBy(DB::raw("YEAR(date_view)"), 'asc')
                    ->orderBy(DB::raw("MONTH(date_view)"), 'asc')
                    ->get();

        // Restituisci i risultati come risposta JSON
        return response()->json($views, 200);
    }
}
