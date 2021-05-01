@extends('backend.layouts.master')
@section('title')
Dashboard
@endsection
@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/basic.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .dropzone {
        border:2px dashed #999999;
        border-radius: 10px;
    }
    .dropzone .dz-default.dz-message {
        height: 171px;
        background-size: 132px 132px;
        margin-top: -101.5px;
        background-position-x:center;

    }
    .dropzone .dz-default.dz-message span {
        display: block;
        margin-top: 145px;
        font-size: 20px;
        text-align: center;
    }
</style>
<style>
    .drivezone {
        border: 2px dashed #999999;
        border-radius: 10px;
    }
    .drivezone {
        position: relative;

    }
    .drivezone, .drivezone * {
        box-sizing: border-box;
    }

    .drivezone .dz-default.dz-message {
    height: 171px;
    background-size: 132px 132px;
    margin-top: -101.5px;
    background-position-x: center;
}
.drivezone .dz-default.dz-message span {
    display: block;
    margin-top: 145px;
    font-size: 20px;
    text-align: center;
}

#result img {
    width: 120px;
    height: 120px;
    padding: 4px;
}
</style>
@endsection
@section('admin-content')
<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-12">
            <div class="breadcrumbs-area clearfix pull-right">
                <h4 class="page-title pull-left">FAS</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="index.html">Home</a></li>
                    <li><span>Upload</span></li>
                </ul>
            </div>
        </div>

    </div>
</div>
<!-- page title area end -->
<div class="main-content-inner">

    <div class="col-lg-12 mt-5">
        <div class="card">
            <div class="card-body">

                <div class="d-md-flex">
                    <div class="nav flex-column nav-pills mr-4 mb-3 mb-sm-0 col-lg-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Upload From Computer</a>
                        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fab fa-google-drive"> </i>Pick From Google Drive</a>
                    </div>
                    <div class="nav flex-column nav-pills mr-4 mb-3 mb-sm-0 col-lg-9">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="inputEmail4">Category</label>
                              <select class="form-control form-control-lg" id="category" name="category">
                                <option>Select Category</option>
                                @foreach ($categories as $key=> $category )
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="form-group col-md-6" id="sub-cat-aria" hidden>
                              <label for="inputPassword4">Sub Category</label>
                              <select class="form-control form-control-lg" id="sub-category" name="sub-category">

                              </select>
                            </div>
                          </div>
                        <div class="tab-content " id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <div class="form-group">
                                    <label for="document">Documents</label>
                                    <div class="needsclick dropzone" id="document-dropzone">

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <div class="form-group"  id="form2">
                                    <label for="document">Documents</label>
                                    <div class="drivezone"  onclick="onApiLoad()">

                                        <div class="dz-default dz-message"><span>Pick google drive files</span></div>
                                        <div id="result" class="result"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <input class="btn btn-danger" type="submit">
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
  var uploadedDocumentMap = {}
  Dropzone.options.documentDropzone = {
    url: '{{ route('projects.storeMedia') }}',
    maxFilesize: 100, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
      uploadedDocumentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedDocumentMap[file.name]
      }
      $('form').find('input[name="document[]"][value="' + name + '"]').remove()
    },
    init: function () {
      @if(isset($project) && $project->document)
        var files =
          {!! json_encode($project->document) !!}
        for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
        }
      @endif
    }
  }
</script>


{{-- google drive scripts --}}

<script src="{{asset('assets/backend/js/filepicker.js')}}"></script>
<script>
    // A simple callback implementation.
    function pickerCallback(data) {
        console.log(data);
        var url = 'nothing';
        var name = 'nothing';
        if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
            var files = data[google.picker.Response.DOCUMENTS];
            url = files[google.picker.Document.URL];
            name = files.name;

            var uploadedDocumentMap = {}
            $.each( files, function( index, value ){
                var data = {
                        file: value,
                        command : 'handle-google-drive-file',
                        _token: "{{ csrf_token() }}",
                        access_token : gapi.auth.getToken().access_token
                    };
                    //console.log(data.access_token);
                    // $.each( file, function( index, value ){

                        $('#result').append("<div><span>Downloading...<span></div>");
                    // })

                $.ajax({
                    url: "{{ route('drive.callback') }}",
                    type:"POST",
                    data: data,
                    success:function(resp){
                        console.log(resp);
                        if(resp) {
                        //    var links =  resp.links;
                        //    $('#result').html('');
                        //     $.each( links, function( index, value ){
                        //     var link = value;
                        //     console.log(link);
                            var message = '<img src="'+resp.link+'" />';
                        $('#result').append(message);
                        $('form').append('<input type="hidden" name="document[]" value="' + resp.name + '">')
                        uploadedDocumentMap[file.name] = resp.name

                        };
                    },
                });
            });
        }
    }
</script>
<script type="text/javascript" src="https://apis.google.com/js/api.js"></script>
<script>
$(document).ready(function(){

$('#category').on("change",function () {
    var categoryId = $(this).find('option:selected').val();
    $.ajax({
        url: "{{ route('admin.category.sub') }}",
        type: "POST",
        data: {
            categoryId : categoryId,
            _token: "{{ csrf_token() }}",
        },
        success: function (response) {
            console.log(response);
            $('#sub-cat-aria').prop('hidden', false);
            if(response.hasSub==true){
                $("#sub-category").html(response.html);
            }else{
            $('#sub-cat-aria').prop('hidden', true);
            $("#sub-category").html('');
            }
        },
    });
});

});
</script>

@stop
