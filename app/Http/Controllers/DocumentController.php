<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Dokumen\StoreRequest;
use App\Http\Requests\Dokumen\UpdateRequest;
use App\Models\SharedDocument;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Zxing\QrReader;
use App\Models\AccessCode;
use App\Notifications\DocumentSharedNotification;
use Illuminate\Support\Facades\Notification;


class DocumentController extends Controller
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['documents'] = Document::with('accessCodes')->where('user_id', \Auth::user()->id)->get();
        $data['users'] = User::all();

        return view('documents.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('documents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $input = $request->all();
        if($request->hasFile('file_path')){
            $File = $request->file('file_path')->getClientOriginalName();
            $Path = base_path().'/'.'public'.'/uploads';
            $request->file('file_path')->move($Path, $File);

            $input['file_path'] = $File;
        }

        $input['user_id'] = \Auth::user()->id;
        Document::create($input);

        alert()->success('Data berhasil disimpan', 'Berhasil');
        return redirect('documents');
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
        $data['documents'] = Document::find($id);
        return view('documents.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $model = Document::find($id);
        $input = $request->all();
        if($request->hasFile('file_path')){
            $File = 'file_path_' . date('Ymdhis').'.pdf';
            $Path = base_path().'/'.'public'.'/uploads';
            $request->file('file_path')->move($Path, $File);

            $input['file_path'] = $File;
        }
        $model->update($input);

        alert()->success('Data berhasil diubah', 'Berhasil');
        return redirect('documents');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Document::find($id);
        $model->delete();

        alert()->success('Data berhasil dihapus', 'Berhasil');
        return redirect('documents');
    }

    public function delete(Request $request)
    {
        $category = Document::find($request->id);
        $category->delete();

        return 'success';
    }

    public function share($id)
    {
        $document = Document::findOrFail($id);
        $users = User::all(); 

        return view('documents.share', compact('document', 'users'));
    }

    public function shareDocument(Request $request)
    {
        $validate = $request->validate([
            'document_id' => 'required',
            'shared_with' => 'required',
        ]);

        $input = $request->all();

        SharedDocument::create($input);

        // Dapatkan informasi dokumen yang dibagikan
        $document = Document::findOrFail($request->document_id);

        // Dapatkan pengguna yang telah dibagikan
        $user = User::findOrFail($request->shared_with);

        // Kirim notifikasi ke pengguna yang dibagikan
        $user->notify(new DocumentSharedNotification($document));

        alert()->success('Data berhasil disimpan dan notifikasi dikirim', 'Berhasil');
        return redirect('documents');
    }

    public function showScanPage(Document $document)
    {
        return view('documents.scan', compact('document'));
    }



    public function accessDocument(Request $request, Document $document)
    {
        $request->validate([
            'access_code' => 'required|string',
        ]);

        $accessCode = AccessCode::where('document_id', $document->id)
                                ->where('access_code', $request->access_code) 
                                ->first();

        if ($accessCode) {
            return response()->download(public_path('uploads/' . $document->file_path));
        } else {
            
            return redirect()->back()->withErrors(['qr_code' => 'Invalid QR code or access code']);
        }
    }

    private function readQRCode($path)
    {
        $qrcode = new QrReader($path);
        return $qrcode->text();
    }

}
