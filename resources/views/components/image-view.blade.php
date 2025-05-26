<style>
    img {
        cursor: pointer;
    }
</style>

<div class="modal fade" id="imagePopupModal" tabindex="-1" aria-labelledby="imagePopupModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-md-12 text-center">
                    <img src="" alt="" id="popup_image" class="w-100">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('body').on('click','img',function(){
        $('#imagePopupModal').modal('show');
        $('#popup_image').attr('src', $(this).attr('src'));
    })
</script>