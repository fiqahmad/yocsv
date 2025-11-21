<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessCsvFile;
use App\Models\CsvData;
use App\Models\CsvUpload;
use Illuminate\Http\Request;

class CsvAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = CsvUpload::query();

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $csvUploads = $query->latest()->paginate(20);

        $stats = [
            'total' => CsvUpload::count(),
            'pending' => CsvUpload::where('status', 'pending')->count(),
            'processing' => CsvUpload::where('status', 'processing')->count(),
            'completed' => CsvUpload::where('status', 'completed')->count(),
            'failed' => CsvUpload::where('status', 'failed')->count(),
            'total_records' => CsvData::count(),
        ];

        return view('admin.csv.index', compact('csvUploads', 'stats'));
    }

    public function show(CsvUpload $csvUpload)
    {
        $errorMessages = $csvUpload->error_messages ? json_decode($csvUpload->error_messages, true) : [];

        return view('admin.csv.show', compact('csvUpload', 'errorMessages'));
    }

    public function reprocess(CsvUpload $csvUpload)
    {
        if (!in_array($csvUpload->status, ['failed', 'completed_with_errors'])) {
            return redirect()->back()->with('error', 'Only failed or partially completed files can be reprocessed.');
        }

        $csvUpload->update(['status' => 'pending']);

        ProcessCsvFile::dispatch($csvUpload);

        return redirect()->back()->with('success', 'File has been queued for reprocessing.');
    }

    public function data(Request $request)
    {
        $query = CsvData::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('unique_key', 'like', "%{$search}%")
                  ->orWhere('product_title', 'like', "%{$search}%")
                  ->orWhere('style', 'like', "%{$search}%");
            });
        }

        $csvData = $query->latest()->paginate(50);

        return view('admin.csv.data', compact('csvData'));
    }

    public function destroy(CsvUpload $csvUpload)
    {
        $csvUpload->delete();

        return redirect()->route('admin.csv.index')->with('success', 'CSV upload record deleted successfully.');
    }
}
