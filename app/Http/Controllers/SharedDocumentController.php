<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SharedDocument;
use App\Models\User;
use App\Models\Document;
use App\Models\AccessRequest;
use App\Notifications\DocumentSharedNotification;
use App\Notifications\AccessRequestNotification;
use App\Notifications\AccessRequestApproved;
use App\Notifications\AccessRequestDenied;


class SharedDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $document = Document::all();
        $sharedDocuments = SharedDocument::where('shared_with', auth()->user()->id)->get();

        return view('shared_documents.index', compact('sharedDocuments', 'document'));
    }


    public function requestQrCode(Request $request, Document $document)
    {
        $accessRequest = AccessRequest::create([
            'document_id' => $document->id,
            'requester_id' => auth()->id(),
            'approver_id' => $document->user_id,
            'status' => 'pending',
        ]);

        $approver = User::findOrFail($document->user_id);

        $approver->notify(new AccessRequestNotification($accessRequest));

        return redirect()->back()->with('success', 'QR code request sent successfully.');
    }

    public function approveQrCode(Request $request, AccessRequest $accessRequest)
    {
        $accessRequest->update(['status' => 'approved']);
    
        $accessRequest->requester->notify(new AccessRequestApproved($accessRequest));
    
        return redirect()->back()->with('success', 'Access request approved.');
    }
    
    public function denyQrCode(Request $request, AccessRequest $accessRequest)
    {
        $accessRequest->update(['status' => 'denied']);
    
        $accessRequest->requester->notify(new AccessRequestDenied($accessRequest));
    
        return redirect()->back()->with('success', 'Access request denied.');
    }
    
}
