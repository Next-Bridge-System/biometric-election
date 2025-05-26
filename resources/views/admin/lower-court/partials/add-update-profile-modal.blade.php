<div class="modal fade" id="addUpdateProfile" tabindex="-1" aria-labelledby="addUpdateProfileLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addUpdateProfileLabel">Profile Image</h5>
                                        <button type="button" class="close" onclick="location.reload()" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                        <div class="modal-body">
                                            @component('components.image-upload-lc')
                                            @slot('is_detail_profile') true @endslot
                                            @slot('label') @endslot
                                            @slot('name') profile_image @endslot
                                            @slot('condition') {{$application->uploads->profile_image ?? null}} @endslot
                                            @endcomponent
                                        </div>

                                </div>
                            </div>
                        </div>
