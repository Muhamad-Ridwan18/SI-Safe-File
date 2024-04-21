<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Dokumen\StoreRequest;
use App\Http\Requests\Dokumen\UpdateRequest;
use App\Models\Dokumen;

class DokumenController extends Controller
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['dokumens'] = Dokumen::where('upload_by', \Auth::user()->id)->get();

        return view('dokumen.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dokumen.create');
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
        if($request->hasFile('file_dokumen')){
            $File = 'file_dokumen_' . date('Ymdhis').'.pdf';
            $Path = base_path().'/'.'public'.'/uploads';
            $request->file('file_dokumen')->move($Path, $File);

            $input['file_dokumen'] = $File;
        }
        Dokumen::create($input);

        alert()->success('Data berhasil disimpan', 'Berhasil');
        return redirect('dokumen');
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
        $data['dokumen'] = Dokumen::find($id);
        return view('dokumen.edit', $data);
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
        $model = Dokumen::find($id);
        $input = $request->all();
        if($request->hasFile('file_dokumen')){
            $File = 'file_dokumen_' . date('Ymdhis').'.pdf';
            $Path = base_path().'/'.'public'.'/uploads';
            $request->file('file_dokumen')->move($Path, $File);

            $input['file_dokumen'] = $File;
        }
        $model->update($input);

        alert()->success('Data berhasil diubah', 'Berhasil');
        return redirect('dokumen');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Dokumen::find($id);
        $model->delete();

        alert()->success('Data berhasil dihapus', 'Berhasil');
        return redirect('dokumen');
    }

    public function delete(Request $request)
    {
        $category = Dokumen::find($request->id);
        $category->delete();

        return 'success';
    }
}
