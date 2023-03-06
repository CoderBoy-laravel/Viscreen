<x-app-layout>
    <div class="mt-5 md:flex items-center justify-between">
        <div class="flex bg-gradient-to-r items-center from-teal-500 to-emerald-800 px-5 py-3 md:w-1/4 rounded-md shadow-md mb-5 md:mb-0">
            <div class="px-3 py-2 bg-white text-sky-500 rounded-full mr-5">
                <i class="fa-solid fa-video"></i>
            </div>
            <div class="text-white">
                <p class="text-lg font-bold mb-2">Total Video</p>
                <p>{{ count($video) }}</p>
            </div>
        </div>
        <div class="flex bg-gradient-to-r items-center from-blue-500 to-indigo-500 px-5 py-3 md:w-1/4 rounded-md shadow-md mb-5 md:mb-0">
            <div class="px-3 py-2 bg-white text-red-500 rounded-full mr-5">
                <i class="fa-regular fa-file-audio"></i>
            </div>
            <div class="text-white">
                <p class="text-lg font-bold mb-2">Total Audio</p>
                <p>{{ count($audio) }}</p>
            </div>
        </div>
        <div class="flex bg-gradient-to-r items-center from-rose-500 to-red-700 px-5 py-3 md:w-1/4 rounded-md shadow-md">
            <div class="px-3 py-2 bg-white text-teal-500 rounded-full mr-5">
                <i class="fa-sharp fa-solid fa-users"></i>
            </div>
            <div class="text-white">
                <p class="text-lg font-bold mb-2">Total User</p>
                <p>{{ count($user) }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
