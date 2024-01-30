<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;




class NoteController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Check if a user is authenticated
        $note = auth()->user()->notes()->create($request->all());

        return response()->json(['message' => 'Note created successfully', 'note' => $note], 201);
    }
    

    public function getAllNotes()
    {
        $notes = Note::where('user_id', Auth::id())->get();

        return response()->json(['notes' => $notes], 200);
    }

    public function getNoteById($id)
    {
        // Find the note by id
        $note = Note::where('id', $id)->where('user_id', Auth::id())->first();

        // check if note is found
        if (!$note) {
            return response()->json(['error' => 'Note not found'], 404);
        }

        return response()->json(['note' => $note], 200);
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'string',
            'content' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Find the note by id
        $note = Note::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$note) {
            return response()->json(['error' => 'Note not found'], 404);
        }

        // Update the note
        $note->update($request->only(['title', 'content']));

        return response()->json(['message' => 'Note updated successfully', 'note' => $note], 200);
    }

    public function delete($id)
    {
        // Find the note by id
        $note = Note::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$note) {
            return response()->json(['error' => 'Note not found'], 404);
        }

        // Delete the note
        $note->delete();

        return response()->json(['message' => 'Note deleted successfully'], 200);
    }


    public function attachFile(Request $req, $id)
    {
        // find the note by id
        $note = Note::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$note) {
            return response()->json(['error' => 'Note not found'], 404);
        }

        // Validate the request
        $validator = Validator::make($req->all(), [
            'file' => 'required|mimes:jpg,jpeg,png,webp,pdf,doc|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Process file upload
        $result = $req->file('file')->store('attachments');

        // Create a new attachment record
        $attachment = new Attachment([
            'file_path' => $result,
        ]);

        // assign the attachment with the current note
        $note->attachments()->save($attachment);

        return response()->json(['result' => $result, 'message' => 'File attached successfully to the note'], 200);
    }

    public function deleteFile($id)
    {
        // find the attachment by id
        $file = Attachment::find($id);

        if (!$file) {
            return response()->json(['error' => 'file not found'], 404);
        }

        // delete attachment
        $file->delete();
        return response()->json(['message' => 'file deleted successfully'], 200);
    }


}
