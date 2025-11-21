<?php

namespace App\Http\Controllers;

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
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('csv_file');
        $fileName = time() . '_' . $file->getClientOriginalName();

        $file->storeAs('csv_uploads', $fileName, 'public');

        CsvUpload::create([
            'file_name' => $fileName,
            'status' => 'pending',
        ]);

        return redirect()->route('csv.index')->with('success', 'CSV file uploaded successfully!');
    }

    public function destroy(CsvUpload $csvUpload)
    {
        $csvUpload->delete();

        return redirect()->route('csv.index')->with('success', 'CSV file deleted successfully!');
    }
}
