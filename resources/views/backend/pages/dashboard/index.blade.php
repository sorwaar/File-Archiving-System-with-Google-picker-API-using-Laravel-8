@extends('backend.layouts.master')
@section('title')
Dashboard
@endsection
@section('admin-content')
<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-12">
            <div class="breadcrumbs-area clearfix pull-right">
                <h4 class="page-title pull-left">Dashboard</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="index.html">Home</a></li>
                    <li><span>Dashboard</span></li>
                </ul>
            </div>
        </div>

    </div>
</div>
<!-- page title area end -->
<div class="main-content-inner">
    <div class="col-12 mt-5">
        <div class="card-body">
            <a class="btn btn-rounded btn-primary mb-3" href="{{route('admin.media')}}" role="button">Upload Files</a>

        </div>
    </div>

</div>
@endsection
@section('scripts')

<script src="{{asset('assets/backend/js/filepicker.js')}}"></script>
<script>
// A simple callback implementation.
function pickerCallback(data) {
    console.log(data);
    var url = 'nothing';
    var name = 'nothing';
    if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
        var file = data[google.picker.Response.DOCUMENTS][0];
            url = file[google.picker.Document.URL];
            name = file.name;
            console.log(file);
        var data = {
                file_id : file.id,
                file_name : file.name,
                extension: file.fileExtension,
                mime_type : file.mimeType,
                access_token : gapi.auth.getToken().access_token,
                command : 'handle-google-drive-file',
                _token: "{{ csrf_token() }}"
            };
        console.log(data.access_token);
        document.getElementById('result').innerHTML = "Downloading...";
        $.ajax({
            url: "{{ route('drive.callback') }}",
            type:"POST",
            data: data,
            success:function(resp){
                console.log(resp);
                if(resp) {
                    var link = resp.link;
                    document.getElementById('result').innerHTML = "Download completed";
                    var message = '<img src="'+link+'" />';
                    document.getElementById('result').innerHTML = message;
                }
            },
        });
    }

}

</script>
<script type="text/javascript" src="https://apis.google.com/js/api.js"></script>
@endsection
