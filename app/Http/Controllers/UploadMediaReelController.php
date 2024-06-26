<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadMediaReelController extends Controller
{
    public function store(Request $request)
    {
        $publicPath = public_path('temp/');
        $file = strtolower(auth()->id().uniqid().time().str_random(20));

        $extensions = [
            'video/mp4',
            'video/quicktime',
            'video/3gpp',
            'video/mpeg',
            'video/x-matroska',
            'video/x-ms-wmv',
            'video/vnd.avi',
            'video/avi',
            'video/x-flv',
        ];

        // Initialize FileUploader
        $FileUploader = new FileUploader('reel', array(
            'limit' => 1,
            'fileMaxSize' => floor(config('settings.file_size_allowed') / 1024),
            'extensions' => $extensions,
            'title' => $file,
            'uploadDir' => $publicPath
        ));

        // Upload
        $upload = $FileUploader->upload();

        if ($upload['isSuccess']) {
            foreach($upload['files'] as $item) {
                $this->uploadReel($item['name']);
            }
        }

        return response()->json($upload);
    }

    protected function uploadReel($video)
    {
        $path = config('path.videos');
        $token = str_random(150).uniqid().now()->timestamp;

        // Insert file into the database with 'is_reel' set to true
        Media::create([
            'updates_id' => 0,
            'user_id' => auth()->id(),
            'type' => 'video',
            'image' => '',
            'video' => $video,
            'video_poster' => '',
            'video_embed' => '',
            'music' => '',
            'file' => '',
            'file_name' => '',
            'file_size' => '',
            'img_type' => '',
            'token' => $token,
            'is_reel' => true,
            'status' => 'pending',
            'created_at' => now()
        ]);

        // Move file to storage
        $this->moveFileStorage($video, $path);
    }

    protected function moveFileStorage($file, $path)
    {
        $localFile = public_path('temp/'.$file);
        Storage::putFileAs($path, new File($localFile), $file);
        unlink($localFile);
    }

    public function delete(Request $request)
    {
        $media = Media::where('id', $request->id)->where('user_id', auth()->id())->first();
        if ($media) {
            Storage::delete(config('path.videos').$media->video);
            $media->delete();
            return back()->with('status', 'Reel deleted successfully!');
        }
        return back()->with('error', 'Reel not found!');
    }
}