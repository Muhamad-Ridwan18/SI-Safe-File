<div class="card-body">

  <div class="form-group">
      <label>Nama Dokumen</label>
      {{ Form::text('title',null,['class'=>'form-control'])}}
      @if ($errors->has('title')) <span class="help-block" style="color:red">{{ $errors->first('title') }}</span> @endif
  </div>
  <div class="form-group mt-1">
    <label>File Dokumen</label>
    {{ Form::file('file_path',['class'=>'form-control', 'accept' => 'application/pdf'])}}
    @if ($errors->has('file_path')) <span class="help-block" style="color:red">{{ $errors->first('file_path') }}</span> @endif

    @if(!empty($dokumen))
    <a href="{{ asset('uploads/'.$dokumen->file_dokumen) }}" download target="_blank"><small class="text-success">Download dokumen <i data-feather="download"></i></small></a>
    @endif
   </div>

</div>

<div class="card-footer">
  <div class="form-group">
      <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i> Simpan</button>
          
      <a href="{{ route('documents.index') }}" class="btn btn-danger btn-sm"><i class="fas fa-backward"></i> Kembali</a>
  </div>
</div>