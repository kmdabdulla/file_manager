<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\File;
use App\Http\Requests\FileUpload;
use App\Models\FileUploads;
use Illuminate\Support\Str;
class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        return view('filemanager');
    }

    public function listFiles(Request $request) {
        //Log::debug('hi');
        $files = FileUploads::where('user_id',Auth::user()->id)->get();
        //Log::debug(print_r($files,true));
        return view('filemanager')->with('files', $files);
    }

    public function searchFiles() {

    }

    public function uploadFiles(FileUpload $request) {
            //Log::debug($request->only('files'));
            //Log::debug(Auth::user()->id);
            $insertData = array();
            $request->validated();
            $fileInfo = new FileUploads;
            if ($request->hasfile('files')) {
                foreach ($request->file('files') as $file) {
                    $data = array();
                    $name = $file->getClientOriginalName();
                   //Log::debug($file->getRealPath());
                   $encryptedContent = Crypt::encrypt(file_get_contents($file->getRealPath()));
                   $encryptedFileName = Str::random(10).time();
                    Storage::put($encryptedFileName, $encryptedContent);
                    //$encryptedFile = Crypt::encrypt(file_get_contents(storage_path('userfiles/'.$newFileName)));
                    $data['original_name'] = $name;
                    $data['encrypted_file_path'] = $encryptedFileName;
                    $data['user_id'] = Auth::user()->id;
                    $insertData[] = $data;
                    /*$fileInfo->original_name = $name;
                    $fileInfo->encrypted_file_path = $encryptedFileName;
                    $fileInfo->user_id = Auth::user()->id;
                    $fileInfo->save();*/
                }
                $fileInfo->insert($insertData);
                //return view('filemanager')->with('success!','File Uploaded!');
            }
            return redirect('fileManager');
    }

    public function deleteFiles(FileUpload $request) {
            $request->validated();
            $fileInfo = new FileUploads;
            $file = $fileInfo->where([['id',$request->fileId],['user_id',Auth::user()->id]])->first();
            //Log::debug($file->count());
            if($file->count()<1) {
                return redirect()->back()->withErrors('File Not Found');
            }
            if(Storage::exists($file->encrypted_file_path)) {
            Storage::delete($file->encrypted_file_path);
            }
            $file->delete();
            return redirect('fileManager');
    }

    public function downloadFiles(FileUpload $request) {
        $request->validated();
        /*$headers = array(
            'Content-Type: application/pdf',
          );*/
         /*if (!$request->has('fileId')) {
            return redirect()->back()->withErrors('Invalid Action');
        }*/
        $fileInfo = new FileUploads;
        //Log::debug($request->fileId);
        $file = $fileInfo->where([['id',$request->fileId],['user_id',Auth::user()->id]])->first();
        //Log::debug(print_r($file,true));
        //Log::debug($file->count());
        if($file->count()<1) {
            return redirect()->back()->withErrors('File Not Found');
        }
        //Log::debug($file->encrypted_file_path);
        if(Storage::exists($file->encrypted_file_path)) {
        $encryptedContents = Storage::get($file->encrypted_file_path);
        $decryptedContents = Crypt::decrypt($encryptedContents);
        Storage::put($file->original_name, $decryptedContents);
        //return Storage::download($file->original_name);
        return response()
        ->download(storage_path('userfiles/'.$file->original_name))
        ->deleteFileAfterSend(true);
        }
    }

    public function viewFiles() {
        //return response()->download($pathToFile);
    }
}
