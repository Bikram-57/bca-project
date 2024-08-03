<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Courses;
use App\Models\MaxMarksCO;
use App\Models\TargetMarks;
use App\Models\CoAttainment;
use App\Models\CoPoRelation;
use App\Models\SubjectMarks;
use Illuminate\Http\Request;
use App\Models\MoreThanSixty;
use App\Models\AssignedSubject;
use Illuminate\Validation\Rules;
use App\Models\FinalCoAttainment;
use App\Models\AttainmentPercentage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCourses = Courses::count();
        $totalFaculty = User::where('is_faculty', 1)->count();
        $uploaded = CoAttainment::join('courses', 'courses.cid', '=', 'co_attainment.cid')
            ->orderBy('co_attainment.updated_at', 'desc')
            ->select('courses.cname', 'co_attainment.cid', 'co_attainment.updated_at', 'co_attainment.batch')
            ->limit(10)
            ->get();
        // dd($uploaded);
        return view('admin-dashboard', ['uploaded' => $uploaded, 'courseCount' => $totalCourses, 'totalFaculty' => $totalFaculty]);
    }

    public function userDashboard()
    {
        // $courses = AssignedSubject::where('faculty_id', Auth::user()->id)->get();
        $courses = AssignedSubject::join('courses', 'assigned_subjects.cid', '=', 'courses.cid')
            ->select('courses.cname as cname', 'courses.cid as cid', 'courses.updated_at as updated_at')
            ->where('faculty_id', Auth::user()->id)
            ->get();

        // dd($courses);
        return view('user-dashboard', compact('courses'));
    }
    public function fetchView()
    {
        if (Auth::user()->is_faculty) {
            $courses = AssignedSubject::join('courses', 'assigned_subjects.cid', '=', 'courses.cid')
                ->select('courses.cname as cname', 'courses.cid as cid', 'courses.updated_at as updated_at')
                ->where('faculty_id', Auth::user()->id)
                ->orderBy('cname', 'asc')
                ->get();
        } else
            $courses = Courses::orderBy('cname', 'asc')->get();
        return view('fetch', ['courses' => $courses]);
    }
    public function fetchData(Request $r)
    {
        if (!CoAttainment::where('cid', $r->subjectId)->where('batch', $r->batch)->first()) {
            return back()->with('error', 'No data found, upload marks first');
        }

        $coPoRelation = CoPoRelation::where('cid', $r->subjectId)->get();

        if ($coPoRelation->isEmpty()) {
            return back()->with('error', 'CO PO Relation not found for this subject');
        }
        return view('show-data', ['subjectCode' => $r->subjectId, 'batch' => $r->batch]);
    }
    public function uploadView()
    {
        if (Auth::user()->is_faculty) {
            $courses = AssignedSubject::join('courses', 'assigned_subjects.cid', '=', 'courses.cid')
                ->select('courses.cname as cname', 'courses.cid as cid', 'courses.updated_at as updated_at')
                ->where('faculty_id', Auth::user()->id)
                ->orderBy('cname', 'asc')
                ->get();
            // dd($courses);
        } else
            $courses = Courses::orderBy('cname', 'asc')->get();
        return view('upload', ['courses' => $courses]);
    }

    public function addSubject(Request $request)
    {
        $request->validate([
            'cid' => 'required|string|max:10|unique:courses',
            'cname' => 'required|string|max:50',
            'course' => 'required|string|max:10',
        ], [
            'unique' => 'Subject already exists',
        ]);

        $courses = Courses::create([
            'cid' => strtoupper($request->cid),
            'cname' => $request->cname,
            'course' => $request->course,
        ]);

        if ($courses)
            return back()->with('success', 'Subject added successfully !');
        else
            return back()->with('error', 'Some error occured in adding subject !');
    }

    public function tables()
    {
        $tables = Schema::getAllTables();
        return view('tables', ['tables' => $tables]);
    }

    public function manageSubjects()
    {
        $courses = Courses::orderBy('updated_at', 'desc')->paginate(10);

        return view('manage-subjects', compact('courses'));
    }

    public function manageFaculty()
    {
        // $perPage = 10;
        // $currentPage = request()->get('page', 1);
        // $offset = ($currentPage - 1) * $perPage;
        // return view('manage-faculty', compact('faculty', 'currentPage', 'perPage'));

        $faculty = User::where('is_faculty', 1)->paginate(10);

        return view('manage-faculty', compact('faculty'));
    }
    public function updateSubject(Request $request)
    {
        // dd($request->all());
        $query = Courses::where('cid', $request->subjectId)->update(['cname' => $request->subject_name]);

        if ($query) {
            return back()->with('success', 'Subject details updated successfully');
        } else {
            return back()->with('error', 'Some error occured in updating subject details');
        }
    }

    public function deleteFaculty(Request $r)
    {
        $r->validate([
            'id' => 'required|numeric|max:9999999999',
        ]);


        if (AssignedSubject::where('faculty_id', $r->id)->first()) {
            return back()->with('error', 'Cannot delete, faculty is assigned to subjects');
        } else {
            $user = User::where(['id' => $r->id])->delete();

            if ($user)
                return back()->with('success', 'Faculty deleted Successfully !');
            else
                return back()->with('error', 'Some error occured in deleting faculty!');
        }

    }

    public function addFaculty(Request $r)
    {
        $r->validate([
            'id' => 'required|numeric|max:9999999999',
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email',
            'password' => ['required', 'string', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'regno' => $r->id,
            'name' => $r->name,
            'email' => $r->email,
            'password' => Hash::make($r->password),
        ]);

        if ($user)
            return back()->with('success', 'Faculty added Successfully !');
        else
            return back()->with('error', 'Some error occured in adding faculty!');
    }

    // ajax requests
    public function getCourseInfo($cid)
    {
        try {
            $courseInfo = Courses::where('cid', $cid)->get();
            return response()->json($courseInfo);
        } catch (\Exception $e) {
            return response()->json('Some error occured in fetching course details');
        }
    }
    public function getFacultyInfo($fid)
    {
        try {
            $facultyInfo = User::where('is_faculty', 1)->where('regno', $fid)->get();
            return response()->json($facultyInfo);
        } catch (\Exception $e) {
            return response()->json('Some error occured in fetching faculty details');
        }
    }

    public function getCOAttainment($cid, $batch)
    {
        // Attempt to retrieve data from the specified model
        $data = SubjectMarks::where('cid', $cid)->where('batch', $batch)->get();

        // Retrieve max marks
        $max_marks = MaxMarksCO::where('cid', $cid)->where('batch', $batch)->first();
        $coAttainment = CoAttainment::where('cid', $cid)->where('batch', $batch)->first();
        $targetMarks = TargetMarks::where('cid', $cid)->where('batch', $batch)->first();
        $more_than_sixty = MoreThanSixty::where('cid', $cid)->where('batch', $batch)->first();
        // dd($more_than_sixty);
        $attainment_percentage = AttainmentPercentage::where('cid', $cid)->where('batch', $batch)->first();

        return view('co_attainment', [
            'data' => $data,
            'attainment' => $coAttainment,
            'max_marks' => $max_marks,
            'targetMarks' => $targetMarks,
            'more_than_sixty' => $more_than_sixty,
            'attainment_percentage' => $attainment_percentage,
            'subjectCode' => $cid,
            'batch' => $batch,
            'subjectName' => Courses::where('cid', $cid)->pluck('cname')->first()
        ]);
    }

    public function bcaAnalysis()
    {
        $courses = Courses::where('course', 'BCA')->get();
        return view('bca', compact('courses'));
    }
    public function mcaAnalysis()
    {
        $courses = Courses::where('course', 'MCA')->get();
        return view('mca', compact('courses'));
    }

    public function getFinalCOAttainment($cid, $batch)
    {
        $co_attainment = CoAttainment::where('cid', $cid)->where('batch', $batch)->first();
        $finalCOAttainment = FinalCoAttainment::where('cid', $cid)->where('batch', $batch)->first();
        return view('final_co_attainment', ['finalCOAttainment' => $finalCOAttainment, 'co_attainment' => $co_attainment, 'subjectCode' => $cid, 'batch' => $batch, 'subjectName' => Courses::where('cid', $cid)->pluck('cname')->first()]);
    }
    public function getPOAttainment($cid, $batch)
    {
        $courses = Courses::all();
        // $relation = CoPoRelation::where('cid', $cid)->where('batch', $batch)->get();

        // for testing purpose batch is not added, but need to be added in production

        $relation = json_decode(CoPoRelation::where('cid', $cid)->pluck('co_po')->first(), true);
        // dd(json_decode($relation, true));
        $coAttainment = FinalCoAttainment::where('cid', $cid)->where('batch', $batch)->first();
        $subjectName = Courses::where('cid', $cid)->pluck('cname')->first();
        return view('po_attainment', compact('relation', 'courses', 'cid', 'batch', 'coAttainment', 'subjectName'));
    }

    public function coPoRelation()
    {
        if (Auth::user()->is_faculty) {
            $courses = AssignedSubject::join('courses', 'assigned_subjects.cid', '=', 'courses.cid')
                ->select('courses.cname as cname', 'courses.cid as cid', 'courses.updated_at as updated_at')
                ->where('faculty_id', Auth::user()->id)
                ->get();
        } else {
            $courses = Courses::paginate(10);
        }

        $relation = CoPoRelation::all();

        return view('co_po_relation', compact('relation', 'courses'));
    }
    // ajax requests
    public function getCoPoRelation($courseId)
    {
        $relation = CoPoRelation::where('cid', $courseId)->pluck('co_po')->first();
        if (is_null($relation)) {
            return response()->json('notfound');
        } else {
            return response()->json($relation);
        }
    }
    public function updateCoPoRelation(Request $r)
    {
        // add validation
        $CO_PO_Array = [
            'CO1' => json_encode($r->input('CO1'), true),
            'CO2' => json_encode($r->input('CO2'), true),
            'CO3' => json_encode($r->input('CO3'), true),
            'CO4' => json_encode($r->input('CO4'), true),
            'CO5' => json_encode($r->input('CO5'), true),
        ];

        $query = CoPoRelation::updateOrCreate(
            ['cid' => $r->courseId],
            ['co_po' => json_encode($CO_PO_Array, true)],
        );

        if ($query) {
            if ($query->wasRecentlyCreated)
                return back()->with('success', 'CO-PO Relation Uploaded Suceessfully');
            else
                return back()->with('success', 'CO-PO Relation Updated Suceessfully');
        } else {
            return back()->with('error', 'Some error occured in updating CO-PO Relation');
        }
    }

    public function searchCourses(Request $request)
    {
        $query = $request->input('query');
        $courses = Courses::where('cid', 'like', "%$query%")
            ->orWhere('cname', 'like', "%$query%")
            ->get();

        return response()->json($courses);
    }

    public function assignSubjectView()
    {
        $allCourses = Courses::all();

        $faculties = User::where('is_faculty', 1)->get();

        $assignedSubjects = AssignedSubject::with('course', 'user')->orderByDesc('created_at')->get();

        $facultyDropdown = [];

        foreach ($assignedSubjects as $assignedSubject) {
            // Extract relevant details
            $facultyId = $assignedSubject->faculty_id;
            $facultyName = $assignedSubject->user->name;  // Ensure you have a 'name' attribute or similar in the User model
            $subjectName = $assignedSubject->course->cname;   // Ensure you have a 'name' attribute or similar in the Course model
            $year = $assignedSubject->year;

            // Initialize the faculty in the dropdown array if not already present
            if (!isset($facultyDropdown[$facultyId])) {
                $facultyDropdown[$facultyId] = [
                    'name' => $facultyName,
                    'subjects' => []
                ];
            }

            // Add the subject with year to the faculty's array
            $facultyDropdown[$facultyId]['subjects'][] = [
                'year' => $year,
                'cid' => $assignedSubject->cid,
                'name' => $subjectName
            ];

        }
        // Sort the subjects for each faculty based on year in descending order
        foreach ($facultyDropdown as $facultyId => $facultyData) {
            usort($facultyDropdown[$facultyId]['subjects'], function ($a, $b) {
                return $b['year'] <=> $a['year']; // Sort by year descending
            });

        }
        
        return view('assign-subject', compact('allCourses', 'faculties', 'assignedSubjects', 'facultyDropdown'));
    }
    public function assignSubject(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:10',
            'faculty' => 'required|numeric',
            'year' => 'required|numeric',
        ]);

        $exists = AssignedSubject::where('cid', $request->subject)
            ->where('faculty_id', $request->faculty)
            ->where('year', $request->year)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Course already assigned to the faculty');
        }

        $query = AssignedSubject::create([
            'cid' => $request->subject,
            'faculty_id' => $request->faculty,
            'year' => $request->year,
        ]);

        Courses::where('cid', $request->subject)->update(['assigned' => 1]);

        if ($query)
            return back()->with('success', 'Subject assigned to faculty successfully');
        else
            return back()->with('error', 'Some error occured in assigning subject to faculty');
    }
    public function assignSubjectUpdate(Request $request)
    {
        try {
            foreach ($request->checked_courses as $id) {
                AssignedSubject::where('id', $id)->delete();
            }
            return back()->with('success', 'Assigned subjects updated successfully');
        } catch (Exception $e) {

            return back()->with('error', 'An error occurred while updating assigned subjects: ' . $e->getMessage());
        }
    }

    public function getAssignedSubjects(Request $request)
    {
        // Fetch assigned courses for the given faculty ID
        $assignedCourses = AssignedSubject::join('courses', 'assigned_subjects.cid', '=', 'courses.cid')
            ->join('users', 'assigned_subjects.faculty_id', '=', 'users.id')
            ->select('courses.cid as course_id', 'courses.cname as course_name', 'users.name as faculty_name', 'assigned_subjects.year', 'assigned_subjects.id')
            ->where('assigned_subjects.faculty_id', $request->faculty_id)
            ->orderByDesc('assigned_subjects.year')
            ->get();

        return response()->json($assignedCourses);
    }

    public function directPOAttainment(Request $request)
    {
        // dd($request->all());
        $batch = $request->batch;
        $course = $request->course;

        $cid = Courses::join('final_co_attainment', 'final_co_attainment.cid', '=', 'courses.cid')
            ->where('batch', $batch)
            ->where('courses.course', $course)
            ->pluck('courses.cid', 'courses.cname')
            ->map(function ($cid, $cname) {
                return $cid . ' - ' . $cname;
            })
            ->values() // Reset keys to indexed numeric format
            ->toArray();

        $poArray = CoPoRelation::join('final_co_attainment', 'final_co_attainment.cid', '=', 'co_po_relation.cid')
            ->leftJoin('courses', 'courses.cid', '=', 'final_co_attainment.cid')
            ->where('final_co_attainment.batch', $batch)
            ->where('courses.course', $course)
            ->pluck('co_po_relation.co_po')
            ->toArray();


        $grandTotalArray = FinalCoAttainment::join('courses', 'courses.cid', '=', 'final_co_attainment.cid')
            ->where('final_co_attainment.batch', $batch)
            ->where('courses.course', $course)
            ->pluck('final_co_attainment.grand_total')
            ->map(function ($item) {
                return json_decode($item, true);
            });

        return view('direct-po-attainment', compact('cid', 'poArray', 'grandTotalArray', 'batch', 'course'));
    }

    public function testPage()
    {
        $courses = Courses::all();
        return view('test-page', compact('courses'));
    }

    public function facultyInfo(Request $request)
    {
        if ($request->searchData == "") {
            $faculty = User::where('is_faculty', 1)->get();
        } else {
            $faculty = User::where('regno', 'LIKE', '%' . $request->searchData . '%')
                ->orWhere('name', 'LIKE', '%' . $request->searchData . '%')
                ->where('is_faculty', 1)
                ->get();
        }

        return view('faculty-table', compact('faculty'));
    }
    public function getAssignedFaculty(Request $request)
    {
        $allCourses = Courses::where('assigned', 0)->get();

        $faculties = User::where('is_faculty', 1)->get();

        $assignedSubjects = AssignedSubject::join('courses', 'assigned_subjects.cid', '=', 'courses.cid')
            ->join('users', 'assigned_subjects.faculty_id', '=', 'users.id')
            ->select('assigned_subjects.*', 'courses.cname as course_name', 'users.name as faculty_name', 'users.id as faculty_id')
            ->where('users.name', 'LIKE', '%' . $request->searchData . '%')
            ->get(10);

        $facultyDropdown = [];
        foreach ($assignedSubjects as $assignedSubject) {
            $facultyId = $assignedSubject->faculty_id;
            $facultyName = $assignedSubject->faculty_name;
            $subjectName = $assignedSubject->course_name;

            // If faculty name is not in the dropdown array, initialize it with an empty array
            if (!isset($facultyDropdown[$facultyName])) {
                $facultyDropdown[$facultyName] = [];
            }

            // Add the subject to the faculty's dropdown array
            $facultyDropdown[$facultyName][$facultyId][$assignedSubject->cid] = $subjectName;
        }

        return view('assigned-subjects-filter-table', compact('allCourses', 'faculties', 'assignedSubjects', 'facultyDropdown'));
    }

    public function getCourses(Request $request)
    {
        if ($request->searchData == "") {
            $courses = Courses::orderBy('updated_at', 'desc')->get();
        } else {
            $courses = Courses::where('cid', 'LIKE', '%' . $request->searchData . '%')
                ->orWhere('cname', 'LIKE', '%' . $request->searchData . '%')
                ->get();
        }

        return view('co_po_relation-table', compact('courses'));
    }

    public function updateFaculty(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255',
        ]);

        $update = User::where('regno', $request->regno)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($update)
            return back()->with('success', 'Faculty details updated successfully');
        else
            return back()->with('error', 'Some error occured while updating faculty details');
    }

    public function deleteSubject(Request $request)
    {
        $course = Courses::where('cid', $request->cid)->first();
        // dd($course);

        if ($course['assigned']) {
            return redirect()->back()->with('error', 'This subject is assigned and cannot be deleted');
        } else {
            $delete = Courses::where('cid', $request->cid)->delete();

            if ($delete)
                return redirect()->back()->with('success', 'Subject deleted successfully');
            else
                return redirect()->back()->with('error', 'Some error occured in deleting subject');
        }
    }

    // ajax request
    public function getSubjects($course)
    {
        if (Auth::user()->is_faculty) {
            $courses = Courses::join('assigned_subjects', 'assigned_subjects.cid', '=', 'courses.cid')
                ->where('course', $course)
                ->pluck('courses.cid', 'courses.cname');

        } else {
            $courses = Courses::where('course', $course)->pluck('cid', 'cname');
        }
        return response()->json($courses);
    }
    public function getSubjectData($cid)
    {
        $attainment = FinalCoAttainment::where('cid', $cid)->pluck('final_co_attainment')->all();
        return response()->json($attainment);
    }
}
