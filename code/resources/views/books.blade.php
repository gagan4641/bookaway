@extends('layouts.app')
@section('content')
<div class="">
  <div class="page-title">
    @if(Session::has('updateBookSuccess'))
      <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('updateBookSuccess') }}</p>
      <?php Session::forget('updateBookSuccess'); ?>
    @endif
    <div class="title_left">
      <h3>BOOKS LIST</h3>
    </div>
    <div class="title_right">
      <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-center">
          <select name="country" class="form-control col-md-7 col-xs-12" id="selectSchool">
            <option value="">Select School</option>
            @foreach($schools as $schoolData)
              <option value="{{ $schoolData->id_school }}" @if($sid==$schoolData->id_school) selected @endif >{{ $schoolData->name_school }}</option>
            @endforeach
          </select>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2><small>List of added Books</small></h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <table id="datatable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Book Name</th>
                <th>Subject</th>
                <th>School</th>
                <th>Price</th>
                <th>No. Of Reports</th>
                <th>Status</th>
                <th>Reports/Flag</th>
              </tr>
            </thead>
            <tbody>
              @foreach($books as $data)
              <?php
              $bookspam = DB::table('book_spam')
                        ->where('id_book', $data->id_book)
                        ->count();
              ?>
              <tr>
                <td>{{ $data->book_name }}</td>
                <td>{{ $data->schoolName }}</td>
                <td>{{ $data->subjectName }}</td>
                <td>${{ $data->book_price }}</td>
                <td>{{ $bookspam }}</td>
                <td> <?php if($data->book_status==1){ echo "Enabled"; } else{ echo "Disabled"; } ?> </td>
                <td>
                  <?php if($data->book_status==1){ ?>
                    <a href="{{url('/').'/blockBook'}}/<?php echo $data->id_book; ?>" class="btn btn-danger blockClass">Block</a>
                  <?php } else{ ?>
                    <a href="{{url('/').'/blockBook'}}/<?php echo $data->id_book; ?>" class="btn btn-primary ublockClass">Unblock</a>
                  <?php } ?>

                  <a href="{{url('/').'/viewBook'}}/<?php echo $data->id_book; ?>" class="btn btn-primary">View</a>


                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $(".statusClass").click(function(){
      var answer=confirm('Do you want to change status of this book?');
      if(answer){
        return true;
      }else{
        return false;
      }
    });

    $(".deleteClass").click(function(){
      var answer=confirm('Do you want to delete this book?');
      if(answer){
        return true;
      }else{
        return false;
      }
    });

    $(".blockClass").click(function(){
      var answer=confirm('Do you want to block this book?');
      if(answer){
        return true;
      }else{
        return false;
      }
    });

    $(".ublockClass").click(function(){
      var answer=confirm('Do you want to unblock this book?');
      if(answer){
        return true;
      }else{
        return false;
      }
    });

    $("#selectSchool").change(function(){
      var school=$("#selectSchool").val();
      if(school==""){
        url = '<?php echo url("/"); ?>/books';
        window.location.replace(url);
      }else{
        url = '<?php echo url("/"); ?>/books/'+school;
        window.location.replace(url); 
      }
    });
  });
</script>
@endsection























