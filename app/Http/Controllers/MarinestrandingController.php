<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Stranding\Map;
use App\Models\Stranding\Group;
use App\Models\Stranding\Species;
use App\Models\Stranding\Province;

class MarinestrandingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {

        $query = Map::with(['group', 'province', 'species'])
            ->select('id', 'latitude', 'longitude', 'information_date', 'location', 'province_id', 'group_id', 'category_id', 'map_slug', 'species_id');

        // ✅ Apply Province Filter
        if ($request->filled('province') && is_array($request->province) && count($request->province) > 0) {
            $query->whereIn('province_id', $request->province);
        }

        // ✅ Apply Species Filter
        if ($request->filled('species') && is_array($request->species) && count($request->species) > 0) {
            $query->whereIn('species_id', $request->species);
        }

        // ✅ Apply Group Filter
        if ($request->filled('group') && is_array($request->group) && count($request->group) > 0) {
            $query->whereIn('group_id', $request->group);
        }

        // ✅ Apply Year Range Filter (Ensure it's an Integer)
        if ($request->filled('startYear') && is_numeric($request->startYear)) {
            $query->whereYear('information_date', '>=', (int)$request->startYear);
        }

        if ($request->filled('endYear') && is_numeric($request->endYear)) {
            $query->whereYear('information_date', '<=', (int)$request->endYear);
        }

        // ✅ Get Filtered Data
        $maps = $query->get()->map(function ($map) {
            return [
                'id' => $map->id,
                'latitude' => $map->latitude,
                'longitude' => $map->longitude,
                'information_date' => date("d-m-Y", strtotime($map->information_date)),
                'location' => $map->location,
                'province' => $map->province->province ?? null,
                'icon' => $map->group->icon ? asset("storage/" . $map->group->icon) : null,
                'species' => $map->species->species ?? null,
            ];
        });

        // ✅ Fetch distinct years from the database
        $years = Map::selectRaw('YEAR(information_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return Inertia::render('Home', [
            'maps' => $maps,
            'provinces' => Province::select('id', 'province')->orderBy('province')->get(),
            'species' => Species::select('id', 'species')->orderBy('species')->get(),
            'groups' => Group::select('id', 'group_name')->orderBy('group_name')->get(),
            'years' => $years
        ]);
    }
}
