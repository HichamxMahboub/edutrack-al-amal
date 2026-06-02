<?php

namespace App\Http\Controllers;

use App\Exports\GradesExport;
use App\Exports\StatisticsExport;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller
{
    public function index()
    {
        return view('import-export.index', [
            'studentCount' => Student::count(),
            'gradeCount' => Grade::count(),
        ]);
    }

    public function importStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);

        Excel::import(new StudentsImport, $request->file('file'));

        return redirect()->route('import-export.index')->with('success', 'Import des élèves terminé.');
    }

    public function exportStudents()
    {
        return Excel::download(new StudentsExport, 'eleves-edutrack.xlsx');
    }

    public function exportGrades()
    {
        return Excel::download(new GradesExport, 'notes-edutrack.xlsx');
    }

    public function exportStatistics()
    {
        return Excel::download(new StatisticsExport, 'statistiques-edutrack.xlsx');
    }
}
