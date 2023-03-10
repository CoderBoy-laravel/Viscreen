<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pawlox\VideoThumbnail\Facade\VideoThumbnail;
use Str;

class UploadController extends Controller
{
    public function index()
    {
        return redirect()->route('slug', 'video');
    }

    public function slug ($slug){
        $selected = $slug;
        switch ($slug){
            case 'audio':
                $slugType = ['audio','bulkaudio'];
                break;
            default:
                $slugType = ['video','bulkvideo'];
        }
        $data = Upload::whereIn('type', $slugType)->paginate(10);
        return view('Webpage.Admin.Upload.Upload', compact('data', 'selected'));
    }

    public function addFile(Request $request)
    {
        if ($request->link) {
            $request->validate([
                'type' => 'required',
                'link' => 'required',
                'title' => 'required',
                'description' => 'required',
            ]);
            Upload::insert([
                'user_id' => Auth::user()->id,
                'type' => $request->type,
                'title' => $request->title,
                'link' => $request->link,
                'description' => $request->description,
                'status' => Auth::user()->role == 'admin' ? 'approved' : 'pending',
                'created_at' => Carbon::now(),
            ]);
            return response()->json('success');
        } else {
            $request->validate([
                'type' => 'required',
                'file' => 'required|mimes:mp3,mp4,ogv,webm,srt',
                'title' => 'required',
                'description' => 'required',
            ]);
            $name = '';
            $folder = '';
            $thumb = '';
            if ($request->type == 'video') {
                if ($request->hasFile('file')) {
                    $getFile = $request->file('file');
                    $random = Str::random(8);
                    $name = $random . '.' . $getFile->getClientOriginalExtension();
                    $path = public_path() . '/assets/Upload/Video/';
                    $folder = '/assets/Upload/Video/';
                    $getFile->move($path, $name);
                    $thumb_path = '/assets/Upload/thumb/';
                    $thumb = $thumb_path . $random . '.jpg';
                    VideoThumbnail::createThumbnail(
                        $path . $name,
                        public_path() . $thumb_path,
                        $random . '.jpg',
                        10,
                        768,
                        576
                    );
                }
            } else {
                if ($request->hasFile('file')) {
                    $getFile = $request->file('file');
                    $name = Str::random(8) . '.' . $getFile->getClientOriginalExtension();
                    $path = public_path() . '/assets/Upload/Audio/';
                    $folder = '/assets/Upload/Audio/';
                    $getFile->move($path, $name);
                }
            }
            Upload::insert([
                'user_id' => Auth::user()->id,
                'type' => $request->type,
                'title' => $request->title,
                'file' => $folder . $name,
                'thumb' => $thumb,
                'description' => $request->description,
                'status' => Auth::user()->role == 'admin' ? 'approved' : 'pending',
                'created_at' => Carbon::now(),
            ]);
            return response()->json('success');
        }
    }
    public function editFile(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $upload = Upload::where('id', $request->id)->first();
        if ($request->link) {
            $request->validate([
                'type' => 'required',
                'link' => 'required',
                'title' => 'required',
                'description' => 'required',
            ]);
            if ($upload->file) {
                if (file_exists(public_path() . $upload->file)) {
                    unlink(public_path() . $upload->file);
                }
                if (file_exists(public_path() . $upload->thumb)) {
                    unlink(public_path() . $upload->thumb);
                }
            }
            Upload::where('id', $request->id)->update([
                'type' => $request->type,
                'title' => $request->title,
                'link' => $request->link,
                'file' => null,
                'thumb' => null,
                'description' => $request->description,
                'status' => Auth::user()->role == 'admin' ? 'approved' : 'pending',
                'updated_at' => Carbon::now(),
            ]);
            return response()->json('success');
        } else {
            if ($request->hasFile('file')) {
                $request->validate([
                    'type' => 'required',
                    'file' => 'required|mimes:mp3,mp4,ogv,webm,srt',
                    'title' => 'required',
                    'description' => 'required',
                ]);
            } else {
                $request->validate([
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                ]);
            }
            $name = '';
            $folder = '';
            $thumb = '';
            if ($request->hasFile('file')) {
                if (file_exists(public_path() . $upload->file)) {
                    unlink(public_path() . $upload->file);
                }
                if (file_exists(public_path() . $upload->thumb)) {
                    unlink(public_path() . $upload->thumb);
                }
                if ($request->type == 'video') {
                    $getFile = $request->file('file');
                    $random = Str::random(8);
                    $name = $random . '.' . $getFile->getClientOriginalExtension();
                    $path = public_path() . '/assets/Upload/Video/';
                    $folder = '/assets/Upload/Video/';
                    $getFile->move($path, $name);
                    $thumb_path = '/assets/Upload/thumb/';
                    $thumb = $thumb_path . $random . '.jpg';
                    VideoThumbnail::createThumbnail(
                        $path . $name,
                        public_path() . $thumb_path,
                        $random . '.jpg',
                        10,
                        768,
                        576
                    );
                } else {
                    $getFile = $request->file('file');
                    $name = Str::random(8) . '.' . $getFile->getClientOriginalExtension();
                    $path = public_path() . '/assets/Upload/Audio/';
                    $folder = '/assets/Upload/Audio/';
                    $getFile->move($path, $name);
                }
            }
            Upload::where('id', $request->id)->update([
                'type' => $request->type,
                'title' => $request->title,
                'link' => null,
                'file' => $request->hasFile('file') ? $folder . $name : $upload->file,
                'thumb' => $request->hasFile('file') ? $thumb : $upload->thumb,
                'description' => $request->description,
                'status' => Auth::user()->role == 'admin' ? 'approved' : 'pending',
                'updated_at' => Carbon::now(),
            ]);
            return response()->json('success');
        }
    }

    public function deleteUpload($id) {
        $upload = Upload::where('id', $id)->first();
        if ($upload->file) {
            if (file_exists(public_path() . $upload->file)) {
                unlink(public_path() . $upload->file);
            }
            if (strlen($upload->thumb) > 4) {
                if (file_exists(public_path() . $upload->thumb)) {
                    unlink(public_path() . $upload->thumb);
                }
            }
        }
        $upload->delete();
        return back()->with('messege', 'File Deleted Successfully');
    }

    public function addBulkFile(Request $request){
        $request->validate([
            'type' => 'required',
            'file' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        $id = $request->id;

        if($id){
            Upload::where('id', $request->id)->update([
                'user_id' => Auth::user()->id,
                'type' => $request->type,
                'title' => $request->title,
                'file' => $request->file,
                'thumb' => '',
                'description' => $request->description,
                'status' => Auth::user()->role == 'admin' ? 'approved' : 'pending',
                'created_at' => Carbon::now(),
            ]);
        } else{
            Upload::insert([
                'user_id' => Auth::user()->id,
                'type' => $request->type,
                'title' => $request->title,
                'file' => $request->file,
                'thumb' => '',
                'description' => $request->description,
                'status' => Auth::user()->role == 'admin' ? 'approved' : 'pending',
                'created_at' => Carbon::now(),
            ]);
        }
        return response()->json('success');
    }

    public function deleteBulkUpload($id) {
        $upload = Upload::where('id', $id)->first();
        $upload->delete();
        return back()->with('messege', 'File Deleted Successfully');
    }
}
