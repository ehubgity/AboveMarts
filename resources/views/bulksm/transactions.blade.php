@extends('bulksms.master')
@section('header')
@endsection

@section('content')
<div class="container-contact1">
    <div class="contact1-pic js-tilt" data-tilt>
        <img src="bulkasset/images/img-01.png" alt="IMG">
    </div>

    <span class="contact1-form-title">
       Bulk SMS Transactions
       </span>




    <table class="table table-striped">
        <thead class="alert alert-success">
            <tr>
                <th scope="col">S/N</th>
                <th scope="col">Title</th>
                <th scope="col">Sender</th>
                <th scope="col">Recipient</th>
                <th scope="col">Message</th>
                <th scope="col">Amount</th>
                <th scope="col">Before / After</th>
                <th scope="col">Status</th>
                <th scope="col">Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $key => $tranx)
            <tr>
                <th scope="row">{{ ++$key }}</th>
                <td>{{ $tranx->title }}</td>
                <td>{{ $tranx->sender }}</td>
                <td>{{ Str::limit($tranx->recipient,15) }}</td>
                <td>{{ $tranx->message }}</td>
                <td>NGN{{ number_format($tranx->amount) }}</td>
                <td>NGN{{ number_format($tranx->before) }} / NGN{{ number_format($tranx->after) }} </td>
                <td>
                    @if($tranx->status == 0) 
                    <span class='badge badge-danger'>Failed</span>
                    @else 
                    <span class='badge badge-success'>Success</span>
                    @endif
                </td>
              
                <td>{{ Date('Y-m-d|h:i A',strtotime($tranx->created_at)) }}</td>
                <td>
                    <a onclick='return confirm("Are you sure you want to resend this SMS?")' class='btn btn-primary btn-sm' href='/resend_sms/{{ $tranx->id }}'>Resend</a>
                    <a class='btn btn-info btn-sm' href='/view_details/{{ $tranx->id }}'>Detais</a>
               
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>

    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create New Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method='post' action='{{ route("saveContacts") }}' enctype="multipart/form-data">@csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Group Name</label>
                                <input type="text" name='name' class="form-control" placeholder="Name of Group">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Description</label>
                                <input type="text" name='description' class="form-control"
                                    placeholder="Short Description">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="radio" name="contact_type" id="manual_input" value="manual_input" checked>
                            <label for="contact_type1"><b>
                                    Manual Input</b>
                            </label>
                            <input type="radio" name="contact_type" id="import_csv" value="import_file">
                            <label for="contact_type2"><b>
                                    Import from file(csv)</b>
                            </label><br>
                            <label for="inputAddress">Contacts</label>
                            <div id='manual_input_field'>
                                <textarea type="text" name='contacts' class="form-control" id="contact_field"
                                    placeholder="Contacts"></textarea>
                                <input type="hidden" name="contacts" id="appendedNumbersInput">

                                <span class="shadow-input1"></span>
                                <div id="output-container"></div>
                            </div>
                        </div>
                        <div style='display:none' id='import_field' class="wrap-input1 validate-input"
                            data-validate="Message is required">


                            <div class="input-group mb-3">
                                <input accept=".xls, .xlsx, .csv" type="file" class="form-control" name='import_file'>

                            </div>
                        </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
                </form>
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

function confirmResend() {
  Swal.fire({
    title: 'Are you sure?',
    text: 'Do you want to resend this SMS?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    cancelButtonText: 'No',
  }).then((result) => {
    if (result.isConfirmed) {
      // User clicked "Yes," navigate to the /resend_sms route
      window.location.href = '/resend_sms/{{ $tranx->id }}';
    } else {
      // User clicked "No," prevent the default link behavior
      return false;
    }
  });
}
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