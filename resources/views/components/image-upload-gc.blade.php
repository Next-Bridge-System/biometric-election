<style>
    .image-preview {
        height: 200px;
        width: 100%
    }
</style>


<div class="form-group">
    <label>{{$label}}</label><br>
    @if ($condition != '' && $condition != NULL && !empty($condition))
        <div class="col-md-12">
            <img src="{{asset('storage/app/public/'.$condition)}}" alt="" class="image-preview">
        </div>

        @if ($type == 'gc_profile_image' && permission('gc_lower_court_edit'))
            <div href="javascript:void(0)" data-action="{{route('gc-lower-court.delete-media',["user_id" => $user_id])}}" id="rm-img" onclick="removeImage(this)"
                class="btn btn-danger btn-xs col-md-12 mt-2 mb-4" style="cursor:pointer">Remove</a>
        @elseif (permission('gc_high_court_edit') && $type == 'hc')
            <div href="javascript:void(0)" data-action="{{route('gc-high-court.delete-media',["user_id" => $user_id])}}" id="rm-img" onclick="removeImage(this)"
                class="btn btn-danger btn-xs col-md-12 mt-2 mb-4" style="cursor:pointer">Remove</a>
        @endif
    @else
        @if ((permission('gc_lower_court_edit') && $type == 'gc_profile_image') || (permission('gc_high_court_edit') && $type == 'hc'))
            <input type="file" id="{{$name}}" name="{{$name}}" accept="image/jpg,image/jpeg,image/png">
        @else
            <div class="col-md-12">
                <img src="{{asset('storage/app/public/')}}" alt="No Image Found" class="image-preview" >
            </div>
        @endif
    @endif

    <div class="errors" data-id="{{$name}}"></div>
</div>

<script>
    var currentRouteName = document.body.dataset.page;

    @if ($type == 'gc_profile_image')
        var url = '{{ route("gc-lower-court.upload-media",["user_id" => $user_id]) }}';
    @else
        var url = '{{ route("gc-high-court.upload-media",["user_id" => $user_id]) }}';
    @endif

    var image = FilePond.create(document.querySelector('input[id="{{$name}}"]'), {
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
                required: false,
                allowMultiple: false,
                allowFileSizeValidation: true,
                maxFileSize: '1MB',
                allowRevert: false,
                fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                resolve(type);
                })
            });

        image.setOptions({
            server: {
                url: url,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            }
        });

    function removeImage(event) {
        Swal.fire({
            icon: 'warning',
            title: 'Warning!',
            text: "Are you sure you want to delete it? This action cannot be revert or undone.",
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.value) {
                $(".custom-loader").removeClass('hidden');
                    $.get(event.dataset.action, function (data, status) {
                        console.log(data, status);
                        location.reload();

                });
            }
        })
    }
</script>
