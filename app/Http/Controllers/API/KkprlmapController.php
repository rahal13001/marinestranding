<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kkprl\Kkprlmap;
use Illuminate\Http\Request;

class KkprlmapController extends Controller
{
    public function rzwp3k(Request $request)
    {


        /**
     * Retrieve RZWP3K Data with optional filters.
     * 
     * @queryParam province_id array Optional. Array of province IDs for filtering. Example: [1,2].
     * @queryParam zone_id array Optional. Array of zone IDs for filtering. Example: [3,4].
     * 
     * @response {
     *   "success": true,
     *   "data": [
     *       {
     *           "id": 1,
     *           "province_id": 1,
     *           "zone_id": 3,
     *           "regulation_id": 5,
     *           "shp": "path/to/geojson/file",
     *           "shp_type": "polygon",
     *           "color": "#ff0000",
     *           "created_at": "2024-11-24T12:34:56Z",
     *           "updated_at": "2024-11-24T12:34:56Z"
     *       }
     *   ],
     *   "meta": {
     *       "current_page": 1,
     *       "total": 1,
     *       "per_page": 10,
     *       "last_page": 1
     *   },
     *   "message": "RZWP3K Data Retrieved Successfully"
     * }
     */


       // Validate request
       $validated = $request->validate([
        'province_id' => 'nullable|array',
        'province_id.*' => 'integer',
        'zone_id' => 'nullable|array',
        'zone_id.*' => 'integer',
        ]);

    // Set fallback defaults for filters
    $provinceId = $validated['province_id'] ?? []; // Default to an empty array
    $zoneId = $validated['zone_id'] ?? []; // Default to an empty array

    // Query the data
    $query = Kkprlmap::with(['zone', 'province', 'regulation'])
        ->when(!empty($provinceId), function ($query) use ($provinceId) {
            $query->whereIn('province_id', $provinceId);
        })
        ->when(!empty($zoneId), function ($query) use ($zoneId) {
            $query->whereIn('zone_id', $zoneId);
        });

    // Check if there are any matching records
    if (!$query->exists()) {
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'No records found for the provided filters.',
        ]);
    }

    // Fetch all results without pagination
    $kkprlmap = $query->get()
        // ->map(function ($item) {
        //     return [
        //         'properties' => [
        //             'zone_id' => $item->zone_id,
        //             'regulation_id' => $item->regulation_id,
        //             'province_id' => $item->province_id,
        //             'shp' => $item->shp,
        //             'province' => $item->province->province,
        //             'zone_name' => $item->zone->zone_name,
        //             'regulation_name' => $item->regulation->regulation_name,
        //             'color' => $item->color, // Include color from the database
        //         ],
        //     ];
        // })
    // ->map(function ($item) {
    //     $item->shp = url('storage/' . $item->shp);
    //     return $item;
    // })
    ;

    // Return JSON response
    return response()->json([
        'success' => true,
        'data' => $kkprlmap,
        'message' => 'RZWP3K Data Retrieved Successfully',
    ]);

     // Convert to GeoJSON format
    //  $features = $query->get()->map(function ($item) {
    //     return [
    //         'type' => 'Feature',
    //         'geometry' => json_decode('storage/'.$item->shp), // Assuming shp is in GeoJSON format
    //         'properties' => [
    //             'id' => $item->id,
    //             'province' => $item->province->province,
    //             'zone_name' => $item->zone->zone_name,
    //             'regulation_name' => $item->regulation->regulation_name,
    //             'color' => $item->color, // Include color from the database
    //         ],
    //     ];
    // });

    // Return GeoJSON response
    // return response()->json([
    //     'success' => true,
    //     'data' => $features,
    //     'type' => 'FeatureCollection',
    //     'message' => 'RZWP3K Data Retrieved Successfully',
    // ]);
}
}
