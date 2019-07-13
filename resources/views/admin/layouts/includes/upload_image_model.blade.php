<!-- The Modal -->
<div class="modal" id="uploadImageModel">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="" enctype="multipart/form-data" id="upload-image-form">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Upload hình ảnh</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">


          {{-- Title --}}
          <div class="form-row">
            <div class="col mb-3">
              <label for="title">{{ __('admin.form.images.title.label') }}</label>
              <input type="text" class="form-control" id="title" name="title"
              placeholder="{{ __('admin.form.images.title.place_holder') }}"
              value="@if(isset($data->title)){{$data->title}}@endif" required>
              <div class="invalid-feedback">
                {{ __('admin.form.images.title.invalid') }}
              </div>
            </div>
          </div>


          {{-- Description --}}
          <div class="form-row">
            <div class="col mb-3">
              <label for="description">{{ __('admin.form.images.description.label') }}</label>
              <textarea class="form-control" id="description" name="description"
              placeholder="{{ __('admin.form.images.description.place_holder') }}"
              value="@if(isset($data->description)){{$data->description}}@endif" required></textarea>
              <div class="invalid-feedback">
                {{ __('admin.form.images.description.invalid') }}
              </div>
            </div>
          </div>


          {{-- Image --}}
          <div class="form-row">
            <div class="col mb-3">
              <label for="origin">{{ __('admin.form.images.origin.label') }}</label>
              <input type="file" class="form-control" id="origin" name="origin"
              placeholder="{{ __('admin.form.images.origin.place_holder') }}"
              value="@if(isset($data->origin)){{$data->origin}}@endif" required>
              <div class="invalid-feedback">
                {{ __('admin.form.images.origin.invalid') }}
              </div>
            </div>
          </div>


        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button name="btn-upload-image" type="button" class="btn btn-primary">Upload</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

      </form>

    </div>
  </div>
</div>