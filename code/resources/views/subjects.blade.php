@extends('layouts.app')
@section('content')
<div class="">
  <div class="page-title">
    @if(Session::has('updateSubjectSuccess'))
      <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('updateSubjectSuccess') }}</p>
      <?php Session::forget('updateSubjectSuccess'); ?>
    @endif
    <div class="title_left">
      <h3>SUBJECTS LIST</h3>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2><small>List of added Subjects</small></h2>
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
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($subjects as $data)
              <tr>
                <td>{{ $data->title_subject }}</td>
                <td> <?php if($data->status_subject==1){ echo "Enabled"; } else{ echo "Disabled"; } ?> </td>
                <td>
                  <div class="fa-hover col-md-12 col-sm-12 col-xs-12">
                    <div class="fa-hover col-md-3 col-sm-4 col-xs-12"><a href="{{url('/').'/editSubject'}}/<?php echo $data->id_subject; ?>"><i class="fa fa-edit"></i></a></div>
                    <?php if($data->status_subject==1){ ?>
                      <div class="fa-hover col-md-3 col-sm-4 col-xs-12"><a class="statusClass" href="{{url('/').'/enDsSubject'}}/<?php echo $data->id_subject; ?>"><i class="fa fa-toggle-on"></i></a></div>
                    <?php }else{ ?>
                      <div class="fa-hover col-md-3 col-sm-4 col-xs-12"><a class="statusClass" href="{{url('/').'/enDsSubject'}}/<?php echo $data->id_subject; ?>"><i class="fa fa-toggle-off"></i></a></div>
                    <?php } ?>
                    <div class="fa-hover col-md-3 col-sm-4 col-xs-12"><a class="deleteClass" href="{{url('/').'/delSubject'}}/<?php echo $data->id_subject; ?>"><i class="fa fa-trash-o"></i></a></div>
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











