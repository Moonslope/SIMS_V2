<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Student;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'description' => 'nullable|string|max:500'
        ]);

        // Get the student record for the logged-in user
        $student = Student::where('user_id', Auth::id())->first();

        if (!$student) {
            return back()->with('error', 'Student record not found.');
        }

        // Store the file
        $file = $request->file('document_file');
        $filename = time() .  '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents/students/' . $student->id, $filename, 'public');

        // Create document record
        $document = Document::create([
            'student_id' => $student->id,
            'document_name' => $file->getClientOriginalName(),
            'document_type' => $request->document_type,
            'file_path' => $path,
        ]);

        // Log activity
        ActivityLogService::created(
            $document,
            "Uploaded document: '{$document->document_name}' (Type: {$document->document_type}) for Student ID: {$student->id}"
        );

        return back()->with('success', 'Document uploaded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource. 
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Check if the document belongs to the logged-in student
        $student = Student::where('user_id', Auth::id())->first();

        if ($document->student_id !== $student->id) {
            abort(403, 'Unauthorized access');
        }

        $documentName = $document->document_name;
        $documentType = $document->document_type;

        // Delete the file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Log activity before deletion
        ActivityLogService::deleted(
            $document,
            "Deleted document: '{$documentName}' (Type: {$documentType}) for Student ID: {$student->id}"
        );

        // Delete the database record
        $document->delete();

        return back()->with('success', 'Document deleted successfully!');
    }

    /**
     * Download document
     */
    public function download($id)
    {
        $document = Document::findOrFail($id);

        // Check if the document belongs to the logged-in student
        $student = Student::where('user_id', Auth::id())->first();

        if ($document->student_id !== $student->id) {
            abort(403, 'Unauthorized access');
        }

        if (! Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File not found.');
        }

        // Log download activity
        ActivityLogService::custom(
            "Downloaded document: '{$document->document_name}' (Type: {$document->document_type}) - Student ID: {$student->id}"
        );

        return response()->download(
            storage_path('app/public/' . $document->file_path),
            $document->document_name
        );
    }
}
