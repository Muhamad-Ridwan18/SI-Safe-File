<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dokumen\StoreRequest;
use App\Http\Requests\Dokumen\UpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\SharedDocument;
use App\Models\Document;
use App\Models\User;

use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\RSA;
use setasign\Fpdi\Fpdi;
use setasign\Fpdf\Fpdf;
use Smalot\PdfParser\Parser;


class DocumentController extends Controller
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::where('user_id', auth()->user()->id)->get();
        return view('documents.index', compact('documents'));
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        return view('documents.show', compact('document'));
    }

    public function test()
    {
        $document = Document::where('user_id', auth()->user()->id)->get();
        
        return view('tests.index', compact('document'));  
    }

    public function testAvalancheEffect($id)
    {
        $document = Document::findOrFail($id);

        // Retrieve original and encrypted file paths
        $originalFilePath = Storage::disk('public')->path('pdfs/' . $document->original_filename);
        $encryptedFilePath = Storage::disk('public')->path($document->encrypted_filename);

        // Retrieve the original and encrypted content
        $originalContent = file_get_contents($originalFilePath);
        $encryptedContent = file_get_contents($encryptedFilePath);

        // Simulate a modified file by changing some content in memory
        $modifiedContent = $this->createModifiedContent($encryptedContent);

        // Calculate avalanche effect
        $bitDifference = $this->calculateBitDifference($originalContent, $modifiedContent);
        $originalFileSize = strlen($originalContent);
        $modifiedFileSize = strlen($modifiedContent);
        $percentageDifference = ($bitDifference / (8 * max($originalFileSize, $modifiedFileSize))) * 100;

        $avalancheResults = [
            'originalFileName' => $document->original_filename,
            'encryptedFileName' => $document->encrypted_filename,
            'originalFileSize' => $originalFileSize,
            'modifiedFileSize' => $modifiedFileSize,
            'bitDifference' => $bitDifference,
            'percentageDifference' => number_format($percentageDifference, 1),
        ];

        if (request()->ajax()) {
            return response()->json(['avalancheResults' => $avalancheResults]);
        }

        return view('tests.index', compact('document', 'avalancheResults'));
    }



    private function createModifiedContent($content)
    {
        // Simulate a small modification in the content
        $modifiedContent = $content;
        $modifiedContent[0] = chr(ord($modifiedContent[0]) ^ 1); // Flip the first bit of the content

        return $modifiedContent;
    }

    private function calculateBitDifference($originalContent, $modifiedContent)
    {
        $originalBits = unpack('C*', $originalContent);
        $modifiedBits = unpack('C*', $modifiedContent);

        $bitDifference = 0;
        $length = min(count($originalBits), count($modifiedBits));

        for ($i = 1; $i <= $length; $i++) {
            $bitDifference += count(array_diff_assoc(str_split(decbin($originalBits[$i])), str_split(decbin($modifiedBits[$i]))));
        }

        return $bitDifference;
    }



    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'secret_key' => 'required|string', 
        ]);

        $file = $request->file('pdf');
        $originalFileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('pdfs', $originalFileName, 'public'); 

        $publicKeyPath = storage_path('app/public/public_key.pem'); 
        $secretKey = $request->input('secret_key'); 

        $encryptedFilePath = $this->encryptPdf($filePath, $publicKeyPath, $originalFileName, $secretKey);

        return redirect()->route('documents.index');
    }

    public function showDecrypt()
    {
        $documents = Document::where('user_id', auth()->user()->id)->get();
        
        return view('decrypt-document.index', compact('documents'));    
    }


    public function decrypt(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'secret_key' => 'required|string',
        ]);

        $document = Document::findOrFail($request->document_id);
        $providedSecretKey = $request->secret_key;

        // Verify the secret key
        if (!Hash::check($providedSecretKey, $document->secret_key)) {
            return response()->json(['message' => 'Invalid secret key'], 403);
        }

        // Proceed with decryption
        $privateKeyPath = storage_path('app/public/private_key.pem');
        $encryptedFilePath = $document->encrypted_filename;
        $decryptedFilePath = $this->decryptPdf($encryptedFilePath, $privateKeyPath);

        $fileUrl = asset('storage/' . $decryptedFilePath);

        return response()->json(['fileUrl' => $fileUrl]);
    }



    private function encryptPdf($filePath, $publicKeyPath, $originalFileName, $secretKey)
    {
        $pdfContent = Storage::disk('public')->get($filePath);

        $aesKey = random_bytes(32);
        $iv = random_bytes(16);

        $aes = new AES('cbc');
        $aes->setKey($aesKey);
        $aes->setIV($iv);
        $encryptedPdfContent = $aes->encrypt($pdfContent);

        $publicKey = file_get_contents($publicKeyPath);
        $rsa = RSA::load($publicKey);
        $encryptedKey = $rsa->encrypt($aesKey);
        $hashedSecretKey = Hash::make($secretKey);
        $dataToStore = base64_encode($encryptedKey . $iv . $encryptedPdfContent);

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->MultiCell(0, 10, $dataToStore);
        $encryptedFilePath = 'encrypted_pdfs/' . pathinfo($originalFileName, PATHINFO_FILENAME) . '.pdf';
        Storage::disk('public')->put($encryptedFilePath, $pdf->Output('S', '')); // Store encrypted PDF

        Document::create([
            'user_id' => auth()->user()->id,
            'original_filename' => $originalFileName,
            'encrypted_filename' => $encryptedFilePath,
            'encryption_key' => base64_encode($encryptedKey),
            'iv' => base64_encode($iv),
            'secret_key' => $hashedSecretKey, // Save the secret key
        ]);

        return $encryptedFilePath;
    }

    private function decryptPdf($encryptedFilePath, $privateKeyPath)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile(storage_path("app/public/$encryptedFilePath"));
        $text = $pdf->getText();

        $encryptedData = base64_decode($text);

        $privateKey = file_get_contents($privateKeyPath);
        $rsa = RSA::load($privateKey);

        $encryptedKey = substr($encryptedData, 0, 256);
        $iv = substr($encryptedData, 256, 16);
        $encryptedPdfContent = substr($encryptedData, 272);

        $aesKey = $rsa->decrypt($encryptedKey);

        $aes = new AES('cbc');
        $aes->setKey($aesKey);
        $aes->setIV($iv);
        $pdfContent = $aes->decrypt($encryptedPdfContent);

        $decryptedFilePath = 'decrypted_pdfs/' . pathinfo($encryptedFilePath, PATHINFO_FILENAME) . '.pdf';
        Storage::disk('public')->put($decryptedFilePath, $pdfContent);

        return $decryptedFilePath;
    }


    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:documents,id'
        ]);

        $document = Document::findOrFail($request->id);

        // Delete the files
        $filePathEncrypted = $document->encrypted_filename;
        $filePathOriginal = 'pdfs/' . $document->original_filename;
        $decryptedFilePath = 'decrypted_pdfs/' . $document->original_filename;        

        Storage::disk('public')->delete([$filePathEncrypted, $filePathOriginal, $decryptedFilePath]);

        // Delete the document record from the database
        $document->delete();

        return response()->json(['message' => 'Document deleted successfully']);
    }

    
    public function download($id)
    {
        $document = Document::findOrFail($id);
        $filePath = $document->encrypted_filename;

        if (Storage::disk('public')->exists($filePath)) {
            return response()->download(storage_path("app/public/$filePath"), $document->original_filename);
        }

        return redirect()->back()->with('error', 'File not found.');
    }


}

