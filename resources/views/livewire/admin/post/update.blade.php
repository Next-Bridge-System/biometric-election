<div wire:ignore.self class="modal fade" id="post_modal" tabindex="-1" aria-labelledby="post_modal_label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="post_modal_label">{{ $edit_mode ? 'Edit' : 'Add' }} Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="">Subject <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" wire:model.defer="subject">
                        @error('subject') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Post Type <span class="text-danger">*</span></label>
                        <select wire:model.defer="post_type" class="form-control custom-select">
                            <option value="">Select</option>
                            @foreach ($post_types as $post_type)
                            <option value="{{$post_type->id}}">{{$post_type->name}}</option>
                            @endforeach
                        </select>
                        @error('post_type') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="">Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" wire:model.defer="lawyer_name">
                        @error('lawyer_name') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Father/Husband</label>
                        <input class="form-control" type="text" wire:model.defer="father_husband">
                        @error('father_husband') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Station/Address</label>
                        <input class="form-control" type="text" wire:model.defer="address">
                        @error('address') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">CNIC No</label>
                        <input class="form-control" type="text" wire:model.defer="cnic_no" id="cnic_no">
                        @error('cnic_no') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Mobile No</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><img src="{{asset('public/admin/images/pakistan.png')}}"
                                        alt=""></span>
                                <span class="input-group-text">+92</span>
                            </div>
                            <input class="form-control" type="text" wire:model.defer="mobile_no" id="mobile_no">
                        </div>
                        @error('mobile_no') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <div>
                            <video id="video" autoplay playsinline width="350" height="350"
                                style="border: 1px solid black;"></video>
                            <canvas id="canvas" style="display: none;"></canvas>
                        </div>
                        <button wire:click="saveImage" class="btn btn-primary btn-sm" id="capture">Capture
                            Image</button>
                    </div>
                    <div class="col-md-6">
                        @if ($capturedImage)
                        <label for="">Webcam Captured Image</label>
                        <img src="{{ $capturedImage }}" alt="Captured Image" width="250"
                            style="border: 2px solid green;">
                        @elseif ($webcam_image_url)
                        <img src="{{asset('storage/app/public/'.$webcam_image_url)}}" width="250"
                            style="border: 2px solid green;">
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-sm"
                    wire:click.prevent="{{ $edit_mode ? 'update' : 'store' }}">
                    {{ $edit_mode ? 'Update' : 'Save changes' }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    const captureButton = document.getElementById('capture');

    // Start the webcam stream
    async function startWebcam() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
        } catch (error) {
            console.error('Error accessing webcam:', error);
            alert('Could not access the webcam. Please check your permissions.');
        }
    }

    // Start the webcam on page load
    document.addEventListener('DOMContentLoaded', startWebcam);

    // Capture image and send it to Livewire
    captureButton.addEventListener('click', () => {
        // Draw the video frame onto the canvas
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convert canvas to Base64 string
        const base64Image = canvas.toDataURL('image/png');
        console.log(base64Image);

        // Set the captured image in Livewire
        Livewire.emit('set-captured-image', base64Image);
    });
</script>

<script>
    var mobile_options = {
        mask: '000-0000000'
    };
    var cnic_options = {
        mask: '00000-0000000-0'
    };

    function initInvoiceInputMask() {
        var mobile_mask = IMask(document.getElementById('mobile_no'), mobile_options);
        var cnic_mask = IMask(document.getElementById('cnic_no'), cnic_options);
    }

    initInvoiceInputMask();

    document.addEventListener('DOMContentLoaded', async function() {
        window.Livewire.on('reset-mask-values', function() {
           initInvoiceInputMask();
        });
    });
</script>