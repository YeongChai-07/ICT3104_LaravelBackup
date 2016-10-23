<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Hod;
use App\Module;
use App\Grade;
use Auth;
use Hash;
use DB;
use Session;

class HodController extends Controller {



    public function index()
    {
        $hodId = auth()->guard('hod')->user()->hodid;

        // $grades = DB::table('grades')
        //         ->where('studentid',$studentId)->paginate(5);


        $modules = DB::table('module')
            ->where('module.hodid', $hodId)->paginate(5);    


        return view('hod.index')->with([
            'modules' => $modules
            ]);  
    }

	
	    public function showManageGrade(Request $request, $id){

            $moduleid = $id;

            $module = Module::findorFail($id);


            $grades = DB::table('module')
            ->join('grades', 'grades.moduleid', '=', 'module.id')
            ->join('students','students.studentid', '=', 'grades.studentid')
            ->select('module.*','grades.*','students.*')
            ->where('grades.moduleid', $moduleid)->paginate(5);    

           
        return view('hod.managegrade')->with([
            'grades' => $grades,
            'module' => $module
            ]); 
    }






    public function showAddGrade(Request $request, $moduleid,$gradeid)
    {
        
        return view('hod.addgrade')->with([
            'moduleid' => $moduleid,
            'gradeid' => $gradeid
            ]); 
    }

    public function addGrade(Request $request, $moduleid,$gradeid)
    {
        $input= $request->all();
        $student = Grade::findorFail($gradeid);
        $recommendation = $request->only(['recommendation']);

        //return $request->only(['recommendation']);
        DB::table('grades')
                ->where('id', $gradeid)
                ->update(['grade' => $input['grade']]);          
          

            //if recommendation is empty dont run this insert into recommendation table
            //else then run this code
            // use isset
            if (!isset($recommendationid)){
            $recommendationid = DB::table('recommendation')->insertGetId([
            'recommendation' => $input['recommendation'], 
            'studentid' =>  $student->studentid,
            'lecturerid' => $student->lecturerid,
            'hodid' => $student->hodid,
            'moduleid' => $moduleid
            ]);
             Session::set('success_message', "Student Grade added sucessfully."); 
             return redirect()->route('manage_grade_hod', $moduleid);
            
            }      

    }
    public function displayPassword()
    {
       return view('hod.change');
    }

    public function updatePassword(Request $request)
    {   
        $input= $request->all();
        $hodid = auth()->guard('hod')->user();
    
        $validator = validator($request->all(),[
        'old-password' => 'required',
        'password' => 'required|min:3|confirmed',
        'password_confirmation' => 'required|min:3'

        ]);
        //Validate inputs
        if ($validator -> fails())
        {
            Session::set('error_message', "Password don't match");
            return redirect()->back(); 
        }
        
    
        if (Hash::check($input['old-password'],$hodid->password)) {
            $hash = Hash::make($input['password']);
            $hodid->password = $hash;
            $hodid->save();

            Session::set('success_message', "Password updated sucessfully.");
            } else {
                Session::set('error_message', "Old Password is wrong.");
            }
            
        return redirect()->back();
    } 

    
    public function showDetailsFunction() // added
    {
            $hodid = auth()->guard('hod')->user()->hodid;

            $hod = DB::table('hod')
            ->where('hodid',$hodid)->first();  
            return view('hod.showdetails',['hod' => $hod]);
    }

    // public function recommendation()
    // {
    //     $hodId = auth()->guard('hod')->user()->hodid;

    //     // $grades = DB::table('grades')
    //     //         ->where('studentid',$studentId)->paginate(5);


    //     // $recommendation = DB::table('module')
    //     //     ->join('recommendation', 'recommendation.moduleid', '=', 'module.id')
    //     //     ->join('grades', 'grades.moduleid', '=', 'module.id')
    //     //     ->join('students', 'students.studentid', '=', 'recommendation.studentid')
    //     //     ->select('module.*','recommendation.*','grades.*','students.*')
    //     //     ->where('grades.studentid', 'students.studentid')
    //     //     ->where('recommendation.hodid', $hodId)->paginate(5);    



    //     $recommendation = DB::table('recommendation')
    //                 ->join('module', 'module.id','=','recommendation.moduleid')
    //                 ->join('grades', 'grades.moduleid','=','module.id')
    //                 ->join('students','students.studentid','=','recommendation.studentid')
    //                 ->select('module.*','recommendation.*','grades.*','students.*')
    //                 ->where('recommendation.hodid', $hodId)->distinct()->paginate(5); 

    //     return view('hod.recommendation')->with([
    //         'recommendations' => $recommendation
    //         ]);      
    // }

}