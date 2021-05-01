<?php

namespace App\Http\Controllers\Backend;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('backend.pages.upload.from_where',compact('categories'));
    }

    public function storeMedia(Request $request)
    {
        $path = storage_path('temp/uploads/'.md5(Auth::id()));

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function store(Request $request)
    {
        $project = Project::create($request->all());

        foreach ($request->input('document', []) as $file) {
            $project->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('document');
        }

        return redirect()->route('projects.index');
    }

    // public function update(UpdateProjectRequest $request, Project $project)
    // {
    //     $project->update($request->all());

    //     if (count($project->document) > 0) {
    //         foreach ($project->document as $media) {
    //             if (!in_array($media->file_name, $request->input('document', []))) {
    //                 $media->delete();
    //             }
    //         }
    //     }

    //     $media = $project->document->pluck('file_name')->toArray();

    //     foreach ($request->input('document', []) as $file) {
    //         if (count($media) === 0 || !in_array($file, $media)) {
    //             $project->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('document');
    //         }
    //     }

    //     return redirect()->route('admin.projects.index');
    // }

    public function callback(Request $request){
        $command = $request->command;

        //$links = [];
        $file = $request['file'];

        if('handle-google-drive-file' === $command) {
            $fileName      = $file['name'];
            $tmp           = explode('.', $fileName);
            $fileExtension = end($tmp);

            $file_id = $file['id'];
            $file_name = $file['name'];
            $extension = $fileExtension;
            $mime_type = $file['mimeType'];
            $access_token = $request['access_token'];

            if (stripos($mime_type, 'google')) {
                $getUrl = 'https://www.googleapis.com/drive/v2/files/' . $file_id .
                '/export?mimeType=application/pdf';
                $authHeader = 'Authorization: Bearer ' . $access_token;
                $file_name = $file_name . " (converted)";
                $extension = 'pdf';
                $file_mime_type = 'application/pdf';
            }
            else { // otherwise we download it the normal way
                $getUrl = 'https://www.googleapis.com/drive/v2/files/' . $file_id .
                '?alt=media';
                $authHeader = 'Authorization: Bearer ' . $access_token;

            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $getUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array($authHeader));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $data = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            $path = storage_path('temp/uploads/'.md5(Auth::id()));

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file = $data;

            $name = uniqid() . '_' . trim($file_name);

            file_put_contents($path.'/'.$name,$data);
            //Storage::put($path.'/'.$file_name, $data);
            $link = asset('storage/temp/uploads/'.md5(Auth::id()).'/'.$name);

            return response()->json([
                'name'          => $name,
                'original_name' => $file_name,
                'link' => $link,
            ]);

            // Storage::disk('temp')->put('/uploads/'.md5(Auth::id()).'/'.$file_name, $data);
            // array_push($links, $link);
        }

        // $data= [
        //     'links' => $links
        // ];
        // return $data;


    }
}
