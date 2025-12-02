<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Student;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

        // Upload file to Cloudinary
        $file = $request->file('document_file');
        $uploadedFile = Cloudinary::upload($file->getRealPath(), [
            'folder' => 'student-documents/' . $student->id,
            'resource_type' => 'auto',
            'public_id' => time() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
        ]);

        // Get the secure URL from Cloudinary
        $cloudinaryUrl = $uploadedFile->getSecurePath();
        $cloudinaryPublicId = $uploadedFile->getPublicId();

        // Create document record
        $document = Document::create([
            'student_id' => $student->id,
            'document_name' => $file->getClientOriginalName(),
            'document_type' => $request->document_type,
            'file_path' => $cloudinaryUrl, // Store Cloudinary URL
            'cloudinary_public_id' => $cloudinaryPublicId, // Store for deletion later
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

        // Delete the file from Cloudinary if public_id exists
        if ($document->cloudinary_public_id) {
            try {
                Cloudinary::destroy($document->cloudinary_public_id);
            } catch (\Exception $e) {
                // Log error but continue with deletion
                Log::error('Cloudinary deletion failed: ' . $e->getMessage());
            }
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

        // Log download activity
        ActivityLogService::custom(
            "Downloaded document: '{$document->document_name}' (Type: {$document->document_type}) - Student ID: {$student->id}"
        );

        // Redirect to Cloudinary URL for download
        return redirect($document->file_path);
    }
}
