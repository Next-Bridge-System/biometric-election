<button type="button" class="btn btn-primary float-right btn-sm m-1" data-toggle="modal"
    data-target="#sendMessageModal">
    <i class="far fa-comments mr-1"></i>Send Messages
</button>

<div class="modal fade" id="sendMessageModal" tabindex="-1" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendMessageModalLabel">Send Messages Lower court</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="send_messages_form_lc" method="POST"> @csrf
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-sm">

                        <body>
                            <tr>
                                <th>Register Message</th>
                                <td><button type="button" class="btn btn-primary btn-sm" onclick="sendMessageLc(1)">Send
                                        it</button></td>
                            </tr>
                            <tr>
                                <th>OTP Message</th>
                                <td><button type="button" class="btn btn-primary btn-sm" onclick="sendMessageLc(2)">Send
                                        it</button></td>
                            </tr>
                            <tr>
                                <th>Application Message</th>
                                <td><button type="button" class="btn btn-primary btn-sm" onclick="sendMessageLc(3)">Send
                                        it</button></td>
                            </tr>
                            <tr>
                                <th>Interview Message</th>
                                <td><button type="button" class="btn btn-primary btn-sm" onclick="sendMessageLc(4)">Send
                                        it</button></td>
                            </tr>
                        </body>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function sendMessageLc(message_type) {
        var application_id = '{{$application->id}}';
        $.ajax({
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'send_message_value': message_type,
                'application_id': application_id,
                'application_type': 'lc',
            },
            url: '{{route('sendMessage')}}',
            beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
                $(".btn").attr('disabled', true);
            },
            success: function (response) {
                $(".custom-loader").addClass('hidden');

                Swal.fire(
                'Sent Successfully!',
                'The message has been successfully sent to the advocate.',
                'success'
                )

                $('#sendMessageModal').modal('toggle');
                $(".btn").attr('disabled', false);

            },
            error : function (errors) {
                $(".custom-loader").addClass('hidden');
            }
        });
    }
</script>
