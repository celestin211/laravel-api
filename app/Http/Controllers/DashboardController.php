<?php

namespace App\Http\Controllers;

use App\Queries\DashboardOfferQuery;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with paginated offers.
     */
    public function show(Request $request): View
    {
        $perPage = min((int) $request->input('per_page', 10), 50); // Max 50 per page
        $page = max((int) $request->input('page', 1), 1);

        $query = new DashboardOfferQuery($request);
        $offers = $query->build()->paginate($perPage, ['*'], 'page', $page);

        return view('dashboard', compact('offers'));
    }
}
