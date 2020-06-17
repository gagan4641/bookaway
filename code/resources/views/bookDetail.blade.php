@extends('layouts.app')
@section('content')
<div class="">
  <div class="page-title">
    @if(Session::has('bookDetailSuccess'))
      <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('bookDetailSuccess') }}</p>
      <?php Session::forget('bookDetailSuccess'); ?>
    @endif
    <div class="title_left">
      <h3>BOOK DETAILS</h3>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2><small>Details of selected book</small></h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">


          <?php if($bookDetail->book_image==""){ ?>
            <img src="{{ asset('uploads/nopicture.jpg') }}" class="profile-pic" style="width: 150px;padding: 10px 0px;" />
          <?php } else { ?>
            <img src="{{ asset('books/') }}/{{$bookDetail->book_image}}" class="profile-pic" style="width: 150px;padding: 10px 0px;" >
          <?php } ?>

          <div class='row'>
            <div class="col-md-1"><h5>Book Name : </h5></div>
            <div class="col-md-6"><h5>{{$bookDetail->book_name}}</h5></div>
          </div>
          <div class='row'>
            <div class="col-md-1"><h5>Author : </h5></div>
            <div class="col-md-6"><h5>{{$bookDetail->book_author}}</h5></div>
          </div>
          <div class='row'>
            <div class="col-md-1"><h5>Price : </h5></div>
            <div class="col-md-6"><h5>${{$bookDetail->book_price}}</h5></div>
          </div>
          <div class='row'>
            <div class="col-md-1"><h5>Description : </h5></div>
            <div class="col-md-6"><h5>{{$bookDetail->book_description}}</h5></div>
          </div>
          <div class='row'>
            <div class="col-md-1"><h5>Condition : </h5></div>
            <div class="col-md-6"><h5>{{$bookDetail->book_condition}}</h5></div>
          </div>
          <div class='row'>
            <div class="col-md-1"><h5>Status : </h5></div>
            <div class="col-md-6"><h5>{{$bookDetail->book_status}}</h5></div>
          </div>
          <div class='row'>
            <div class="col-md-1"><h5>School : </h5></div>
            <div class="col-md-6"><h5>{{$bookDetail->schoolName}}</h5></div>
          </div>
          <div class='row'>
            <div class="col-md-1"><h5>Subject : </h5></div>
            <div class="col-md-6"><h5>{{$bookDetail->subjectName}}</h5></div>
          </div>
          <div class='row'>
            <div class="col-md-1"><h5>Added By : </h5></div>
            <div class="col-md-6"><h5>{{$bookDetail->userFname}} {{$bookDetail->userLname}}</h5></div>
          </div>

          <?php if((@$repUsers)&&(count($repUsers)>0)){ ?>
          <br><br>
          <h3>REPORTS</h3>
          <table id="datatable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>School</th>
                <th>Report Type</th>
                <th>Comment</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($repUsers as $datau)
              <tr>
                <td>{{ $datau->fname }} {{ $datau->lname }}</td>
                <td>{{ $datau->email }}</td>
                <td>{{ $datau->name_school }}</td>
                <?php
                  $reasons = DB::table('report_reason')->where('id_reason', $datau->id_reason)->first();
                ?>
                <td>{{ $reasons->title_reason }}</td>
                <td>{{ $datau->comment }}</td>
                <td>{{ $datau->date }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $(".statusClass").click(function(){
      var answer=confirm('Do you want to change status of this school?');
      if(answer){
        return true;
      }else{
        return false;
      }
    });
    $(".deleteClass").click(function(){
      var answer=confirm('Do you want to delete this school?');
      if(answer){
        return true;
      }else{
        return false;
      }
    });
  });
</script>
@endsection











