<style>
    .image-preview {
        height: 200px;
        width: 100%
    }
</style>

<div class="{{ isset($is_detail_profile) &&  $is_detail_profile == 'true' ? '' : 'col-md-4' }} form-group">
    @if (!isset($is_detail_profile))
    <label>{{$label}}: <span class="text-danger">*</span></label>
    @endif
    @if ($condition != '' && $condition != NULL && !empty($condition))
    <div class="col-md-12">
        <img src="{{asset('storage/app/public/'.$condition)}}" alt="" class="image-preview">
    </div>

    <a href="javascript:void(0)"
    @if(isset($appId))
        data-action="{{route('intimations.destroy.profile-image',['app_id' => $appId])}}"
    @else
        data-action="{{route('intimations.destroy.profile-image', $name)}}"
    @endif
        class="btn btn-danger btn-xs col-md-12 mt-2 mb-4" onclick="removeImage(this,1)">Remove</a>
    @else
    <input type="file" id="{{$name}}" name="{{$name}}" accept="image/jpg,image/jpeg,image/png">
    @endif

    <div class="errors" data-id="{{$name}}"></div>
</div>

<script>
    var currentRouteName = document.body.dataset.page;
    @if(isset($appId))
        var url = "{{route('intimations.uploads.profile-image',['app_id' => $appId])}}"
    @else
        var url = "{{route('intimations.uploads.profile-image')}}"
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

    function removeImage(event, step) {
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
                    if (step === 1) {
                        location.reload();
                    } else {
                        // var action = $('form').data('action');
                        var action = $('.steps-form').data('action');
                        goToStep(action, step)
                    }
                });
            }
        })
    }
</script>
