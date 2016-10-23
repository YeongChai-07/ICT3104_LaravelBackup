<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Admin;
use App\Lecturer;
use App\Student;
use App\Hod;
use App\Module;
use App\Enroll;
use App\Recommendation;
use App\Grade;
use Auth;
use Hash;
use DB;
use Session;
use DateTime;

//Importing the Artisan Facade
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller {


	/**
    public function index()
    {
        $adminId = auth()->guard('admin')->user()->id;

        $students = DB::table('students')->paginate(5);
        return view('admin.index')->with([
            'students' => $students
            ]);
    }

*/

	/** --- LECTURER CONTROL ---*/
    public function showLecturer()
    {
        $adminId = auth()->guard('admin')->user()->id;

        $lecturers = DB::table('lecturer')->paginate(5);
        return view('admin.lecturer')->with([
            'lecturers' => $lecturers
            ]);
    }


    public function showAddLecturer(){
        return view('admin.addlecturer');
    }

    public function addLecturer(Request $request)
    {
        $input = $request->all();
        $emailcheck = Lecturer::where('lectureremail', $input['email'])
                    ->first();

        $id = $emailcheck['lecturerid'];
        if(!$id)
        {
            $password = substr(md5(uniqid(mt_rand(), true)) , 0, 6);
            $password = 'demo123';
            $hash = Hash::make($password);

            $lecturerid = DB::table('lecturer')->insertGetId([
            'lecturername' => $input['name'], 
            'lectureremail' =>  $input['email'],
			'contact' =>  $input['contact'],
            'password' => $hash
            ]);    
        }
        else
        {
            Session::set('error_message', "Lecturer Exists");
            return redirect()->back();             
        }


            Session::set('success_message', "Lecturer Created Successfully");
            return redirect()->back(); 
    }


    public function editLecturer($id)
    {
    
            $lecturer = Lecturer::where('lecturerid',$id)                              
                                ->first();  
        
            
            
            return view('admin.editlecturer',['lecturer' => $lecturer]);
    }

    /**
     * Update Teacher Details based on inputs
     *
     * @param  $id
     * @return View User editteacher 
     */
    public function updateLecturer($id, Request $request) 
    {
          
        $lecturer = Lecturer::findorFail($id);
                  
        
       $input= $request->all();
       //check if duplicate email
       if(trim($lecturer->lectureremail) == trim($input['email']))
       {
           $emailvalidation = 'required|email';
       }
       else
       {
           $emailvalidation = 'required|email|unique:lecturer,lectureremail';
       }

        $validator= validator($request->all(), [
                'name' => 'required',
                'email' => $emailvalidation
        ]);

   
        //Validate inputs
        if ($validator -> fails())
        {
            Session::set('error_message', "Email already exists");
            return redirect()->back();
        }     
        
       
       DB::table('lecturer')
                ->where('lecturerid', $id)
                ->update(['lecturername' => $input['name'],'lectureremail' => $input['email'], 'contact' =>  $input['contact']]);          
          
                Session::set('success_message', "Profile updated sucessfully."); 
               return redirect()->back();
          
    }

    public function deleteLecturer($id){
        
           DB::table('lecturer')->where('lecturerid', $id)->delete();
                                       
        Session::set('success_message', "Lecturer deleted Successfully");  
        return redirect()->back();
    }


	
	/** --- HOD CONTROL ---*/
	public function showHod()
    {
        $adminId = auth()->guard('admin')->user()->id;

        $hods = DB::table('hod')->paginate(5);
        return view('admin.hod')->with([
            'hods' => $hods
            ]);
    }
    public function showAddHod(){
        return view('admin.addhod');
    }

    public function addHod(Request $request)
    {
        $input = $request->all();
        $emailcheck = Hod::where('hodemail', $input['email'])
                    ->first();

        $id = $emailcheck['hodid'];
        if(!$id)
        {
            $password = substr(md5(uniqid(mt_rand(), true)) , 0, 6);
            $password = 'demo123';
            $hash = Hash::make($password);

            $studentid = DB::table('hod')->insertGetId([
            'hodname' => $input['name'], 
            'hodemail' =>  $input['email'],
			'contact' =>  $input['contact'],
            'password' => $hash
            ]);


                         
        }
        else
        {
            Session::set('error_message', "Hod Exists");
            return redirect()->back();             
        }


            Session::set('success_message', "Hod Created Successfully");
            return redirect()->back(); 
    }

    public function editHod($id)
    {
    
            $hod = hod::where('hodid',$id)                              
                                ->first();  
        
            
            
            return view('admin.edithod',['hod' => $hod]);
    }

    /**
     * Update Teacher Details based on inputs
     *
     * @param  $id
     * @return View User editteacher 
     */
    public function updateHod($id, Request $request) 
    {
          
        $hod = Hod::findorFail($id);
                  
        
       $input= $request->all();
       //check if duplicate email
       if(trim($hod->hodemail) == trim($input['email']))
       {
           $emailvalidation = 'required|email';
       }
       else
       {
           $emailvalidation = 'required|email|unique:hod,hodemail';
       }

        $validator= validator($request->all(), [
                'name' => 'required',
                'email' => $emailvalidation,
        ]);

   
        //Validate inputs
        if ($validator -> fails())
        {
            Session::set('error_message', "Email already exists");
            return redirect()->back();
        }     
        
       
       DB::table('hod')
                ->where('hodid', $id)
                ->update(['hodname' => $input['name'],'hodemail' => $input['email'], 'contact' =>  $input['contact']]);          
          
                Session::set('success_message', "Profile updated sucessfully."); 
               return redirect()->back();
          
    }

    public function deleteHod($id){
        
           DB::table('hod')->where('hodid', $id)->delete();
                                       
        Session::set('success_message', "Hod deleted Successfully");  
        return redirect()->back();
    }

	
	/** --- ADMIN CONTROL --- */
	
	public function showAdmin()
    {
        $adminId = auth()->guard('admin')->user()->id;

        $admins = DB::table('admin')->paginate(5);
        return view('admin.admin')->with([
            'admins' => $admins
            ]);
    }

    public function showAddAdmin(){
        return view('admin.addadmin');
    }

    public function addAdmin(Request $request)
    {
        $input = $request->all();
        $emailcheck = Admin::where('adminemail', $input['email'])
                    ->first();

        $id = $emailcheck['adminid'];
        if(!$id)
        {
            $password = substr(md5(uniqid(mt_rand(), true)) , 0, 6);
            $password = 'demo123';
            $hash = Hash::make($password);

            $adminid = DB::table('admin')->insertGetId([
            'adminname' => $input['name'], 
            'adminemail' =>  $input['email'],
			'contact' =>  $input['contact'],
            'password' => $hash
            ]);

 
        }
        else
        {
            Session::set('error_message', "Admin Exists");
            return redirect()->back();             
        }


            Session::set('success_message', "Admin Created Successfully");
            return redirect()->back(); 
    }

    public function editAdmin($id)
    {
    
            $admin = Admin::where('adminid',$id)                              
                                ->first();  
        
            
            
            return view('admin.editadmin',['admin' => $admin]);
    }

    /**
     * Update Student Details based on inputs
     *
     * @param  $id
     * @return View User editteacher 
     */
    public function updateAdmin($id, Request $request) 
    {
          
        $admin = Admin::findorFail($id);
                  
        
       $input= $request->all();
       //check if duplicate email
       if(trim($admin->adminemail) == trim($input['email']))
       {
           $emailvalidation = 'required|email';
       }
       else
       {
           $emailvalidation = 'required|email|unique:admin,adminemail';
       }

        $validator= validator($request->all(), [
                'name' => 'required',
                'email' => $emailvalidation,
        ]);

   
        //Validate inputs
        if ($validator -> fails())
        {
            Session::set('error_message', "Email already exists");
            return redirect()->back();
        }     
        
       
       DB::table('admin')
                ->where('adminid', $id)
                ->update(['adminname' => $input['name'],'adminemail' => $input['email'], 'contact' =>  $input['contact']]);          
          
                Session::set('success_message', "Admin Profile updated sucessfully."); 
               return redirect()->back();
          
    }

    public function deleteAdmin($id){
        
           DB::table('admin')->where('adminid', $id)->delete();
                                       
        Session::set('success_message', "Admin deleted Successfully");  
        return redirect()->back();
    } 
	
	
	/** --- MODULE CONTROL -- */

    public function showModule(){


        $modules = DB::table('module')
            ->join('lecturer','lecturer.lecturerid', '=', 'module.lecturerid')
            ->join('hod','hod.hodid', '=','module.hodid')
            ->select('module.*','hod.*','lecturer.*')->paginate(5);


        return view('admin.module')->with([
            'modules' => $modules
            ]);


    }


    public function showAddModule(){

            $lecturers = Lecturer::all();
            $hods = Hod::all();

            return view('admin.addmodule')->with([
            'lecturers' => $lecturers,
            'hods' => $hods
            ]);

    }
    public function addModule(Request $request)
    {
        //To be used later when comparing dates
        //$today = (new DateTime())->format('Y-m-d');

        $input = $request->all();

        if(!isset($input['editdate']) || !isset($input['freezedate']))
        {
            Session::set('error_message', "Please Enter Dates");
            return redirect()->back();  
        }   

        $editdata = $request->only(['editdate']);
        $freezedata = $request->only(['freezedate']);


        $editDateString = implode(';', $editdata);
        $editdate = (new DateTime($editDateString))->format('Y-m-d');

        
        $freezeDateString = implode(';', $freezedata);
        $freezedate = (new DateTime($freezeDateString))->format('Y-m-d');

        $modulecheck = Module::where('modulename', $input['name'])
                    ->first();

        if($editdate >= $freezedate)
        {

            Session::set('error_message', "Edit Date must be before Freeze Date");
            return redirect()->back();    
        }

        $id = $modulecheck['id'];
        if(!$id)
        {        
            $moduleid = DB::table('module')->insertGetId([
            'modulename' => $input['name'], 
            'description' =>  $input['description'],
            'lecturerid' => $input['lecturer'],
            'hodid' => $input['hod'],
            'editdate' => $editdate,
            'freezedate' => $freezedate
            ]);

        }
        else
        {

            Session::set('error_message', "Module Exists");
            return redirect()->back();             
        }


            Session::set('success_message', "Module Created Successfully");
            return redirect()->back(); 
    }

    public function editModule($id)
    {
    
            $module = Module::where('id',$id)                              
                                ->first();  
        
            $lecturers = Lecturer::all();
            $hods = Hod::all();

            return view('admin.editmodule')->with([
            'lecturers' => $lecturers,
            'hods' => $hods,
            'module' => $module
            ]);
            
    }

    public function updateModule($id, Request $request) 
    {
          
        $module = Module::findorFail($id);
                  
        
       $input= $request->all();

        $editdata = $request->only(['editdate']);
        $freezedata = $request->only(['freezedate']);


        $editDateString = implode(';', $editdata);
        $editdate = (new DateTime($editDateString))->format('Y-m-d');

        
        $freezeDateString = implode(';', $freezedata);
        $freezedate = (new DateTime($freezeDateString))->format('Y-m-d');

        $modulecheck = Module::where('modulename', $input['name'])
                    ->first();

        if($editdate >= $freezedate)
        {

            Session::set('error_message', "Edit Date must be before Freeze Date");
            return redirect()->back();    
        }        
       
       DB::table('module')
                ->where('id', $id)
                ->update(['modulename' => $input['name'],'description' => $input['description'],'lecturerid' => $input['lecturer'],'hodid' => $input['hod'],'editdate' => $editdate, 'freezedate' => $freezedate]);          
          
                Session::set('success_message', "Module updated sucessfully."); 
               return redirect()->back();
          
    }

    public function deleteModule($id){
        
           DB::table('module')->where('id', $id)->delete();
           DB::table('grades')->where('moduleid',$id)->delete();
           DB::table('recommendation')->where('moduleid',$id)->delete();

        Session::set('success_message', "Module deleted Successfully");  
        return redirect()->back();
    }

	
	/** --- ENROL CONTROL --- */
    public function displayStudent($id)
    {

        $students = DB::select('select * from students WHERE NOT EXISTS (SELECT * FROM enroll WHERE students.studentid = enroll.studentid and enroll.moduleid = ?)', [$id]);

        return view('admin.enrollstudent')->with([
            'students' => $students,
            'id'   => $id
            ]);
    }



    public function enrollStudent(Request $request,$id)
    {
         $input= $request->all();

        if(!isset($input['chkid']))
        {
            Session::set('error_message', "Please Check at least one");
            return redirect()->back();  
        }

         $enroll = $input['chkid'];


         $module = Module::findorFail($id);

         for($i=0;$i<count($enroll);$i++)
         {
            $enrollid = DB::table('enroll')->insertGetId([
            'moduleid' => $id, 
            'studentid' =>  $enroll[$i]
            ]);

            $gradeid = DB::table('grades')->insertGetId([
            'moduleid' => $module->id,
            'studentid' => $enroll[$i],
            'lecturerid'=> $module->lecturerid,
            'hodid' => $module->hodid,
            'publish' => 0



            ]);
         }
         
        Session::set('success_message', "Student enrolled Successfully");  
        return redirect()->back();
    }
	
	
	/** --- ROUTINE OPERATIONS --- */
	public function backupSystem()
	{
		return view('admin.backupsystem');
	}
	
	public function processSystemBackup()
	{
		$artisanCall_Result = Artisan::call('backup:run', []);
	}


    

	/** --- UPDATE PERSONAL INFO  ---*/
    public function displayDetails()
    {

            $userid = auth()->guard('admin')->user()->id; 

              $user = User::where('id',$userid)                              
                                ->first();  

            return view('admin.editdetails',['user' => $user]);
    
}

	/** --- RECOMMENDATION --- */
    public function showRecommendation($moduleid)
    {

        $module = Module::findorFail($moduleid);

        $recommendations = DB::table('recommendation')
            ->join('students','students.studentid', '=', 'recommendation.studentid')
            ->select('recommendation.*','students.*')
            ->where('recommendation.moduleid', $moduleid)
            ->where('recommendation.status', 1)->paginate(5);  

            return view('admin.moderate')->with([
                'module' => $module,
                'recommendations' => $recommendations
                ]); 
    }

	
	/** --- MODERATE GRADES --- */
    public function moderateGrade($moduleid,$recommendationid)
    {

        $recommendation = Recommendation::findorFail($recommendationid);
        $grade = Grade::where('studentid', $recommendation->studentid)
                    ->where('moduleid', $recommendation->moduleid)
                    ->first();

        $finalgrade = $grade->grade + $recommendation->moderation;


        DB::table('grades')
                ->where('id', $grade->id)
                ->update(['grade' => $finalgrade]);

        DB::table('recommendation')
                ->where('id', $recommendationid)
                ->update(['status' => 3]);   

        Session::set('success_message', "Student Grade moderated.");
        return redirect()->back();
    }

}