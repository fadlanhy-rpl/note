@extends('layouts.app')

@section('title', 'Edit Profile')

@push('styles') {{-- Stack untuk CSS spesifik halaman ini --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    .img-container img#imageToCrop { max-width: 100%; display: block; }
    #cropModal .modal-dialog { max-width: 500px; }
    input[type="file"].hidden { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0; }
</style>
@endpush

@section('content')
<div class="p-4 md:p-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-google-sans font-medium text-gray-800">Edit Profile</h1>
            <a href="{{ route('profile.show') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                <i class="ri-arrow-left-line mr-1"></i>Back to Profile
            </a>
        </div>

        {{-- Form Update Informasi Profil --}}
        <div class="bg-white rounded-xl shadow-sm p-6 md:p-8 mb-8">
            <h2 class="text-lg font-google-sans font-medium text-gray-700 mb-6 border-b pb-3">Personal Information</h2>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileUpdateForm">
                @csrf
                @method('PATCH')
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="form-input {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="form-input {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                               max="{{ now()->toDateString() }}"
                               class="form-input {{ $errors->has('date_of_birth') ? 'border-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}">
                        @error('date_of_birth') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                        <div class="mt-1 flex items-center space-x-4">
                            <img id="profileImagePreview" class="h-20 w-20 rounded-full object-cover shadow"
                                 src="{{ $user->profile_image_path ? Storage::url($user->profile_image_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff&size=80&rounded=true' }}"
                                 alt="Current profile photo">
                            <div>
                                <label for="profile_image_input" class="cursor-pointer bg-white py-2 px-3  border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Change Picture</span>
                                    <input type="file" id="profile_image_input" name="profile_image_original" accept="image/png, image/jpeg, image/gif, image/webp" class="hidden">
                                </label>
                                <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF, WEBP up to 2MB.</p>
                            </div>
                            <input type="hidden" name="profile_image" id="cropped_image_data">
                        </div>
                        @error('profile_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('profile_image_original') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Form Update Password --}}
        <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
            <h2 class="text-lg font-google-sans font-medium text-gray-700 mb-6 border-b pb-3">Change Password</h2>
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required
                               class="form-input {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}">
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" id="password" name="password" required
                               class="form-input {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="form-input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk Cropping Gambar -->
<div id="cropModal" class="fixed z-[101] items-center justify-center place-items-center inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center sm:items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full modal-dialog">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Crop Your Image
                        </h3>
                        <div class="mt-2 img-container max-h-[60vh] overflow-hidden">
                            <img id="imageToCrop" src="#" alt="Image to crop">
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="cropAndUploadButton" class="btn btn-primary w-full sm:ml-3 sm:w-auto">
                    Crop & Use
                </button>
                <button type="button" id="cancelCropButton" class="btn btn-secondary mt-3 w-full sm:mt-0 sm:ml-3 sm:w-auto">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
{{-- Tidak ada @endpush di sini, karena @section('content') sudah selesai --}}
@endsection {{-- Ini adalah penutup yang benar untuk @section('content') --}}


@push('scripts') {{-- Stack untuk JavaScript spesifik halaman ini --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('profile_image_input');
    const imagePreview = document.getElementById('profileImagePreview');
    const imageToCrop = document.getElementById('imageToCrop');
    const cropModal = document.getElementById('cropModal');
    const cropAndUploadButton = document.getElementById('cropAndUploadButton');
    const cancelCropButton = document.getElementById('cancelCropButton');
    const croppedImageDataInput = document.getElementById('cropped_image_data');
    const changePictureButtonLabel = document.querySelector('label[for="profile_image_input"]');

    let cropper;
    let originalFile = null;

    // Event listener untuk label "Change Picture" tidak lagi diperlukan jika input disembunyikan
    // dan label hanya berfungsi sebagai pemicu visual. Browser akan menangani klik label.

    if (imageInput && imagePreview && imageToCrop && cropModal && cropAndUploadButton && cancelCropButton && croppedImageDataInput) {
        imageInput.addEventListener('change', function (event) {
            const files = event.target.files;
            if (files && files.length > 0) {
                originalFile = files[0];
                const reader = new FileReader();
                reader.onload = function (e) {
                    imageToCrop.src = e.target.result;
                    cropModal.classList.remove('hidden');
                    cropModal.classList.add('flex');
                    if (cropper) {
                        cropper.destroy();
                    }
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1 / 1,
                        viewMode: 1,
                        dragMode: 'move',
                        background: false,
                        autoCropArea: 0.8,
                        responsive: true,
                        checkCrossOrigin: false,
                        modal: true,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                    });
                };
                reader.readAsDataURL(originalFile);
            }
        });

        cancelCropButton.addEventListener('click', function () {
            cropModal.classList.add('hidden');
            cropModal.classList.remove('flex');
            if (cropper) {
                cropper.destroy();
            }
            imageInput.value = '';
            croppedImageDataInput.value = '';
        });

        cropAndUploadButton.addEventListener('click', function () {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 256,
                    height: 256,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });
                const croppedImageDataURL = canvas.toDataURL(originalFile.type || 'image/jpeg', 0.9);

                imagePreview.src = croppedImageDataURL;
                croppedImageDataInput.value = croppedImageDataURL;

                cropModal.classList.add('hidden');
                cropModal.classList.remove('flex');
                cropper.destroy();
            }
        });
    } else {
        console.warn('One or more elements for profile image cropping are missing.');
    }
});
</script>
@endpush