<style>
    .image-preview {
        height: 200px;
        width: 100%
    }
</style>

@if (in_array(Route::currentRouteName(),['frontend.high-court.show']))
<div class="col-md-4 form-group">
    <label>{{$label}}: <span class="text-danger">*</span></label>
    <div class="col-md-12">
        <img src="{{asset('storage/app/public/'.$condition)}}" alt="" class="image-preview">
    </div>
</div>
@else
<div class="col-md-4 form-group">
    <label>{{$label}}: <span class="text-danger">*</span></label>
    @if ($condition != '' && $condition != NULL && !empty($condition))
    <div class="col-md-12">
        <img src="{{asset('storage/app/public/'.$condition)}}" alt="" class="image-preview">
    </div>


    @if(in_array(Route::currentRouteName(),['high-court.show']) && permission('edit-high-court'))
    <a href="javascript:void(0)" data-action="{{route('high-court.destroy.file', $name)}}" onclick="removeImage(this,6)"
        class="btn btn-danger btn-xs col-md-12 mt-2 mb-4">Remove</a>
    @endif

    @if(in_array(Route::currentRouteName(),['frontend.high-court.create-step-4','frontend.high-court.create-step-5']))
    <a href="javascript:void(0)" data-action="{{route('frontend.high-court.destroy.file', $name)}}"
        onclick="removeImage(this,6)" class="btn btn-danger btn-xs col-md-12 mt-2 mb-4">Remove</a>
    @endif

    @else
    <input type="file" id="{{$name}}" name="{{$name}}" accept="image/jpg,image/jpeg,image/png">
    @endif

    <div class="errors" data-id="{{$name}}"></div>
</div>
@endif

<script>
    var currentRouteName = document.body.dataset.page;
    
    if (currentRouteName == 'high-court.show') {
        var url = '{{ route("high-court.upload.file", ":slug") }}';
    } else {
        var url = '{{ route("frontend.high-court.upload.file", ":slug") }}';
    }

    url = url.replace(':slug', '{{$name}}');

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
                        
                        if (currentRouteName == 'high-court.show') {
                            location.reload();
                        } else {
                            if (step === 1) {
                                location.reload();
                            } else {
                                var action = $('.steps-form').data('action');
                                goToStep(action, step)
                            }
                        }
                });
            }
        })
    }
</script>