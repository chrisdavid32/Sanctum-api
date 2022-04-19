<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
   public function create(Request $request) 
   {
       $request->validate([
        'name' => 'required',
        'description' => 'required',
        'duration' => 'required'
       ]);
       $student_id = auth()->user()->id;
       $project = new Project();
       $project->student_id = $student_id;
       $project->name = $request->name;
       $project->description = $request->description;
       $project->duration = $request->duration;
       $project->save();
       return response()->json([
           'message' => 'Project Created Successfully',
           'status' => 201,
       ]);

   }

   public function projectList()
   {
       $student_id = auth()->user()->id;
       $project = Project::where('student_id', $student_id)->get();
       return response()->json([
        'status' => 200,
        'data' => $project
       ]);
   }

   public function singleProject($id)
   {
       $student_id = auth()->user()->id;
        if(Project::where(['id' => $id, 'student_id' => $student_id])->exists()){
            $detail = Project::where(['id' => $id, 'student_id' => $student_id])->first();
            return response()->json([
                'status' => 200,
                'data' => $detail
            ]); 
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'user not found'
            ]);
        }
   }
}
