@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="https://www.jqueryscript.net/demo/Clean-jQuery-Date-Time-Picker-Plugin-datetimepicker/jquery.datetimepicker.css"/>

<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">

<script src="https://www.jqueryscript.net/demo/Clean-jQuery-Date-Time-Picker-Plugin-datetimepicker/jquery.js"></script>

<script src="https://www.jqueryscript.net/demo/Clean-jQuery-Date-Time-Picker-Plugin-datetimepicker/jquery.datetimepicker.js"></script>

<div class="">
  <div class="page-title">
    @if(Session::has('orderSuccess'))
      <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('orderSuccess') }}</p>
      <?php Session::forget('orderSuccess'); ?>
    @endif
    <div class="title">
      <h3>PAYMENTS LIST</h3>
    </div>


  <form  method="GET" action="{{url('payments') }}">
  {{ csrf_field() }}
      <div class="col-md-3 col-sm-3 col-xs-3 form-group pull-center">
          <select name="selectSchool" class="form-control col-md-7 col-xs-12" id="selectSchool">
            <option value="">Select School</option>
            @if($schoolsList)
            @foreach($schoolsList as $sdata)
              <option value="{{$sdata->id_school}}" @if($selectedSchool==$sdata->id_school) selected @endif >{{$sdata->name_school}}</option>
            @endforeach
            @endif
          </select>
      </div>
      <div class="col-md-3 col-sm-3 col-xs-3 form-group pull-center">
          <select name="selectSubject" class="form-control col-md-7 col-xs-12" id="selectSubject">
            <option value="">Select Subject</option>
            @if($subjectsList)
            @foreach($subjectsList as $sbdata)
              <option value="{{$sbdata->id_subject}}" @if($selectedSubject==$sbdata->id_subject) selected @endif >{{$sbdata->title_subject}}</option>
            @endforeach
            @endif
          </select>
      </div>
      <div class="col-md-3 col-sm-3 col-xs-3 form-group pull-center">
          <select name="selectUser" class="form-control col-md-7 col-xs-12" id="selectUser">
            <option value="">Select User</option>
            @if($usersList)
            @foreach($usersList as $userdata)
              <option value="{{$userdata->id}}" @if($selectedUser==$userdata->id) selected @endif >{{$userdata->fname}} {{$userdata->lname}}</option>
            @endforeach
            @endif
          </select>
      </div>
      <div class="col-md-2 col-sm-2 col-xs-2 form-group pull-center">
          <input name="selectDate" value="{{$selectedDate}}" type="text" class="form-control col-md-7 col-xs-12" id="datetimepicker2">
      </div>


      <div class="col-md-1 col-sm-1 col-xs-1 form-group pull-right top_search">
        <div class="input-group">
          <span class="input-group-btn">
            <button type="submit" class="btn btn-primary"><span style="color:#fff;">Submit </span></button>
          </span>
        </div>
      </div>
</form>

    </div>


    <!-- <div class="title_right">
      <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
        <div class="input-group">
          <span class="input-group-btn">
            <a href="{{url('/').'/sendSpeakerLoginDetails'}}" class="btn btn-primary"><span style="color:#fff;">Send E-Mail</span></a>
          </span>
        </div>
      </div>
    </div> -->
  </div>
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2><small>List of Payments</small></h2>
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
                <th>Payment Id</th>
                <th>Payer Id</th>
                <th>Sale Id</th>
                <th>Merchant Id</th>
                <th>Payment State</th>
                <th>Sale Total</th>
                <th>Actual Amount Received</th>
                <th>Transaction Fees</th>
                <th>School Name</th>
                <th>Book Name</th>
                <th>User Name</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($paymentsList as $data)
              <tr>
                <td>{{ $data->parent_id }}</td>
                <td>{{ $data->payer_id }}</td>
                <td>{{ $data->sale_id }}</td>
                <td>{{ $data->merchant_id }}</td>
                <td>{{ $data->parent_state }}</td>
                <td>{{ $data->sale_total }} USD</td>
                <td>{{ $data->receaved_amount }} USD</td>
                <td>{{ $data->trans_fee }} USD</td>
                <td>{{ $data->schoolName }}</td>
                <td>{{ $data->bookName }}</td>
                <td>{{ $data->userFname }} {{ $data->userLname }}</td>
                <td></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  {{ csrf_field() }}
</div>

<script type="text/javascript">

  $('#datetimepicker2').datetimepicker({
      timepicker:false,
      format:'Y-m-d',
      formatDate:'Y-m-d',
    });


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

    $("#selectSchool").change(function(){
      $("#selectSubject").empty();
      $("#selectUser").empty();
      var schoolId=$("#selectSchool").val();
      if(schoolId==""){
          $("#selectSubject").append("<option value=''>Select Subject</option>");
          $("#selectUser").append("<option value=''>Select User</option>");
      }else{
          var schoolIdAjax, token, url, data;
          token = $('input[name=_token]').val();
          schoolIdAjax = schoolId;
          url = '<?php echo url("/"); ?>/getSubjectsAjax';
          data = {
              schoolIdAjax: schoolIdAjax,
          };
          $.ajax({
              url: url,
              headers: {'X-CSRF-TOKEN': token},
              data: data,
              type: 'POST',
              datatype: 'JSON',
              success: function (resp) {
                  $("#selectSubject").append("<option value=''>Select Subject</option>");
                  $("#selectUser").append("<option value=''>Select User</option>");
                  $.each(resp.subjectsList, function (key, value) {
                      var subjectidRes = value.id_subject;
                      var subjectnameRes = value.title_subject;
                      $("#selectSubject").append('<option value="'+subjectidRes+'">'+subjectnameRes+'</option>');
                  });
              }
          });
        }
    });


    $("#selectSubject").change(function(){
        $("#selectUser").empty();
        var schoolId=$("#selectSchool").val();
        var subjectId=$("#selectSubject").val();
        if(subjectId==""){
            $("#selectUser").append("<option value=''>Select User</option>");
        }else{
            var subjectIdAjax, schoolIdAjax, token, url, data;
            token = $('input[name=_token]').val();
            schoolIdAjax = schoolId;
            subjectIdAjax = subjectId;
            url = '<?php echo url("/"); ?>/getUsersAjax';
            data = {
                schoolIdAjax: schoolIdAjax,
                subjectIdAjax: subjectIdAjax,
            };
            $.ajax({
                url: url,
                headers: {'X-CSRF-TOKEN': token},
                data: data,
                type: 'POST',
                datatype: 'JSON',
                success: function (resp) {
                    $("#selectUser").append("<option value=''>Select User</option>");
                    $.each(resp.usersList, function (key, value) {
                        var useridRes = value.user_id;
                        var usernameRes = value.fname+' '+value.lname;
                        $("#selectUser").append('<option value="'+useridRes+'">'+usernameRes+'</option>');
                    });
                }
            });
        }
    });
  });
</script>
@endsection











