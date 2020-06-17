@extends('layouts.app')
@section('content')
<h3>TERMS AND CONDITIONS</h3>
  <div class="row">
    <div class="col-sm-10">

    @if(Session::has('updateTermSuccess'))
      <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('updateTermSuccess') }}</p>
    @endif

      <form enctype="multipart/form-data" method="post" id="adddeocsv" action="{{url('saveTermCondition') }}">
      <input type="hidden" name="_token" value="<?= csrf_token(); ?>">
      <input type="hidden" name="termId" class="form-control" value="{{ $termsConditions->id }}">

        <div class="form-group{{ $errors->has('termTitle') ? ' has-error' : '' }}">
          <label class="form-label">Title</label>
          <input type="text" name="termTitle" class="form-control" value="{{ old( 'termTitle', $termsConditions->title) }}">
          @if ($errors->has('termTitle'))
            <span class="help-block">
            <strong>{{ $errors->first('termTitle') }}</strong>
            </span>
          @endif
        </div>

        <div class="form-group{{ $errors->has('termContent') ? ' has-error' : '' }}">
          <label class="form-label">Content</label>
          <textarea id="editor1" class="ckeditor" rows="4" cols="30" name="termContent" value="" required>{{ old( 'termContent', $termsConditions->content) }}</textarea>
          @if ($errors->has('termContent'))
            <span class="help-block">
            <strong>{{ $errors->first('termContent') }}</strong>
            </span>
          @endif
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
      </form>
    </div>
  </div>
  
<script src="http://quieroundron.com/templateEditor/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
CKEDITOR.replace( 'editor1' );
</script>
@endsection



