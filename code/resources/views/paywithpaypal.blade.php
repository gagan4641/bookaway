<form style="display:none;" class="form-horizontal" method="POST" id="payment-form" role="form" action="{!! URL::route('paypal') !!}" >
{{ csrf_field() }}
<input id="amount" type="hidden" class="form-control" name="fees" value="{{$fees}}">
<input id="bprice" type="hidden" class="form-control" name="bprice" value="{{$bprice}}">
<input id="bookName" type="hidden" class="form-control" name="bookName" value="{{$bname}}">
<input id="buserid" type="hidden" class="form-control" name="buserid" value="{{$uid}}">
<input id="bschool" type="hidden" class="form-control" name="bschool" value="{{$schid}}" autofocus>
<input id="bsubject" type="hidden" class="form-control" name="bsubject" value="{{$subId}}">
<input id="bpic" type="hidden" class="form-control" name="bpic" value="{{$bpic}}">
<input id="bauth" type="hidden" class="form-control" name="bauth" value="{{$bauth}}">
<input id="bdescription" type="hidden" class="form-control" name="bdescription" value="{{$bdes}}">
<input id="bcondition" type="hidden" class="form-control" name="bcondition" value="{{$bcon}}">
<button type="submit" class="btn btn-primary">Paywith Paypal</button>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#payment-form").submit();
});
</script>