<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dokumen\StoreRequest;
use App\Http\Requests\Dokumen\UpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\Document;
use App\Models\Category;
use App\Models\User;

use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\RSA;
use setasign\Fpdi\Fpdi;
use setasign\Fpdf\Fpdf;
use Smalot\PdfParser\Parser;

use App\Exports\ExportReportDocument;
use Maatwebsite\Excel\Facades\Excel;

class DocumentController extends Controller
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $documents = Document::with('category')->where('user_id', auth()->user()->id)->get();
        // dd($documents);
        return view('documents.index', compact('documents', 'categories'));
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
        $categories = Category::pluck('name', 'id');
        return view('documents.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'pdf' => 'required|file|mimes:pdf',
            'secret_key' => 'nullable|string',
        ]);


        $file = $request->file('pdf');
        $originalFileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('pdfs', $originalFileName, 'public');

        $secretKey = $request->input('secret_key');

        if (!empty($secretKey)) {
            $publicKeyPath = storage_path('app/public/public_key.pem');
            $this->encryptPdf($filePath, $publicKeyPath, $originalFileName, $secretKey , $request->category_id, $request->folder_id);
        } else {
            Document::create([
                'category_id' => $request->category_id,
                'user_id' => auth()->user()->id,
                'original_filename' => $originalFileName,
                'encrypted_filename' => null,
                'encryption_key' => null,
                'iv' => null,
                'secret_key' => null,
                'folder_id' => $request->folder_id
            ]);
        }

        return redirect()->back()->with('success', 'Document created successfully!');
    }


    private function encryptPdf($filePath, $publicKeyPath, $originalFileName, $secretKey, $category_id, $folder_id)
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
            'secret_key' => $hashedSecretKey, 
            'category_id' => $category_id,
            'folder_id' => $folder_id
        ]);

        return $encryptedFilePath;
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

        if (!Hash::check($request->secret_key, $document->secret_key)) {
            return back()->withErrors(['secret_key' => 'Password salah.']);
        }

        // Decrypt the file
        $privateKeyPath = storage_path('app/public/private_key.pem');
        $encryptedFilePath = $document->encrypted_filename;
        $decryptedFilePath = $this->decryptPdf($encryptedFilePath, $privateKeyPath);

        return response()->download(storage_path("app/public/" . $decryptedFilePath), $document->original_filename);
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


    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Check if document is password protected
        if ($document->secret_key) {
            $password = request()->input('password');
            
            if (!$password) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password diperlukan untuk menghapus dokumen yang dilindungi.'
                    ], 422);
                }
                return redirect()->back()->withErrors(['delete' => 'Password diperlukan untuk menghapus dokumen yang dilindungi.']);
            }
            
            if (!Hash::check($password, $document->secret_key)) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password salah.'
                    ], 422);
                }
                return redirect()->back()->withErrors(['delete' => 'Password salah.']);
            }
        }

        $filePathEncrypted = $document->encrypted_filename ?? null;
        $filePathOriginal = $document->original_filename ? 'pdfs/' . $document->original_filename : null;
        $decryptedFilePath = $document->original_filename ? 'decrypted_pdfs/' . $document->original_filename : null;

        $pathsToDelete = array_filter([
            $filePathEncrypted,
            $filePathOriginal,
            $decryptedFilePath
        ]);

        Storage::disk('public')->delete($pathsToDelete);

        $document->delete();

        // Check if request is AJAX
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus!'
            ]);
        }

        return redirect()->back()->with('success', 'File berhasil dihapus.');
    }

    
    public function download($id)
    {
        $document = Document::findOrFail($id);
        $filePath = $document->encrypted_filename ?? 'pdfs/' . $document->original_filename;
        if (Storage::disk('public')->exists($filePath)) {
            return response()->download(storage_path("app/public/$filePath"), $document->original_filename);
        }

        return redirect()->back()->with('error', 'File not found.');
    }

    /**
     * Export documents to Excel
     */
    public function export(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'export_filename' => 'nullable|string|max:255',
        ]);

        $userId = $request->input('user_id');
        $categoryId = $request->input('category_id');
        
        // Default export filename
        $filename = $request->input('export_filename') ?? 'documents-export-' . date('Y-m-d') . '.xlsx';
        
        // Ensure filename has proper extension
        if (!str_ends_with(strtolower($filename), '.xlsx')) {
            $filename .= '.xlsx';
        }

        return Excel::download(
            new ExportReportDocument($userId, $categoryId),
            $filename
        );
    }

    /**
     * Quick export all documents without showing the form
     */
    public function exportAll()
    {
        dd('export all');
        return Excel::download(
            new ExportReportDocument(),
            'all-documents-' . date('Y-m-d') . '.xlsx'
        );
    }

}

