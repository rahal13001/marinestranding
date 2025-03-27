<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Kkprl\Zone;
use Illuminate\Http\Request;
use App\Models\Kkprl\Kkprlmap;
use App\Models\Kkprl\Kkprluse;
use App\Models\Stranding\Province;

class MarinespatialController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Query marine spatial maps
        $query = Kkprlmap::with(['zone', 'province', 'regulation'])
                ->select('id', 'province_id', 'zone_id', 'regulation_id', 'shp', 'shp_type', 'color');

        // Query marine spatial utilization data
        $querykkprluse = Kkprluse::with(['province'])
                ->select('id', 'province_id', 'subject_shp', 'shp_type', 'color', 'subject_activity', 'subject_status', 'width', 'length');

        // Apply Province Filter
        if ($request->filled('province') && is_array($request->province) && count($request->province) > 0) {
            $query->whereIn('province_id', $request->province);
            $querykkprluse->whereIn('province_id', $request->province);
        }

        // Apply Zone Filter
        if ($request->filled('zone') && is_array($request->zone) && count($request->zone) > 0) {
            $query->whereIn('zone_id', $request->zone);
        }

        // Apply Subject Status Filter (✅ Fixed)
        if ($request->filled('subject_status') && is_array($request->subject_status) && count($request->subject_status) > 0) {
            $querykkprluse->whereIn('subject_status', $request->subject_status);
        }

        // Fetch and transform data for kkprlmaps
        $kkprlmaps = $query->get()->map(function ($kkprlmap) {
            return [
                'id' => $kkprlmap->id,
                'province' => optional($kkprlmap->province)->province,
                'zone' => optional($kkprlmap->zone)->zone_name,
                'kawasan' => optional($kkprlmap->zone)->namakawasan,
                'regulation' => optional($kkprlmap->regulation)->regulation_name,
                'shp' => $kkprlmap->shp ? asset("storage/" . $kkprlmap->shp) : null,
                'shp_type' => $kkprlmap->shp_type,
                'color' => $kkprlmap->color,
            ];
        });

        // Fetch and transform data for kkprluses
        $kkprluses = $querykkprluse->get()->map(function ($kkprluse) {
            return [
                'id' => $kkprluse->id,
                'province_use' => optional($kkprluse->province)->province,
                'subject_shp' => $kkprluse->subject_shp ? asset("storage/" . $kkprluse->subject_shp) : null,
                'shp_type' => $kkprluse->shp_type,
                'color' => $kkprluse->color,
                'subject_activity' => $kkprluse->subject_activity,
                'subject_status' => $kkprluse->subject_status,
                'width' => $kkprluse->width,
                'length' => $kkprluse->length,
            ];
        });

        return Inertia::render('Marinespatial', [
            'kkprlmaps' => $kkprlmaps,
            'kkprluses' => $kkprluses,
            'provinces' => Province::select('id', 'province')->orderBy('province')->get(),
            'zones' => Zone::select('id', 'zone_name')->orderBy('zone_name')->get(),
            'subjectStatuses' => Kkprluse::select('subject_status')
                                ->distinct()
                                ->whereNotNull('subject_status')
                                ->orderBy('subject_status')
                                ->get()
                                ->map(fn ($item) => [
                                    'id' => $item->subject_status,
                                    'subject_status' => $item->subject_status,
                                ]),
            'selectedFilters' => [
                'province' => $request->province ?? [],
                'zone' => $request->zone ?? [],
                'subject_status' => $request->subject_status ?? [],
                'showKkprlMaps' => $request->showKkprlMaps ?? true, // ✅ Ensure updated data is returned
                'showKkprlUses' => $request->showKkprlUses ?? true,
            ],
        ]);
    }
}
