<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\materials\add;
use App\Http\Requests\admin\materials\edit;
use App\Models\File;
use App\Models\Material;
use App\Models\MaterialTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class materials extends Controller
{
    public function index(Request $request){
        $materials = Material::where('status', '!=', -1)
                            ->where('subject_id', $request->get('subject'))
                            ->get();

        return view('admins.materials.index')->with([
            'materials'         => $materials,
            'curriculum_id'     => $request->get('curriculum'),
            'level_id'          => $request->get('level'),
            'year_id'           => $request->get('year'),
            'term_id'           => $request->get('term'),
            'subject_id'        => $request->get('subject'),
            'parms'             => 'curriculum=' . $request->get('curriculum') .
                                    '&&level=' . $request->get('level') . 
                                    '&&year=' . $request->get('year').
                                    '&&term=' . $request->get('term').
                                    '&&subject=' . $request->get('subject'),
        ]);
    }

    public function delete($material_id){
        //get material
        $material = Material::find($material_id);
        $parms = 'curriculum=' . $_GET['curriculum'] .
                '&&level=' . $_GET['level'] .
                '&&year=' . $_GET['year'] .
                '&&term=' . $_GET['term'].
                '&&subject=' . $_GET['subject'];


        if($material == null)
            return redirect('admins/materials?' . $parms)->with('error', 'delete faild');

        //delete file
        $file = $material->File->src;
        
        if(file_exists(base_path('public/uploads/materials/') . $file)){
            unlink(base_path('public/uploads/materials/') . $file);
        }

        $material->delete();

        return redirect('admins/materials?' . $parms)->with('success', 'delete material success');
    }

    public function createView(Request $request){
        return view('admins.materials.create');
    }

    public function create(add $request){
        $parms= 'curriculum=' . $_GET['curriculum'] .
                '&&level=' . $_GET['level'] .
                '&&year=' . $_GET['year'] .
                '&&term=' . $_GET['term'] .
                '&&subject=' . $_GET['subject'];

        try{
            DB::beginTransaction();
                //get cate status
                ($request->status== 1)? $active = 1: $active = 0;

                //create country
                $new_material = Material::create([
                    'status'            => $active,
                    'subject_id'        => $_GET['subject'],
                ]);

                //upload file
                $path = rand(0,1000000) . time() . '.' . $request->file('file')->getClientOriginalExtension();
                $request->file('file')->move(base_path('public/uploads/materials') , $path);

                 File::create([
                    'fileable_id'       => $new_material->id,
                    'fileable_type'     => 'App\Models\Material',
                    'src'               => $path,
                ]);

                foreach($request->materials as $key=>$material){
                    MaterialTranslation::create([
                        'name'              => $material['name'],
                        'locale'            => $key,
                        'material_id'       => $new_material['id'],
                    ]);
                }
            DB::commit();
            return redirect('admins/materials?' . $parms)->with('success', 'add material success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/materials?' . $parms)->with('error', 'add material faild');
        }
    }

    public function editView($material_id){
        $material = Material::find($material_id);
        $parms= 'curriculum=' . $_GET['curriculum'] .
                '&&level=' . $_GET['level'] .
                '&&year=' . $_GET['year'] .
                '&&term=' . $_GET['term'] .
                '&&subject=' . $_GET['subject'];

        //if material not found
        if($material == null)
            return redirect('admins/subjects?' . $parms);
        
        return view('admins.materials.edit')->with([
            'material'       => $material,
        ]);
    }

    public function edit($material_id,edit $request){
        $parms= 'curriculum=' . $_GET['curriculum'] .
                '&&level=' . $_GET['level'] .
                '&&year=' . $_GET['year'] .
                '&&term=' . $_GET['term'] .
                '&&subject=' . $_GET['subject'];
        try{
            $material = Material::find($material_id);
            $materialsTranslation = MaterialTranslation::where('material_id', $material_id)->get();
            
            DB::beginTransaction();
                //get cate status
                ($request->status== 1)? $active = 1: $active = 0;

                //edit subject
                $material->status           = $active;
                $material->save();

                //change all term trans name
                foreach($materialsTranslation as $materialTranslation){
                    $materialTranslation->name = $request->materials[$materialTranslation->locale]['name'];
                    $materialTranslation->save();
                }

            DB::commit();
            return redirect('admins/materials?' . $parms)->with('success', 'add material success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/materials?' . $parms)->with('error', 'add material faild');
        }
    }
}
