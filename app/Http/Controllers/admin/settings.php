<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\settings\add;
use App\Models\Settings as ModelsSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class settings extends Controller
{
    public function editView(){
        $settings = ModelsSettings::first();

        //if settings not found
        if($settings == null)
            return redirect('admins/settings/edit');
        
        return view('admins.settings.edit')->with([
            'settings' => $settings,
        ]);
    }

    public function edit(add $request){
        $settings = ModelsSettings::first();

        try{
            DB::beginTransaction();
                //edit settings
                $settings->cost_students_number     = $request->cost_students_number;
                $settings->cost_level               = $request->cost_level;
                $settings->cost_country             = $request->cost_country;
                $settings->cost_company_percentage  = $request->cost_company_percentage;
                $settings->cost_year                = $request->cost_year;

                $settings->save();

            DB::commit();
            return redirect('admins/settings/edit')->with('success', 'add settings success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/settings/edit')->with('error', 'add settings faild');
        }
    }
}
