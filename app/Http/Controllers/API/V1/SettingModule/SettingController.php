<?php

namespace App\Http\Controllers\API\V1\SettingModule;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getSettings(Request $request, $keys)
    {
        if (!$request->user()->tokenCan("settings:get")) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à accéder à cette ressource."
            ], 403);
        }

        $requestedKeys = explode(',', $keys);

        $settings = [];
        foreach ($requestedKeys as $key) {
            // Assuming you have a settings table with columns 'key' and 'value'
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                $settings[$key] = $setting->value;
            } else {
                $settings[$key] = null;
            }
        }

        return response()->json($settings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
