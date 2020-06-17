@extends('layouts.app')
@section('content')
<div class="">
  <div class="page-title">
    <div class="title_left">
      <h3>ASSIGN SUBJECTS</h3>
    </div>
    <div class="title_right">
      <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-center">
          <select name="country" class="form-control col-md-7 col-xs-12" id="selectSchool">
            <option value="">Select School</option>
            @foreach($schools as $schoolData)
              <option value="{{ $schoolData->id_school }}" @if($sschool==$schoolData->id_school) selected @endif >{{ $schoolData->name_school }}</option>
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
          <h2><small>List of subjects</small></h2>
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
                <th>Subject Name</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($subjects as $data)
              <tr>
                <td>{{ $data->title_subject }}</td>
                <td><input class="chkboxClass" type="checkbox" <?php if(in_array($data->id_subject, $school_subjects)){ echo "checked"; } ?> value="{{$data->id_subject}}"></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
{{ csrf_field() }}
<script type="text/javascript">
  $(document).ready(function(){
    $("#selectSchool").change(function(){
      var school=$("#selectSchool").val();

      if(school==""){
        url = '<?php echo url("/"); ?>/assignSubjects';
        window.location.replace(url);
      }else{
        url = '<?php echo url("/"); ?>/assignSubjects/'+school;
        window.location.replace(url); 
      }
        
    });

    $("#datatable").on("click", ".chkboxClass", function(){
      var subid=$(this).val();
      var schoolid=$('#selectSchool').val();
      if($(this). prop("checked") == true){
        var checked=1;
      }
      else if($(this). prop("checked") == false){
        var checked=0;
      }
      var subidAjax, schoolidAjax, checkedAjax, token, url, data;
      token = $('input[name=_token]').val();
      url = '<?php echo url("/"); ?>/assignAjaxSubject';
      data = {
        subidAjax: subid,
        schoolidAjax: schoolid,
        checkedAjax: checked
      };
      $.ajax({
          url: url,
          headers: {'X-CSRF-TOKEN': token},
          data: data,
          type: 'POST',
          datatype: 'JSON',
          success: function (resp) { 
            var resSubs=resp.pass;
            if(resSubs==0){
              alert('Please Try Again');
            } 
          }
      });
    });

  });
</script>
@endsection











