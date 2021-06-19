@extends('backend.layouts.master')
@section('title')
Dashboard
@endsection
@section('styles')
<style>


.banner {
  background: #a770ef;
  background: -webkit-linear-gradient(to right, #a770ef, #cf8bf3, #fdb99b);
  background: linear-gradient(to right, #a770ef, #cf8bf3, #fdb99b);

}
.banner img{
    height: 250px;
    object-fit: cover;
}

.modal {
z-index:1;
display:none;
padding-top:10px;
position:fixed;
left:0;
top:0;
width:100%;
height:100%;
overflow:auto;
background-color:rgb(0,0,0);
background-color:rgba(0,0,0,0.8)
}

.modal-content2{
margin: auto;
display: block;
    position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 500px;
}


.modal-hover-opacity {
opacity:1;
filter:alpha(opacity=100);
-webkit-backface-visibility:hidden
}

.modal-hover-opacity:hover {
opacity:0.60;
filter:alpha(opacity=60);
-webkit-backface-visibility:hidden
}


.close {
text-decoration:none;float:right;font-size:24px;font-weight:bold;color:white
}
.container1 {
width:200px;
display:inline-block;
}
</style>
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
                    <li><span>Files</span></li>
                </ul>
            </div>
        </div>

    </div>
</div>
<!-- page title area end -->
<div class="main-content-inner">
    <div class="col-12 mt-5">
        @if (!empty($categoryFiles))
        <div class="card">
            <div class="card-body">
            <h4 class="header-title">{{$categoryFiles->name}}</h4>
            @if ($categoryFiles->children->count() > 0)
                @foreach ($categoryFiles->children as $key => $category)
                    <a href="{{route('admin.category.files',$category->id)}}" class="btn btn-rounded btn-primary mb-3 mr-2">{{$category->name}}</a>
                @endforeach
            @endif
            @if ($categoryFiles->files->count() > 0)
                <hr>
            <div class="row">
                @foreach ($categoryFiles->files as $key => $file)
                <!-- Gallery item -->
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4 ">
                        <div class="bg-white rounded shadow-sm banner">
                            @if (in_array(strtoupper($file->file_type), config('basic.image')))
                                <img src="{{$path.'/'.$file->file_name}}" alt="" onclick="onClick(this)" class="img-fluid card-img-top" style="cursor: pointer">
                            @elseif (in_array(strtoupper($file->file_type), config('basic.audio')))
                                <img src="{{asset('assets/backend/images/audio.png')}}" alt="" class="img-fluid card-img-top">
                            @elseif (in_array(strtoupper($file->file_type), config('basic.document')))
                                <img src="{{asset('assets/backend/images/document.png')}}" alt="" class="img-fluid card-img-top">
                            @elseif (in_array(strtoupper($file->file_type), config('basic.video')))
                                <img src="{{asset('assets/backend/images/video.jpg')}}" alt="" class="img-fluid card-img-top">
                            @else
                                <img src="{{asset('assets/backend/images/files.jpg')}}" alt="" class="img-fluid card-img-top">
                            @endif
                            <div class="p-4">
                                <h5> <a href="#" class="text-dark">{{$file->category?$file->category->name:'Uncategorised'}}</a></h5>
                                <p class="small text-muted mb-0">{{substr_replace($file->file_name, "", 40)}}</p>
                                <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
                                <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">{{strtoupper($file->file_type)}}</span></p>
                                <div class="badge badge-danger px-3 rounded-pill font-weight-normal">Delete</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- End -->
                @endforeach
            </div>
            @else
            <div class="card-body">
                <p>No files here.</p>
            </div>
            @endif
        @endif
    </div>

</div>

<div id="modal01" class="modal" onclick="this.style.display='none'">
    <span class="close">&times;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <div class="modal-content2">
      <img id="img01" style="max-width:100%">
    </div>
  </div>

@endsection
@section('scripts')


<script>

    function onClick(element) {
    document.getElementById("img01").src = element.src;
    document.getElementById("modal01").style.display = "block";
    }

</script>

@endsection
