<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCsvFile;
use App\Models\CsvUpload;
use Illuminate\Http\Request;

class CsvUploadController extends Controller
{
    public function index(Request $request)
    {
        $query = CsvUpload::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $csvUploads = $query->latest()->paginate(15);

        return view('csv.dashboard', compact('csvUploads'));
    }

    public function create()
    {
        return view('csv.upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimetypes:text/plain,text/csv,application/csv,text/comma-separated-values,application/vnd.ms-excel|max:10240',
        ]);

        try {
            $file = $request->file('csv_file');
            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->storeAs('csv_uploads', $fileName, 'public');

            $csvUpload = CsvUpload::create([
                'file_name' => $fileName,
                'status' => 'pending',
            ]);

            ProcessCsvFile::dispatch($csvUpload);

            return redirect()->route('csv.index')->with('success', 'CSV file uploaded successfully! Processing in background...');
        } catch (\Exception $e) {
            return redirect()->route('csv.index')->with('error', 'Error uploading file: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(CsvUpload $csvUpload)
    {
        $csvUpload->delete();

        return redirect()->route('csv.index')->with('success', 'CSV file deleted successfully!');
    }
}
