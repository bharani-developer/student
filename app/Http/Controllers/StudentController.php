<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use App\student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;




class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {if ($request->ajax()) {

        if(!empty($request->filter_gender))
        {
         $data = DB::table('students')
           ->select('name', 'gender')
           ->where('Gender', $request->filter_gender)
           
           ->get();
        }else{




        $data = Student::latest()->get();
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                       $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editstudent">Edit</a>';

                       $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletestudent">Delete</a>';

                        return $btn;
                })->filter(function ($instance) use ($request) {
                    if ($request->get('male') == 'male' || $request->get('female') == 'female') {
                        $instance->where('gender', $request->get('gender'));
                    }
                    if (!empty($request->get('gender'))) {
                         $instance->where(function($w) use($request){
                            $search = $request->get('gender');
                            $w->orWhere('gender', 'LIKE', "%$search%")
                            ->orWhere('gender', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                
                ->make(true);
            }
            return datatables()->of($data)->make(true);

    }
  
    return view('students.index',compact('students'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        student::updateOrCreate(['id' => $request->student_id],

        ['name' => $request->name, 
        'gender' => $request->gender,
        'address' => $request->address,
        'department' => $request->department,
        'branch' => $request->branch
        ]);        



return response()->json(['success'=>'Product saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit( $student_id)
    {
        $student = student::find($student_id);
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy( $student_id)
    {
        
        Student::find($student_id)->delete();
     
     
        return response()->json(['success'=>'student deleted successfully.']);
    }
    function import(Request $request)
    {
     $this->validate($request, [
      'select_file'  => 'required|mimes:xls,xlsx'
     ]);
     Excel::import(new StudentsImport, request()->file('select_file'));

     return back()->with('success', 'Excel Data Imported successfully.');
    }
}
