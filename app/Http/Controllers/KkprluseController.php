<?php

namespace App\Http\Controllers;

use App\Models\Kkprl\Kkprluse;
use Illuminate\Http\Request;

class KkprluseController extends Controller
{
    public function kkprl(Request $request)
    {
         // Validate request
         $validated = $request->validate([
            'province_id' => 'nullable|array',
            'province_id.*' => 'integer',
            ]);

        // Set fallback defaults for filters
        $provinceId = $validated['province_id'] ?? []; // Default to an empty array
       
        // Query the database based on the provided filters
         // Query the data
        $query = Kkprluse::with(['province'])
            ->when(!empty($provinceId), function ($query) use ($provinceId) {
                $query->whereIn('province_id', $provinceId);
            });

        // Check if there are any matching records
        if (!$query->exists()) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'No records found for the provided filters.',
            ]);
        }

        $kkprluse = $query->get();

         // Return JSON response
        return response()->json([
            'success' => true,
            'data' => $kkprluse,
            'message' => 'KKPRL Data Retrieved Successfully',
        ]);



    }
}
