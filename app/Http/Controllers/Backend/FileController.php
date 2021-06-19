<?php

namespace App\Http\Controllers\Backend;
use App\Models\File;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;


class FileController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->where('created_by',Auth::id())->get();
        return view('backend.pages.upload.from_where',compact('categories'));
    }

    public function storeMedia(Request $request)
    {

        // $path = Storage::disk('uploads').'temp/'.md5(Auth::id());
        // dd($path);
        $path = storage_path('app/public/uploads/temp/'.md5(Auth::id()));

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

        $data = $request->all();
        //dd($data);
        $category_id = empty($request->category)?0:(empty($request->sub_category)?$request->category:$request->sub_category);
        $finalArray = array();
        $from_path = '/temp/'.md5(Auth::id());
        $to_path = '/main/'.md5(Auth::id());
        $storage_path = public_path('app/public/uploads/main/'.md5(Auth::id()));
        if (!file_exists($storage_path)) {
            mkdir($storage_path, 0777, true);
        }
        foreach($data['document'] as $key=>$document){
            $file_type = pathinfo($document, PATHINFO_EXTENSION);

        array_push($finalArray, array(
                        'user_id'=> Auth::id(),
                        'category_id'=> $category_id,
                        'file_type'=>$file_type,
                        'file_name'=>$document,
                        'status'=>1

                    ));

        Storage::disk('uploads')->move($from_path.'/'.$document, $to_path.'/'.$document);
        };

        File::insert($finalArray);


        // foreach ($request->input('document', []) as $file) {
        //     $project->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('document');
        // }

        return redirect()->route('admin.media');
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

            $path = storage_path('app/public/uploads/temp/'.md5(Auth::id()));

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file = $data;

            $name = uniqid() . '_' . trim($file_name);

            file_put_contents($path.'/'.$name,$data);
            //Storage::put($path.'/'.$file_name, $data);
            $link = asset('storage/uploads/temp/'.md5(Auth::id()).'/'.$name);

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */

    public function viewCategory($id)
    {
        $categoryFiles = Category::with('files','children')->where('id',$id)->where('created_by',Auth::id())->first();
        $path = asset('storage/uploads/main/'.md5(Auth::id()));
        //dd($categoryFiles->files->count());
        return view('backend.pages.upload.files',compact('categoryFiles','path'));
    }

    public function delete($id){
        $file = File::where('id',$id)->where('user_id',Auth::id())->first();
        //$image_path = asset('storage/uploads/main/'.md5(Auth::id())).'/'.$file->file_name;
        Storage::delete('app/public/uploads/app/main/'.md5(Auth::id()).'/'.$file->file_name);
            // if (file_exists($image_path)) {
            //     dd('here');

            //     @unlink($image_path);

            // }
            return redirect()->route('admin.dashboard');
    }
}
