@extends('layouts.app')
@section('content')
<div class="">
  <div class="page-title">
    @if(Session::has('updateSchoolSuccess'))
      <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('updateSchoolSuccess') }}</p>
      <?php Session::forget('updateSchoolSuccess'); ?>
    @endif
    <div class="title_left">
      <h3>SCHOOLS LIST</h3>
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
          <h2><small>List of added Schools</small></h2>
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
                <th>School Name</th>
                <th>Address</th>
                <th>Country</th>
                <th>State</th>
                <th>City</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($schools as $data)
              <tr>
                <td>{{ $data->name_school }}</td>
                <td>{{ $data->address_school }}</td>
                <td>{{ $data->contName }}</td>
                <td>{{ $data->stateName }}</td>
                <td>{{ $data->cityName }}</td>
                <td> <?php if($data->status_school==1){ echo "Enabled"; } else{ echo "Disabled"; } ?> </td>
                <td>
                  <div class="fa-hover col-md-12 col-sm-12 col-xs-12">

                    <div class="fa-hover col-md-3 col-sm-4 col-xs-12"><a href="{{url('/').'/editSchool'}}/<?php echo $data->id_school; ?>"><i class="fa fa-edit"></i></a></div>

                    <?php if($data->status_school==1){ ?>
                      <div class="fa-hover col-md-3 col-sm-4 col-xs-12"><a class="statusClass" href="{{url('/').'/enDsSchool'}}/<?php echo $data->id_school; ?>"><i class="fa fa-toggle-on"></i></a></div>
                    <?php }else{ ?>
                      <div class="fa-hover col-md-3 col-sm-4 col-xs-12"><a class="statusClass" href="{{url('/').'/enDsSchool'}}/<?php echo $data->id_school; ?>"><i class="fa fa-toggle-off"></i></a></div>
                    <?php } ?>

                    <div class="fa-hover col-md-3 col-sm-4 col-xs-12"><a class="deleteClass" href="{{url('/').'/delSchool'}}/<?php echo $data->id_school; ?>"><i class="fa fa-trash-o"></i></a></div>
                  </div>
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











