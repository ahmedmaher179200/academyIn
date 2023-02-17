<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\main_subject\add;
use App\Models\Image;
use App\Models\Main_subject;
use App\Models\Main_subjectTranslation;
use App\Http\Requests\admin\image as AdminImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class main_subjects extends Controller
{
    public function index(){
        $main_subjects = Main_subject::where('status', '!=', -1)->get();
        return view('admins.main_subjects.index')->with('main_subjects', $main_subjects);
    }

    public function delete($main_subject_id){
        //get main_subject 
        $main_subject = Main_subject::find($main_subject_id);

        if($main_subject == null)
            return redirect('admins/main_subjects')->with('error', 'delete faild');

        $main_subject->status = -1;
        $main_subject->save();

        return redirect('admins/main_subjects')->with('success', 'delete main_subject success');
    }

    public function createView(){
        return view('admins.main_subjects.create');
    }

    public function create(add $request){
        try{
            DB::beginTransaction();
                //get cate status
                ($request->status== 1)? $active = 1: $active = 0;

                //create main_subject
                $new_main_subject = Main_subject::create([
                    'status'            => $active,
                ]);

                foreach($request->main_subjects as $key=>$main_subject){
                    Main_subjectTranslation::create([
                        'name'              => $main_subject['name'],
                        'locale'            => $key,
                        'main_subject_id'   => $new_main_subject['id'],
                    ]);
                }

                //add image
                $path = $this->upload_image($request->file('image'),'uploads/main_subjects', 100, 100);
                Image::create([
                    'imageable_id'   => $new_main_subject->id,
                    'imageable_type' => 'App\Models\Main_subject',
                    'src'            => $path,
                ]);
                
            DB::commit();
            return redirect('admins/main_subjects')->with('success', 'add success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/main_subjects')->with('error', 'add faild');
        }
    }

    public function editView($main_subject_id){
        $main_subject = Main_subject::find($main_subject_id);

        //if admin not found
        if($main_subject == null)
            return redirect('admins/main_subjects');
        
        return view('admins.main_subjects.edit')->with([
            'main_subject' => $main_subject,
        ]);
    }

    public function edit($main_subject_id,AdminImage $request){
        $main_subject = Main_subject::find($main_subject_id);
        $main_subjectsTranslation = Main_subjectTranslation::where('main_subject_id', $main_subject_id)->get();

        try{
            DB::beginTransaction();
                //get cate status
                ($request->status== 1)? $active = 1: $active = 0;

                //edit main_subject
                $main_subject->name              = $request->main_subjects['en']['name'];
                $main_subject->status            = $active;
                $main_subject->save();

                //update image
                if($request->has('image')){
                    $path = $this->upload_image($request->file('image'),'uploads/main_subjects', 100, 100);

                    if($main_subject->Image == null){
                        //if user don't have image 
                        Image::create([
                            'imageable_id'   => $main_subject->id,
                            'imageable_type' => 'App\Models\Main_subject',
                            'src'            => $path,
                        ]);

                    } else {
                        //if main_subject have image
                        $oldImage = $main_subject->Image->src;

                        if(file_exists(base_path('public/uploads/main_subjects/') . $oldImage)){
                            unlink(base_path('public/uploads/main_subjects/') . $oldImage);
                        }

                        $main_subject->Image->src = $path;
                        $main_subject->Image->save();
                    }
                }
                //change all main_subject trans name
                foreach($main_subjectsTranslation as $main_subjectTranslation){
                    $main_subjectTranslation->name = $request->main_subjects[$main_subjectTranslation->locale]['name'];
                    $main_subjectTranslation->save();
                }

            DB::commit();
            return redirect('admins/main_subjects')->with('success', 'add main_subject success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/main_subjects')->with('error', 'add main_subject faild');
        }
    }
}
