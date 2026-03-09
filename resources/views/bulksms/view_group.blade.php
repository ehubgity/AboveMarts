@extends('bulksms.master')
@section('header')
@endsection

@section('content')


<div id="content" class="app-content">

  <div class="col-xl-12 ui-sortable">
    <div class="panel panel-inverse m-4 p-4" data-sortable-id="form-stuff-1">


      <span class="contact1-form-title">
        <h1>{{ $contact->name }}</h1>
        
        {{-- <h5>{{ $contact->description }}</h5> --}}

        <a onclick='return history.back()' class='btn btn-success text-white'>Back</a>
      </span>
      <div class='col-md-12'>
        <span class="contact1-form-title">
          <h5>Contact Info</h5>
        </span>

        <div class='alert alert-success'>{{ $contacts }}</div>
      </div>

    </div>
  </div>
</div>



@endsection
@section('script')
<script>
  $(document).ready(function() {

$("#import_csv").click(function() {
  $("#import_field").show()
  $("#manual_input_field").hide()
  $("#select_group_field").hide()
})
$("#manual_input").click(function() {
  $("#manual_input_field").show()
  $("#select_group_field").hide()
  $("#import_field").hide()
})
$("#select_group").click(function() {
  $("#manual_input_field").hide()
  $("#select_group_field").show()
  $("#import_field").hide()
})
$("#sms").on('input', function() {
  var sms_length = $("#sms").val().length
  if(sms_length < 157) {
      $("#character").text(157 - sms_length)
  console.log(sms_length)
  } else {
      $("#max_char").text('You have reached the maximum length')
      $("#sms").prop('disabled',true)
  }
 

})
$("#scheduleSend").click(function() {
  scheduleSend()
})
function scheduleSend() {


Swal.fire({
 title: 'Schedule Send For Later',
 html: "<input id='sweet_alert_date' class='form-control form-input' min='" + new Date().toISOString().split("T")[0] + "' type='date'/><br><input id='sweet_alert_time' class='form-control form-input' type='time' />",
 showCancelButton: true,
 confirmButtonText: "Send SMS Later",
 preConfirm: () => {
   // Get the selected date from the date picker
   const selectedDate = document.getElementById('sweet_alert_date').value;
   const selectedTime = document.getElementById('sweet_alert_time').value;
   console.log('Selected Date:', selectedDate, selectedTime);
   $("#schedule_date").val(selectedDate)
   $("#schedule_time").val(selectedTime)
 },
}).then((result) => {
              // If the user confirms, submit the form
              if (result.isConfirmed) {
                  $(".myForm").submit();
              }
          });;


}

})
</script>

@endsection