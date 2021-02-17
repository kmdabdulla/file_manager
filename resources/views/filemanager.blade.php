@extends('layouts.navbar')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>File Explorer</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
<!-- error/success message div -->
@if(session()->has('response') || (isset($errors) && count($errors) > 0))
    <div class="alert alert-{{session('response.status') ?? 'danger'}} alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ session('response.message') ?? $errors->first() }}
    </div>
@endif
<div class="col-md-3">
     <!--upload file form -->
                <form action="uploadFiles" method="post" enctype="multipart/form-data">
                    @csrf
                    <label>File Uploads</label>
                    <div>
                        <input id="file" name="files[]" type="file" data-show-upload="true" data-show-caption="false" multiple>
                    </div>
                    <br>
                    <div>
                        <button type="submit" class="btn btn-primary .btn-md">Upload Files</button>
                    </div>
            </form>
        </div>
<br>
 <!--display uploaded files-->
@if (@isset($files) && $files->isNotEmpty())
<div class="container-fluid">
   <div class="row">
   <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Uploaded Files</h3>
          </div>
          <div class="card-body table-responsive p-0" style="height: 500px;">
            <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Uploaded Date</th>
                        <th width="100px">Action</th>
                    </tr>
                </thead>
              <tbody>
                @foreach($files as $file)
                    <tr>
                        <td>{{$file->original_name}}</td>
                        <td>{{$file->created_at}}</td>
                        <td> <div style="display:none"><form action="downloadFiles" method="post">@csrf<input name="fileId" value={{$file->id}}/></div><button class="btn btn-info .btn-sm">
                        <span class="fa fa-download"></span>
                    </button> </form>
                    <button id="deleteButton" class="btn btn-danger .btn-sm" data-info="{{$file->id}}, {{$file->original_name}}" data-toggle="modal" data-target="#deleteModal">
                        <span class="fa fa-trash"></span>
                    </button>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
@endif

<!-- delete model -->
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Delete Confirmation</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="deleteFiles" method="post">@csrf
                    <input id="deleteFileId" type="hidden" name="fileId" value=""/>
              <p>Are you sure about deleting <span id="deleteFileName"></span>?</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Delete</button>
            </div>
        </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
</section>
@endsection
