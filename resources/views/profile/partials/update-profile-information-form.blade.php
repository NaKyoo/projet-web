<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @error('current_password')
    <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror

    @error('new_password')
    <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror

    @error('new_password_confirmation')
    <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror

    <div class="card pb-2.5">
        <div class="card-header" id="basic_settings">
            <h3 class="card-title">
                Basic Settings
            </h3>
        </div>
        <div class="card-body grid gap-5">

            <div class="flex items-center flex-wrap gap-2.5">
                <label class="form-label max-w-56">
                    Photo
                </label>
                <div class="flex items-center justify-between flex-wrap grow gap-2.5">
                    <span class="text-2sm text-gray-700">150x150px JPEG, PNG Image</span>


                    <!-- Avatar input -->
                    <div class="form-group">

                        <!-- Image Preview (similar to Metronic style) -->
                        <div class="image-input-placeholder rounded-full border-2 border-success image-input-empty:border-gray-300"
                             style="background-image:url('{{ asset('metronic/media/avatars/300-2.png') }}');">

                            <!-- Image preview -->
                            <div id="image-preview" class="image-input-preview rounded-full"
                                 style="background-image: url('{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('metronic/media/avatars/300-2.png') }}');">
                            </div>

                            <!-- Tooltip for removing or reverting image -->
                            <div id="remove-image-btn" class="btn btn-icon btn-icon-xs btn-light shadow-default absolute z-1 size-5 -top-0.5 -end-0.5 rounded-full" data-tooltip="#image_input_tooltip" data-tooltip-trigger="hover" onclick="removeImage()">
                                <i class="ki-filled ki-cross"></i> <!-- Cross icon for removal -->
                            </div>
                        </div>

                        <span class="tooltip" id="image_input_tooltip">Click to remove or revert</span>

                        <!-- Hidden file input -->
                        <input type="file" name="avatar" id="avatar" accept="image/png, image/jpeg, image/jpg" class="d-none" onchange="previewImage(event)">

                        <!-- Custom button to trigger file input -->
                        <button type="button" class="btn btn-icon btn-icon-xs btn-light shadow-default rounded-full" onclick="document.getElementById('avatar').click()">
                            <i class="ki-filled ki-pencil"></i> <!-- Pen icon for file selection -->
                        </button>

                        <!-- Hidden input for avatar removal -->
                        <input name="avatar_remove" type="hidden" id="avatar_remove" value=""/>

                    </div>

                </div>
            </div>
            <div class="w-full">
                <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                    <label class="form-label flex items-center gap-1 max-w-56">
                        Last name
                    </label>
                    <x-forms.input
                        name="last_name" type="text" :value="old('name', auth()->user()->last_name)"
                        required autofocus class="w-full" :messages="$errors->get('last_name')" />
                </div>
            </div>
            <div class="w-full">
                <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                    <label class="form-label flex items-center gap-1 max-w-56">
                        First name
                    </label>
                    <x-forms.input
                        name="first_name" type="text" :value="old('name', auth()->user()->first_name)"
                        required autofocus class="w-full" :messages="$errors->get('first_name')" />
                </div>
            </div>
            <div class="flex justify-end pt-2.5">
                <button class="btn btn-primary">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</form>

<style>
    /* Styles pour l'aperçu de l'image */
    .image-input-placeholder {
        position: relative;
        width: 120px;
        height: 120px;
        background-size: cover;
        background-position: center;
        border-radius: 50%;
        margin-left: 220px;
    }

    .image-input-preview {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-size: cover;
        background-position: center;
    }

    input[type="file"]::-webkit-file-upload-button {
        display: none;
    }

    input[type="file"] {
        color: transparent;
    }
</style>

<script>

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById('image-preview');
            preview.style.backgroundImage = 'url(' + reader.result + ')';
            document.getElementById('avatar_remove').value = ''; // Clear the remove flag when a new image is uploaded
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    // Function to remove the image and reset the avatar
    function removeImage() {
        const preview = document.getElementById('image-preview');
        preview.style.backgroundImage = 'url("{{ asset('metronic/media/avatars/300-2.png') }}")'; // Default Metronic image
        document.getElementById('avatar').value = ''; // Reset file input to ensure no file is sent
        document.getElementById('avatar_remove').value = '1'; // Set flag to indicate removal when form is submitted
    }

</script>

