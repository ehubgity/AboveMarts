<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from seantheme.com/color-admin/admin/html/email_compose.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 09 Sep 2022 13:15:35 GMT -->
<head>
<meta charset="utf-8" />
<title>AboveMarts | Support</title>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
<link href="../assets/css/vendor.min.css" rel="stylesheet" />
<link href="../assets/css/default/app.min.css" rel="stylesheet" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link href="../assets/plugins/tag-it/css/jquery.tagit.css" rel="stylesheet" />
<link href="../assets/plugins/summernote/dist/summernote-lite.css" rel="stylesheet" />

</head>
@include('user.header')
@include('user.sidebar')

<div class="app-sidebar-bg"></div>
<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>


<div id="content" class="app-content p-0">

<div class="mailbox">

<div class="mailbox-sidebar">
<div class="mailbox-sidebar-header d-flex justify-content-center">


</div>

</div>


<div class="mailbox-content">
<div class="mailbox-content-header">

<h2>Open Ticket</h2>

</div>
<div class="mailbox-content-body">

<div data-scrollbar="true" data-height="100%" data-skip-mobile="true">

<form action="{{ route('support') }}" method="POST" name="email_to_form" class="mailbox-form">
@csrf

<div class="mailbox-to">
<label class="control-label" style="width: 8%">Email:</label>
<input type="email" class="form-control" name="email" placeholder="Email Address" required>

</div>

<div data-id="extra-cc"></div>

<div class="mailbox-to">
<label class="control-label" style="width: 8%">Subject:</label>
<input type="text" name="subject" class="form-control" placeholder="Subject" required/>
</div>


<div class="mailbox-to" >
<label class="control-label" style="width: 8%">Message:</label>
<textarea name="message" class="form-control" required></textarea>
</div>



</div>

</div>
<div class="mailbox-content-footer d-flex align-items-center justify-content-end">
<button type="submit" class="btn btn-primary ps-40px pe-40px">Send</button>
</form>
</div>
</div>

</div>

</div>

<div class="alert alert-secondary alert-dismissible rounded-0 mb-0 fade show">
    <button type="button" class="btn-close" data-bs-dismiss="alert">
    </button>
    Tickets
    </div>
    
    <div class="panel-body">
    
    </table> <table id="data-table-responsive" class="table table-striped table-bordered align-middle">
        <thead>
        <tr>
        <th class="text-nowrap">Ticket ID</th>
        <th class="text-nowrap">Title</th>
        <th class="text-nowrap">Message</th>
        <th class="text-nowrap">Status</th>
        <th class="text-nowrap">Date</th>
    
        </tr>
        </thead>
        @foreach ( $datas as $data )
    
        <tbody>
        <tr class="odd gradeX">
        <td>{{ $data->ticketId }}</td>
        <td>{{ $data->title }}</td>
        <td>{{ $data->msg }}</td>
        <td>{{ $data->status }}</td>
        <td>{{ $data->created_at }}</td>
        </tr>
        </tr>
        </tbody>
        @endforeach
        </table>
    </div>
    
    </div>


<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>

</div>

@include("user.footer");