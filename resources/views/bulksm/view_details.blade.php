@extends('bulksms.master')
@section('header')
@endsection

@section('content')
<div class="container-contact1">
    <div class="contact1-pic js-tilt" data-tilt>
        <img src="{{ asset('bulkasset/images/img-01.png')}}" alt="IMG">
    </div>


    <span class="contact1-form-title">
        Transaction Details<br>
        {{-- <h5>: {{ $contact->description }}</h5> --}}
        
        <a onclick='return history.back()' class='btn btn-success text-white'>Back</a>
    </span>
    <div class='col-md-12'>
        <span class="contact1-form-title">
        <h5>Transaction Details</h5>
        </span>

        <div class='alert @if($transaction->status == 1)alert-success @else alert-danger @endif'>
            <ul>
                <li><b>Title</b> : {{ $transaction->title }}</li>
                <li><b>Details</b> : {{ $transaction->description }}</li>
                <li><b>Sender Name</b> : {{ $transaction->sender_name }}</li>
                <li><b>Recipients</b> : {{ $transaction->recipient }}</li>
                <li><b>Message Sent</b>  : {{ $transaction->message }}</li>
                <li><b>Amount Charged</b> : NGN{{ number_format($transaction->amount,2) }}</li>
                <li><b>Date Of Transaction</b> : {{ Date('Y-m-d h:i:s',strtotime($transaction->created_at)) }}</li>
            </ul>
        </div>
    </div>


    <!-- Button trigger modal -->

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