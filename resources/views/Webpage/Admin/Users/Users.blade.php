<x-app-layout>
    @if (Auth::user()->role == 'admin')
    <div class="flex justify-end items-center sm:px-6 lg:px-8 mt-5">
        <button onclick="modalHandler()" class="px-7 py-2 bg-sky-500 text-white rounded">Add User</button>
    </div>
    @endif
    <div class="flex flex-col mt-5">
        <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-200 border-b">
                            <tr>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    #
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Name
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Email
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Role
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $loop->iteration }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $user->name }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $user->email }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $user->role == 'sub' ? 'Sub Admin' : 'Admin' }}
                                </td>
                                <td
                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap flex justify-center items-center">
                                    {{-- <button class="px-4 py-3 bg-blue-500 text-white text-sm rounded-full mr-3"><i class="fa-solid fa-pen-to-square"></i></button> --}}
                                    <button onclick="modalDelHandler(this)" class="px-4 py-3 bg-red-500 text-white text-sm rounded-full ml-3"
                                        data-url="{{ route('deleteUser',$user->id) }}"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{ $users->links() }}
    </div>
    <div class="bg-slate-800 bg-opacity-50 flex justify-center items-center absolute top-0 right-0 bottom-0 left-0"
        id="modal" style="display: none">
        <div class="bg-white px-8 py-7 rounded-md text-center relative w-[400px]">
            <form action="{{ route('addUser') }}" method="post" onsubmit="return validateForm()">
                <div class="absolute top-0 right-0 w-full text-right text-xl px-2">
                    <i class="fa-sharp fa-solid fa-xmark cursor-pointer" onclick="modalHandler()"></i>
                </div>
                @csrf
                <h1 class="text-xl mb-4 font-bold text-slate-500">Add User</h1>
                <div class="text-left">
                    <p class="text-sm">Name</p>
                    <input name="name" type="text" placeholder="Type Name"
                        class="w-full px-3 py-3 border focus:outline-none rounded-md " />
                    <p id='nameError' class="text-rose-500 mb-5"></p>
                    <p class="text-sm">Email</p>
                    <input name="email" type="email" placeholder="Type Email"
                        class="w-full px-3 py-3 border focus:outline-none rounded-md" />
                    <p id='emailError' class="text-rose-500 mb-5"></p>
                    <p class="text-sm">Password</p>
                    <input name="password" type="password" placeholder="Type Password"
                        class="w-full px-3 py-3 border focus:outline-none rounded-md" />
                    <p id='passwordError' class="text-rose-500 mb-5"></p>
                </div>
                <button type="submit" class="px-7 py-2 bg-sky-500 text-white rounded">Submit</button>
            </form>
        </div>
    </div>
    <div class="bg-slate-800 bg-opacity-50 flex justify-center items-center absolute top-0 right-0 bottom-0 left-0"
        id="modalDelete" style="display: none">
        <div class="bg-white px-8 py-7 rounded-md text-center relative w-[400px]">
            <div class="absolute top-0 right-0 w-full text-right text-xl px-2">
                <i class="fa-sharp fa-solid fa-xmark cursor-pointer" onclick="modalDelHandler()"></i>
            </div>
            <h1 class="text-xl mb-4 font-bold text-slate-500">Delete User</h1>
            <p class="text-sm mb-7 font-bold text-slate-500">Are you Sure?</p>
            <div class="flex justify-center">
                <a id="deletUserLink" href="" class="px-7 py-2 bg-red-500 text-white rounded mr-3">Delete</a>
                <button class="px-7 py-2 bg-sky-500 text-white rounded ml-3" onclick="modalDelHandler()">Cancel</button>
            </div>
        </div>
    </div>
    <script src="{{ url('/') }}/assets/js/jquery-3.6.3.min.js"></script>
    <script src="{{ url('/') }}/assets/js/Validation.js"></script>
    <script src="{{ url('/') }}/assets/js/toastr.js"></script>
    <script>
        @if(session('message'))
        toastr.success('{{ session('message') }}')
        @endif
    </script>
</x-app-layout>
