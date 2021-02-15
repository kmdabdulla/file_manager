<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\FileUpload; // This file contains sanitization and validation logic
use App\Models\FileUploads; //Model for this controller
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //check if user is authenticated to access this class methods.
    }
     /**
     * List all the files uploaded for the specific user.
     *
     * @return FileUploads Collection
     */
    public function listFiles(Request $request) {
        $files = FileUploads::where('user_id',Auth::user()->id)->get();
        return view('filemanager')->with('files', $files);
    }

    /**
     * To upload and encrypt user files. Files are encrypted using AES-256-CBC algorithm via
     * Laravel Crypt Facade.
     *
     */
    public function uploadFiles(FileUpload $request) {
            $insertData = array();
            $request->validated();
            $fileInfo = new FileUploads;
            if ($request->hasfile('files')) {
                foreach ($request->file('files') as $file) {
                    $data = array();
                    $name = $file->getClientOriginalName();
                    $encryptedContent = Crypt::encrypt(file_get_contents($file->getRealPath()));
                    $encryptedFileName = Str::random(10).time().Str::random(5);
                    Storage::put($encryptedFileName, $encryptedContent);
                    $data['original_name'] = $name;
                    $data['encrypted_file_path'] = $encryptedFileName;
                    $data['user_id'] = Auth::user()->id;
                    $data['created_at'] = now();
                    $insertData[] = $data;
                }
                $fileInfo->insert($insertData);
            }
            return redirect('fileManager');
    }
    /**
     * Check for the existence of the file and deletes it. The entry from the table is also removed.
     *
     */
    public function deleteFiles(FileUpload $request) {
            $request->validated();
            $fileInfo = new FileUploads;
            $file = $fileInfo->where([['id',$request->fileId],['user_id',Auth::user()->id]])->first();
            if($file->count()<1) {
                return redirect()->back()->withErrors('File Not Found');
            }
            if(Storage::exists($file->encrypted_file_path)) {
                Storage::delete($file->encrypted_file_path);
            }
            $file->delete();
            return redirect('fileManager');
    }
    /**
     * Check for the existence of the file to decrypt and download it.
     * Laravel Crypt Facade is used to decrypt the files before downloading.
     *
     */
    public function downloadFiles(FileUpload $request) {
        $request->validated();
        $fileInfo = new FileUploads;
        $file = $fileInfo->where([['id',$request->fileId],['user_id',Auth::user()->id]])->first();
        if($file->count()<1) {
            return redirect()->back()->withErrors('File Not Found');
        }
        if(Storage::exists($file->encrypted_file_path)) {
            $encryptedContents = Storage::get($file->encrypted_file_path);
            $decryptedContents = Crypt::decrypt($encryptedContents);
            Storage::put($file->original_name, $decryptedContents);
            return response()
                    ->download(storage_path('userfiles/'.$file->original_name))
                    ->deleteFileAfterSend(true);
        }
    }
}
