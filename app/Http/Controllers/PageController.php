<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\News;
use App\Models\Wave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {
        $news = News::orderBy('created_at', 'DESC')->paginate(8);
        $waves = Wave::where([
            ['start_date', '<=', Carbon::today()],
            ['end_date', '>=', Carbon::today()],
            ['quantity', '>', 0]
        ])
        ->orderBy('end_date', 'ASC')
        ->get();
        $counters = Counter::orderBy('updated_at', 'DESC')->get();

        return view('index', [
            'news' => $news,
            'waves' => $waves,
            'counters' => $counters,
        ]);
    }
}
