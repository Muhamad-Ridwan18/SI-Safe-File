<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\AccessCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class AccessCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($document_id)
    {
        $document = Document::findOrFail($document_id);

        return view('access_codes.create', compact('document'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate input data
        $validate = $request->validate([
            'access_code' => 'required|unique:access_codes',
        ]);

        $documentId = $request->document_id;

        // Check if document_id already exists in access_codes table
        $existingAccessCode = AccessCode::where('document_id', $documentId)->first();

        if ($existingAccessCode) {
            // Document ID already exists, handle accordingly
            return redirect()->route('documents.index')->withInput()->withErrors(['document_id' => 'Document ID already has an access code.']);
        }

        $code = $request->access_code;

        $encode = base64_encode($code);

        $decode = base64_decode($encode);

        // Hash
        $hashedCode = hash('sha512', $request->access_code);

        $encryptedAccessCode = Crypt::encryptString($hashedCode, false, 'AES-256-CBC');

        $decryptedAccesCode = substr(Crypt::decryptString($encryptedAccessCode), 0, 32);


        // Generate QR code
        $qrCode = QrCode::format('png')->size(400)->generate($decryptedAccesCode);

        // Save QR code image to storage
        $qrCodePath = 'qr_codes/' . uniqid() . '.png';
        Storage::disk('public')->put($qrCodePath, $qrCode);
        

        //Sha Proccess
       
        

        try {
            // Attempt to create access code
            AccessCode::create([
                'document_id' => $documentId,
                'access_code' => $decryptedAccesCode, // Save hashed access code
                'qr_code_path' => $qrCodePath, // Save QR code path
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry exception
            return redirect()->back()->withInput()->withErrors(['access_code' => 'Access code must be unique.']);
        }

        return redirect()->route('documents.index')->with('success', 'Access code created and QR code generated.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function downloadQrCode(Request $request, Document $document)
    {
        
        $document_id = $document->id;
        
        $image = AccessCode::where('document_id', $document_id)->first();

        $qrCodePath = $image->qr_code_path;
        // dd($qrCodePath);

        if (!$image) {
            // Handle scenario where access code does not exist
            abort(404);
        }

        return response()->download(public_path('storage/' . $qrCodePath));
    }
}
