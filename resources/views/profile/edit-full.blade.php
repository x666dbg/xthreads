<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Edit Profile</h1>
                <p class="text-dark-400 mt-1">Customize your profile information</p>
            </div>
            <a href="{{ route('profile.show', auth()->user()->username) }}" 
               class="text-primary-400 hover:text-primary-300 text-sm font-medium">
                ← Back to Profile
            </a>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="mb-6 bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Profile updated successfully!
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update-full') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Cover Photo Section -->
            <div class="bg-dark-800/30 backdrop-blur-sm border border-dark-700/50 rounded-2xl overflow-hidden">
                <div class="relative h-48 bg-gradient-to-r from-primary-600/20 to-secondary-600/20">
                    @if($user->cover_photo)
                        <img src="{{ asset('storage/' . $user->cover_photo) }}" 
                             alt="Cover Photo" 
                             class="w-full h-full object-cover"
                             id="cover-preview">
                    @else
                        <div class="w-full h-full flex items-center justify-center" id="cover-preview">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-dark-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-dark-400 text-sm">No cover photo</p>
                            </div>
                        </div>
                    @endif

                    <!-- Cover Photo Upload Button -->
                    <div class="absolute bottom-4 right-4">
                        <label for="cover_photo" class="bg-dark-800/80 hover:bg-dark-700/80 text-white px-4 py-2 rounded-lg cursor-pointer transition-all backdrop-blur-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Change Cover
                        </label>
                        <input type="file" id="cover_photo" name="cover_photo" accept="image/*" class="hidden" onchange="previewCover(this)">
                    </div>
                </div>

                @error('cover_photo')
                    <div class="px-6 py-2 text-red-400 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <!-- Profile Photo Section -->
            <div class="bg-dark-800/30 backdrop-blur-sm border border-dark-700/50 rounded-2xl p-6">
                <h3 class="text-xl font-semibold text-white mb-4">Profile Photo</h3>
                
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" 
                                 alt="Profile Photo" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-primary-500/30"
                                 id="photo-preview">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center border-4 border-primary-500/30" id="photo-preview">
                                <span class="text-white font-bold text-2xl">
                                    {{ strtoupper(substr($user->username, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label for="photo" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg cursor-pointer transition-all">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Choose Photo
                        </label>
                        <input type="file" id="photo" name="photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                        <p class="text-dark-400 text-sm mt-2">JPG, PNG, GIF up to 2MB</p>
                    </div>
                </div>

                @error('photo')
                    <div class="mt-2 text-red-400 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <!-- Basic Information -->
            <div class="bg-dark-800/30 backdrop-blur-sm border border-dark-700/50 rounded-2xl p-6">
                <h3 class="text-xl font-semibold text-white mb-6">Basic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-white mb-2">Username</label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               value="{{ old('username', $user->username) }}"
                               class="w-full bg-dark-700/50 border border-dark-600 text-white rounded-lg px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all"
                               required>
                        @error('username')
                            <div class="mt-1 text-red-400 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-white mb-2">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               class="w-full bg-dark-700/50 border border-dark-600 text-white rounded-lg px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all"
                               required>
                        @error('email')
                            <div class="mt-1 text-red-400 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Bio -->
                <div class="mt-6">
                    <label for="bio" class="block text-sm font-medium text-white mb-2">Bio</label>
                    <textarea id="bio" 
                              name="bio" 
                              rows="4"
                              placeholder="Tell us about yourself..."
                              class="w-full bg-dark-700/50 border border-dark-600 text-white rounded-lg px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all resize-none">{{ old('bio', $user->bio) }}</textarea>
                    <div class="flex justify-between mt-1">
                        @error('bio')
                            <div class="text-red-400 text-sm">{{ $message }}</div>
                        @else
                            <div></div>
                        @enderror
                        <div class="text-dark-400 text-sm">
                            <span id="bio-count">{{ strlen(old('bio', $user->bio ?? '')) }}</span>/500
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="mt-6">
                    <label for="location" class="block text-sm font-medium text-white mb-2">Location</label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location', $user->location) }}"
                           placeholder="e.g. Jakarta, Indonesia"
                           class="w-full bg-dark-700/50 border border-dark-600 text-white rounded-lg px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all">
                    @error('location')
                        <div class="mt-1 text-red-400 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Password Change -->
            <div class="bg-dark-800/30 backdrop-blur-sm border border-dark-700/50 rounded-2xl p-6">
                <h3 class="text-xl font-semibold text-white mb-6">Change Password</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-white mb-2">Current Password</label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password"
                               class="w-full bg-dark-700/50 border border-dark-600 text-white rounded-lg px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all">
                        @error('current_password')
                            <div class="mt-1 text-red-400 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-white mb-2">New Password</label>
                        <input type="password" 
                               id="password" 
                               name="password"
                               class="w-full bg-dark-700/50 border border-dark-600 text-white rounded-lg px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all">
                        @error('password')
                            <div class="mt-1 text-red-400 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">Confirm Password</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation"
                               class="w-full bg-dark-700/50 border border-dark-600 text-white rounded-lg px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all">
                    </div>
                </div>

                <p class="text-dark-400 text-sm mt-4">
                    Leave password fields empty if you don't want to change your password.
                </p>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-lg transition-all hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        // Preview profile photo
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-preview').innerHTML = 
                        `<img src="${e.target.result}" alt="Preview" class="w-24 h-24 rounded-full object-cover border-4 border-primary-500/30">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Preview cover photo
        function previewCover(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('cover-preview').innerHTML = 
                        `<img src="${e.target.result}" alt="Cover Preview" class="w-full h-full object-cover">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Bio character count
        document.getElementById('bio').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('bio-count').textContent = count;
        });
    </script>
</x-app-layout>