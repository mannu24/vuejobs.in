<?php

namespace App\Http\Controllers\Admin;

use App\Exports\JobImportTemplate;
use App\Http\Controllers\Controller;
use App\Imports\JobsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class JobImportController extends Controller
{
    public function show()
    {
        return view('admin.jobs.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $import = new JobsImport();
        Excel::import($import, $request->file('file'));

        $message = "Import complete: {$import->imported} jobs imported, {$import->skipped} skipped.";

        if (count($import->errors)) {
            return redirect()->route('admin.jobs.import')
                ->with('success', $message)
                ->with('import_errors', $import->errors);
        }

        return redirect()->route('admin.jobs.import')->with('success', $message);
    }

    public function template()
    {
        return Excel::download(new JobImportTemplate(), 'vuejobs-import-template.xlsx');
    }
}
