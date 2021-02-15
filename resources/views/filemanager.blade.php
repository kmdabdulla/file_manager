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
      <!--error display div -->
        <div class="col-md-3">
            @if(isset($errors) && count($errors) > 0 )
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul class="p-0 m-0" style="list-style: none;">
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif
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
            <div class="card-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body table-responsive p-0" style="height: 200px;">
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
                        <span class="fa fa-download"></span> Download
                    </button> </form>
                    <div style="display:none"><form action="deleteFiles" method="post">@csrf<input name="fileId" value={{$file->id}}/></div><button class="btn btn-danger .btn-sm">
                        <span class="fa fa-trash"></span> Delete
                    </button> </form>
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
</section>
@endsection
