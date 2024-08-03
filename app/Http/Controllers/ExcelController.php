<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Courses;
use App\Models\MaxMarksCO;
use App\Models\TargetMarks;
use App\Models\CoAttainment;
use App\Models\CoPoRelation;
use App\Models\SubjectMarks;
use Illuminate\Http\Request;
use App\Models\MoreThanSixty;
use App\Models\FinalCoAttainment;
use App\Models\AttainmentPercentage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController extends Controller
{
    protected $updated = 0;
    public function saveData($dataArray, $regno, $batch, $cid)
    {
        // Convert Q1 and S1 arrays to JSON
        $jsonQ1 = json_encode($dataArray['Q1']);
        $jsonS1 = json_encode($dataArray['S1']);
        $jsonQ2 = json_encode($dataArray['Q2']);
        $jsonS2 = json_encode($dataArray['S2']);
        $jsonAssignment = json_encode($dataArray['Assignment']);
        $jsonEndSem = json_encode($dataArray['End Sem']);

        if (strtolower($regno) == strtolower("Max Marks/CO")) {
            // if already exists then update
            $existingRecord = MaxMarksCO::where('cid', $cid)
                ->where('batch', $batch)
                ->first();

            if ($existingRecord) {
                $existingRecord->Q1 = $jsonQ1;
                $existingRecord->S1 = $jsonS1;
                $existingRecord->Q2 = $jsonQ2;
                $existingRecord->S2 = $jsonS2;
                $existingRecord->assignment = $jsonAssignment;
                $existingRecord->end_sem = $jsonEndSem;

                if ($existingRecord->save()) {
                    // If the update operation is successful
                    $this->updated = 1;
                    return true;
                } else {
                    // If there's an error during the update operation
                    return false;
                }
            } else {
                $model = new MaxMarksCO();
                $model->cid = $cid;
                $model->batch = $batch;
                $model->Q1 = $jsonQ1;
                $model->S1 = $jsonS1;
                $model->Q2 = $jsonQ2;
                $model->S2 = $jsonS2;
                $model->assignment = $jsonAssignment;
                $model->end_sem = $jsonEndSem;

                if ($model->save()) {
                    // If the save operation is successful
                    return True;
                } else {
                    // If there's an error during the save operation
                    return False;
                }
            }
        } else {
            $model = SubjectMarks::where('cid', $cid)
                ->where('batch', $batch)
                ->where('regno', $regno)
                ->first();

            // dd($model, $regno);

            if ($model) {
                // If a record already exists, delete it and save new data

                // Delete the existing record
                SubjectMarks::where('cid', $cid)->where('batch', $batch)->delete();

                $newModel = new SubjectMarks();
                $newModel->cid = $cid;
                $newModel->batch = $batch;
                $newModel->regno = $regno;
                $newModel->Q1 = $jsonQ1;
                $newModel->S1 = $jsonS1;
                $newModel->Q2 = $jsonQ2;
                $newModel->S2 = $jsonS2;
                $newModel->assignment = $jsonAssignment;
                $newModel->end_sem = $jsonEndSem;

                if ($newModel->save()) {
                    // If the update operation is successful
                    $this->updated = 1;
                    return true;
                } else {
                    // If there's an error during the update operation
                    return false;
                }
            } else {
                // If no record exists, create a new one
                $model = new SubjectMarks();
                $model->cid = $cid;
                $model->batch = $batch;
                $model->regno = $regno;
                $model->Q1 = $jsonQ1;
                $model->S1 = $jsonS1;
                $model->Q2 = $jsonQ2;
                $model->S2 = $jsonS2;
                $model->assignment = $jsonAssignment;
                $model->end_sem = $jsonEndSem;

                if ($model->save()) {
                    // If the save operation is successful
                    return true;
                } else {
                    // If there's an error during the save operation
                    return false;
                }
            }
        }
    }
    function getCOLevel($attainmentPercentage)
    {
        if (is_null($attainmentPercentage))
            return null;
        if ($attainmentPercentage < 38)
            return 0;
        elseif ($attainmentPercentage >= 38 && $attainmentPercentage <= 51)
            return 1;
        elseif ($attainmentPercentage >= 52 && $attainmentPercentage <= 72)
            return 2;
        elseif ($attainmentPercentage >= 73)
            return 3;
    }

    // calculate final co attainment
    function calculateFinalCOAttainment($cid, $batch)
    {
        $co_attainment = CoAttainment::where('cid', $cid)->where('batch', $batch)->first();
        $q1 = json_decode($co_attainment['q1'], true);
        $s1 = json_decode($co_attainment['s1'], true);
        $q2 = json_decode($co_attainment['q2'], true);
        $s2 = json_decode($co_attainment['s2'], true);
        $assignment = json_decode($co_attainment['assignment'], true);
        $end_sem = json_decode($co_attainment['end_sem'], true);

        $examArray = [$q1, $s1, $q2, $s2, $assignment, $end_sem];

        $totalAvgIntArray = [];
        $grandTotal = [];

        for ($i = 1; $i <= 5; $i++) {
            $coMarksArray = [];

            foreach ($examArray as $exam) {
                if (isset($exam["CO$i"]) && !is_null($exam["CO$i"]) && $exam !== $end_sem) {
                    $coMarksArray[] = $exam["CO$i"];
                }
            }

            $totalAvgIntArray["CO$i"] = count($coMarksArray) > 0 ? round(array_sum($coMarksArray) / count($coMarksArray), 2) : null;
        }

        // Calculate grandTotal
        foreach ($totalAvgIntArray as $key => $totalAvgInt) {
            if (!is_null($totalAvgInt)) {
                $grandTotal[$key] = round(($totalAvgInt + $end_sem[$key]) / 2, 2);
            }
        }
        // dd($grandTotal);

        $finalCOAttainment = round(array_sum($grandTotal) / count($grandTotal), 2);

        // Convert arrays to JSON
        $totalAvgInternalJSON = json_encode($totalAvgIntArray);
        $grandTotalJSON = json_encode($grandTotal);


        // Update or create the record in the database
        $query = FinalCoAttainment::updateOrCreate(
            ['cid' => $cid, 'batch' => $batch],
            [
                'total_avg_internal' => $totalAvgInternalJSON,
                'grand_total' => $grandTotalJSON,
                'final_co_attainment' => $finalCOAttainment,
            ]
        );

        if (!$query) {
            return back()->with('error', 'Some error occured in uploading/updating CO Attainment');
        } else {
            return true;
        }
        // Optionally, you can return the calculated values or perform any other actions
    }
    public function calculate($cid, $batch)
    {
        // Attempt to retrieve data from the specified model
        $data = SubjectMarks::where('cid', $cid)->where('batch', $batch)->get();

        // Retrieve max marks
        $max_marks = MaxMarksCO::where('cid', $cid)->where('batch', $batch)->first();

        $examArray = ['q1', 's1', 'q2', 's2', 'assignment', 'end_sem'];
        $target_marks = [];

        for ($i = 0; $i < count($examArray); $i++) {
            $marks = json_decode($max_marks[$examArray[$i]], true);
            $copy_marks = $marks;

            foreach ($copy_marks as $key => $x) {
                if (!is_null($x)) {
                    $copy_marks[$key] = round((60 / 100) * $x, 2);
                }
            }
            $target_marks[$examArray[$i]] = $copy_marks;
        }


        // save target marks to database
        $query = TargetMarks::updateOrCreate(
            ['cid' => $cid, 'batch' => $batch],
            [
                'q1' => json_encode($target_marks['q1'], true),
                's1' => json_encode($target_marks['s1'], true),
                'q2' => json_encode($target_marks['q2'], true),
                's2' => json_encode($target_marks['s2'], true),
                'assignment' => json_encode($target_marks['assignment'], true),
                'end_sem' => json_encode($target_marks['end_sem'], true),
            ]
        );

        if (!$query) {
            return back()->with('error', 'Some error occured in uploading/updating target marks');
        }

        $co_po = [
            'CO1' => null,
            'CO2' => null,
            'CO3' => null,
            'CO4' => null,
            'CO5' => null,
            'Total' => null,
        ];

        $copy_co_po = [];
        $target_marks_count = 0;
        $marks_more_than_sixty_percent_array = [];
        $co_attainment = [];

        $attainmentPercentage = [];
        $attainmentPercentage_CO_PO = [];
        $co_attainment_CO_PO = [];
        $index = 0;

        for ($i = 0; $i < count($target_marks); $i++) {
            // here key is co1, co2, co3..
            foreach ($co_po as $key => $v) {
                foreach ($data as $d) {
                    $marks = json_decode($d[$examArray[$index]], true);

                    if (is_null($marks[$key]) || strtoupper($marks[$key]) == "AB" || strtoupper($marks[$key]) == "DT") {
                        if (is_null($marks[$key])) {
                            $target_marks_count = null;
                        } else if ($target_marks_count === 0) {
                            $target_marks_count = 0;
                        } else
                            continue;
                    } else {
                        if ($marks[$key] >= $target_marks[$examArray[$index]][$key]) {
                            $target_marks_count++;
                        } else
                            continue;
                    }
                }

                // store target marks count
                $copy_co_po[$key] = $target_marks_count;

                // calculate attainment percentage
                // check for null
                if ($target_marks_count === 0) {
                    $attainmentPercentage_CO_PO[$key] = 0;
                } else if ($target_marks_count === null) {
                    $attainmentPercentage_CO_PO[$key] = null;
                } else {
                    $attainmentPercentage_CO_PO[$key] = intval(($target_marks_count / count($data)) * 100);
                }

                // calculate co attainment level


                $co_attainment_CO_PO[$key] = $this->getCOLevel($attainmentPercentage_CO_PO[$key]);

                // reset target_marks_count to 0 for counting next CO
                $target_marks_count = 0;
            }

            // store the copy array to q1, s1, q2, respectively
            $marks_more_than_sixty_percent_array[$examArray[$index]] = $copy_co_po;

            // store the attainment percentage
            $attainmentPercentage[$examArray[$index]] = $attainmentPercentage_CO_PO;

            // store co attainment level
            $co_attainment[$examArray[$index]] = $co_attainment_CO_PO;
            $index++;
        }

        $query = MoreThanSixty::updateOrCreate(
            ['cid' => $cid, 'batch' => $batch],
            [
                'q1' => json_encode($marks_more_than_sixty_percent_array['q1'], true),
                's1' => json_encode($marks_more_than_sixty_percent_array['s1'], true),
                'q2' => json_encode($marks_more_than_sixty_percent_array['q2'], true),
                's2' => json_encode($marks_more_than_sixty_percent_array['s2'], true),
                'assignment' => json_encode($marks_more_than_sixty_percent_array['assignment'], true),
                'end_sem' => json_encode($marks_more_than_sixty_percent_array['end_sem'], true),
            ]
        );

        if (!$query) {
            return back()->with('error', 'Some error occured in uploading/updating student more than 60%');
        }

        $query = AttainmentPercentage::updateOrCreate(
            ['cid' => $cid, 'batch' => $batch],
            [
                'q1' => json_encode($attainmentPercentage['q1'], true),
                's1' => json_encode($attainmentPercentage['s1'], true),
                'q2' => json_encode($attainmentPercentage['q2'], true),
                's2' => json_encode($attainmentPercentage['s2'], true),
                'assignment' => json_encode($attainmentPercentage['assignment'], true),
                'end_sem' => json_encode($attainmentPercentage['end_sem'], true),
            ]
        );

        if (!$query) {
            return back()->with('error', 'Some error occured in uploading/updating attainment percentage');
        }

        $query = CoAttainment::updateOrCreate(
            ['cid' => $cid, 'batch' => $batch],
            [
                'q1' => json_encode($co_attainment['q1'], true),
                's1' => json_encode($co_attainment['s1'], true),
                'q2' => json_encode($co_attainment['q2'], true),
                's2' => json_encode($co_attainment['s2'], true),
                'assignment' => json_encode($co_attainment['assignment'], true),
                'end_sem' => json_encode($co_attainment['end_sem'], true),
            ]
        );

        if (!$query) {
            return back()->with('error', 'Some error occured in uploading/updating CO Attainment');
        }

        if ($query && $this->calculateFinalCOAttainment($cid, $batch)) {
            if ($query->wasRecentlyCreated)
                $this->updated = 1;

            return true;
        } else {
            return false;
        }

    }

    public function fileUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $filePath = $request->file('file')->path();
        $courseId = $request->file('file')->getClientOriginalName();
        $courseId = strtoupper(str_replace(' ', '_', pathinfo($courseId, PATHINFO_FILENAME)));
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $highestRow = $worksheet->getHighestDataRow();
        $highestColumn = $worksheet->getHighestDataColumn();

        $expectedHeaders = ['Reg No', 'Q1', 'S1', 'Q2', 'S2', 'Assignment', 'End Sem'];

        // Loop through each row and store the data
        $excelData = [];
        $errors = [];
        $executedRows = [];
        $rowEnd = false;
        for ($row = 1; $row <= $highestRow; $row++) {
            $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);

            // for co row
            if (is_null($rowData[0][0]) && !is_null($rowData[0][1])) {
                $excelData[] = $rowData[0];
            } else if (!is_null($rowData[0][0])) {
                $excelData[] = $rowData[0];
                if (strtolower($rowData[0][0]) == 'max marks/co')
                    $rowEnd = true;

                // Check if the row data is a duplicate
                if (in_array($rowData[0][0], $executedRows)) {
                    $errors[] = "Duplicate entry Regno - " . $rowData[0][0] . " at row " . $row;
                }
                $executedRows[] = $rowData[0][0];
            } else {
                if ($rowEnd) {
                    break;
                }
            }
        }

        if (!empty($errors)) {
            return back()->with('errorsArray', $errors);
        }

        // Initialize an empty associative array
        $associativeArray = [];

        // Iterate over the original array
        for ($i = 0; $i < count($excelData[0]); $i++) {
            // Check if the value is not empty
            if ($excelData[0][$i]) {
                // Add the key-value pair to the associative array
                $associativeArray[$excelData[0][$i]] = $i; // Set an empty string as the value
            }
        }

        $header = ['Regno', 'Q1', 'S1', 'Q2', 'S2', 'Assignment', 'End Sem'];

        $co_po = [
            'CO1' => null,
            'CO2' => null,
            'CO3' => null,
            'CO4' => null,
            'CO5' => null,
            'Total' => null,
        ];


        $dataArray = [
            'regno' => 0,
            'Q1' => 1,
            'S1' => 2,
            'Q2' => 3,
            'S2' => 4,
            'Assignment' => 5,
            'End Sem' => 5,
        ];


        $flag = True;

        try {
            for ($row = 2; $row < count($excelData); $row++) {
                $regno = $excelData[$row][0];
                for ($x = 1; $x < count($associativeArray); $x++) {
                    $start = $associativeArray[$header[$x]];

                    if ($x == count($associativeArray) - 1) {
                        $end = count($excelData[1]);
                    } else {
                        $end = $associativeArray[$header[$x + 1]];
                    }

                    for ($j = $start; $j < $end; $j++) {
                        if (array_key_exists($excelData[1][$j], $co_po)) {
                            $co_po[$excelData[1][$j]] = $excelData[$row][$j];
                        }
                    }

                    if (array_key_exists($header[$x], $dataArray)) {
                        $dataArray[$header[$x]] = $co_po;
                    }

                    // reset co_po after storing
                    $co_po = [
                        'CO1' => null,
                        'CO2' => null,
                        'CO3' => null,
                        'CO4' => null,
                        'CO5' => null,
                        'Total' => null,
                    ];
                    $dataArray['regno'] = $regno;
                    $dataArray['cid'] = $request->subjectId;
                    $dataArray['batch'] = $request->batch;
                }

                if ($this->saveData($dataArray, $regno, $request->batch, $request->subjectId) == False) {
                    $flag = False;
                    break;
                }
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Excel sheet not in correct format, please try again' . $e->getMessage());
        }

        if ($flag == False)
            return back()->with('error', 'some error occured in uploading file due to flag false');
        else {
            if ($this->calculate($request->subjectId, $request->batch)) {
                if ($this->updated == 1)
                    return back()->with('success', 'Marks Updated Successfully');
                else
                    return back()->with('success', 'Marks Uploaded Successfully');
            } else {
                return back()->with('error', 'some error occured in uploading file in calculate');
            }
        }
    }

    public function export($course, $batch)
    {
        $cid = Courses::join('final_co_attainment', 'final_co_attainment.cid', '=', 'courses.cid')
            ->where('batch', $batch)
            ->where('courses.course', $course)
            ->get();


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


        // Create a new Spreadsheet instance
        $spreadsheet = new Spreadsheet();

        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        // Add table headers
        $headers = ['Course', 'CO', 'Attainment level'];
        foreach (range(1, 12) as $index) {
            $headers[] = 'PO' . $index;
        }
        $sheet->fromArray([$headers]);

        $rowIndex = 2;

        for ($i = 0; $i < count($poArray); $i++) {
            // Process CO and attainment level
            $firstIteration = true;
            foreach (json_decode($poArray[$i], true) as $co => $r) {
                $r = json_decode($r, true);

                $row = [];

                // Add Course ID if it's the first iteration
                if ($firstIteration) {
                    $row[] = $cid[$i]['cid'] . '-' . $cid[$i]['cname']; // Course ID
                    $firstIteration = false; // Set flag to false after adding CID for the first time
                } else {
                    // Add an empty string for CID in subsequent iterations
                    $row[] = ''; // Course ID placeholder
                }

                // Add CO and Attainment level
                $row[] = $co; // CO
                $row[] = $grandTotalArray[$i][$co]; // Attainment level

                // Process each PO
                foreach (range(1, 12) as $index) {
                    $value = isset($r['PO' . $index]) ? $r['PO' . $index] : null;
                    $row[] = $value;
                    if (!is_null($value)) {
                        // Calculate direct attainment for the current PO and CO combination
                        $directAttainment['PO' . $index][] = $grandTotalArray[$i][$co] * $value;
                        $totalPoArray['PO' . $index][] = $value;
                    }
                }
                $sheet->fromArray([$row], null, 'A' . $rowIndex);

                // Increment row index
                $rowIndex++;
            }

            // Add the current row to the spreadsheet
        }

        // Add Direct PO Attainment row after processing all data
        $startingColumnIndex = 'A'; // Start from column A for the header text
        $valueStartingColumnIndex = 'D'; // Start from column D for the values

        // Add Direct PO Attainment header text to column A
        $headerRow = ['Direct PO Attainment'];

        // Add null values for columns B and C
        $headerRow = array_merge($headerRow, [null, null]);

        // Add Direct PO Attainment values starting from column D
        $directAttainmentValues = [];
        foreach (range(1, 12) as $index) {
            $value = isset($directAttainment['PO' . $index])
                ? round(array_sum($directAttainment['PO' . $index]) / array_sum($totalPoArray['PO' . $index]), 2)
                : '';
            $directAttainmentValues[] = $value;
        }

        // Combine the header text and values arrays
        $row = array_merge($headerRow, $directAttainmentValues);

        // Add the combined row to the spreadsheet starting from column A and row $rowIndex
        $sheet->fromArray([$row], null, $startingColumnIndex . $rowIndex);

        // Create a new Excel writer object
        $writer = new Xlsx($spreadsheet);

        // Set the file name
        $filename = $batch . '_' . $course . '_' . 'DIRECT_ATTAINMENT.xlsx';

        // Save the Excel file to storage
        $writer->save(storage_path('app/' . $filename));

        // Return a download response
        return response()->download(storage_path('app/' . $filename))->deleteFileAfterSend(true);
    }
}
