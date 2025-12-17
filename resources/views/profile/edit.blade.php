<x-app-layout>
    <x-slot name="header">
        <!-- Judul Header konsisten dengan gaya dashboard -->
        <h2 class="text-2xl font-extrabold text-text-light dark:text-text-dark">
            {{ __('Pengaturan Profil') }}
        </h2>
    </x-slot>

    <!-- Konten utama disesuaikan agar padding tidak berlebihan -->
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- 1. Update Profile Information Form (Kartu standar) -->
            <div class="p-4 sm:p-8 
                        bg-white dark:bg-base-dark/80 
                        shadow-xl dark:shadow-2xl dark:shadow-poco-900/50 
                        rounded-2xl transition-all duration-500">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- 2. Update Password Form (Kartu standar) -->
            <div class="p-4 sm:p-8 
                        bg-white dark:bg-base-dark/80 
                        shadow-xl dark:shadow-2xl dark:shadow-poco-900/50 
                        rounded-2xl transition-all duration-500">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- 3. Delete User Form (Kartu peringatan dengan batas merah) -->
            <div class="p-4 sm:p-8 
                        bg-white dark:bg-base-dark/80 
                        shadow-xl dark:shadow-2xl dark:shadow-poco-900/50 
                        rounded-2xl transition-all duration-500 
                        border border-red-300 dark:border-red-800">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>