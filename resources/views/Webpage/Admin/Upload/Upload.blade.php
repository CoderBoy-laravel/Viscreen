@push('css')
<link rel="stylesheet" href="{{ url('/') }}/assets/css/plyr.css">
@endpush
<x-app-layout>
    <div class="flex justify-between items-center sm:px-6 lg:px-8 mt-5">
        <select class="px-7 py-2 rounded" onchange="filterHandler(this, '{{ url('/') }}')">
            <option @if ($selected == 'video')
            selected
            @endif value="video">Video</option>
            <option @if ($selected == 'audio')
            selected
        @endif value="audio">Audio</option>
        </select>
        <div>
        <button onclick="bulkModalHandler()" class="px-7 py-2 bg-blue-500 text-white rounded">Bulk upload</button>
        <button onclick="modalHandler()" class="px-7 py-2 bg-sky-500 text-white rounded">Upload File</button>
        </div>
    </div>
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
                                    Thumbnail
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Title
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Description
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    @if ($item->thumb == null && $item->type == 'audio')
                                    <img src="{{ url('/') }}{{ '/assets/Upload/music.png' }}" class="w-11" />
                                    @else
                                    @if ($item->thumb == null)
                                    <img src="{{ url('/') }}{{ '/assets/Upload/video.png' }}" class="w-20" />
                                    @else
                                    <img src="{{ url('/') }}{{ $item->thumb }}" class="w-32" />
                                    @endif
                                    @endif
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ substr($item->title, 0, 50) }}{{ strlen($item->title) > 50 ? '...' : null}}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ substr($item->description, 0, 50) }}{{ strlen($item->description) > 50 ? '...' : null}}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap text-right">
                                    @if($item->type == 'video'|| $item->type == 'audioo')
                                        <button class="px-4 py-3 bg-blue-500 text-white text-sm rounded-full ml-3"
                                        onclick="viewHandler('{{ url('/') }}',{{ json_encode(['file' => $item->file, 'link' => $item->link, 'thumb' => $item->thumb, 'title' => $item->title, 'description' => $item->description]) }})"><i
                                            class="fa-solid fa-play"></i></button>
                                            @if ($item->user_id == Auth::user()->id)
                                            <button class="px-4 py-3 bg-sky-400 text-white text-sm rounded-full ml-3"
                                                onclick="editHandler({{ json_encode(['id' => $item->id, 'link' => $item->link, 'type' => $item->type, 'title' => $item->title, 'description' => $item->description]) }})"><i
                                                    class="fa-solid fa-pen-to-square"></i></button>
                                            <button onclick="modalDelHandler(this)" data-url="{{ route('deleteUpload',$item->id) }}" class="px-4 py-3 bg-red-500 text-white text-sm rounded-full ml-3"><i
                                                 class="fa-solid fa-trash"></i></button>
                                            @endif
                                    @else
                                        <button class="px-4 py-3 bg-blue-500 text-white text-sm rounded-full ml-3"
                                                onclick="viewBulkHandler('{{ url('/') }}',{{ json_encode(['file' => $item->file, 'link' => $item->link, 'thumb' => $item->thumb, 'title' => $item->title, 'description' => $item->description,'playlist' =>getPlayListFile($item->type,$item->file)]) }})"><i
                                                class="fa-solid fa-play"></i></button>
                                        @if ($item->user_id == Auth::user()->id)
                                            <button class="px-4 py-3 bg-sky-400 text-white text-sm rounded-full ml-3"
                                                    onclick="editBulkHandler({{ json_encode(['id' => $item->id, 'link' => $item->link, 'type' => $item->type,'file' => $item->file, 'title' => $item->title, 'description' => $item->description]) }})"><i
                                                    class="fa-solid fa-pen-to-square"></i></button>
                                            <button onclick="modalBulkDelHandler(this)" data-url="{{ route('deleteBulkUpload',$item->id) }}" class="px-4 py-3 bg-red-500 text-white text-sm rounded-full ml-3"><i
                                                    class="fa-solid fa-trash"></i></button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="bg-slate-800 bg-opacity-50 flex justify-center items-center fixed top-0 right-0 bottom-0 left-0"
        id="modal" style="display: none">
        <div class="bg-white px-8 py-7 rounded-md text-center relative w-full md:w-[600px]">
            <form data-url="{{ route('addFile') }}" method="POST" id="fileUpload" enctype="multipart/form-data">
                <div class="absolute top-0 right-0 w-full text-right text-xl px-2">
                    <i class="fa-sharp fa-solid fa-xmark cursor-pointer" onclick="modalHandler()"></i>
                </div>
                @csrf
                <h1 class="text-xl mb-4 font-bold text-slate-500">Upload File</h1>
                <div class="text-left">
                    <select class="w-full px-3 py-3 rounded-md mb-5" name="type">
                        <option value="video">Video</option>
                        <option value="audio">Audio</option>
                    </select>
                    <div class="flex justify-between items-center">
                        <p class="text-sm" id="typeChoose">File</p>
                        <div class="flex items-center">
                            <button type="button" class="px-3 py-1 bg-slate-100 border hover:bg-white"
                                onclick="typeChoose(this, 'file')">File</button>
                            <button type="button" class="px-3 py-1 bg-slate-100 border hover:bg-white"
                                onclick="typeChoose(this, 'link')">Link</button>
                        </div>
                    </div>
                    <input type="file" name="file" class="w-full px-3 py-3 rounded-md border" id="FileChoose" />
                    <p id='fileError' class="text-rose-500 mb-5"></p>
                    <p class="text-sm">Title</p>
                    <textarea name="title" id="title" class="border w-full"></textarea>
                    <p id='titleError' class="text-rose-500 mb-5"></p>
                    <p class="text-sm">Description</p>
                    <textarea name="description" id="description" class="border w-full"></textarea>
                    <p id='descriptionError' class="text-rose-500 mb-5"></p>
                    <progress class="progress w-full mb-8" value="0" max="100" style="display: none"></progress>
                </div>
                <button type="submit" class="px-7 py-2 bg-sky-500 text-white rounded">Submit</button>
            </form>
        </div>
    </div>
    <div class="bg-slate-800 bg-opacity-50 flex justify-center items-center fixed top-0 right-0 bottom-0 left-0"
        id="modalEdit" style="display: none">
        <div class="bg-white px-8 py-7 rounded-md text-center relative w-full md:w-[600px]">
            <form data-url="{{ route('editFile') }}" method="POST" id="editUpload" enctype="multipart/form-data">
                <input id="editId" name="id" hidden readonly />
                <div class="absolute top-0 right-0 w-full text-right text-xl px-2">
                    <i class="fa-sharp fa-solid fa-xmark cursor-pointer" onclick="editHandler()"></i>
                </div>
                @csrf
                <h1 class="text-xl mb-4 font-bold text-slate-500">Upload File</h1>
                <div class="text-left">
                    <select class="w-full px-3 py-3 rounded-md mb-5" name="type" id="editSelect">
                        <option value="video">Video</option>
                        <option value="audio">Audio</option>
                    </select>
                    <div class="flex justify-between items-center">
                        <p class="text-sm" id="edittypeChoose">File</p>
                        <div class="flex items-center">
                            <button type="button" class="px-3 py-1 bg-slate-100 border hover:bg-white"
                                onclick="editTypeChoose(this, 'file')">File</button>
                            <button type="button" class="px-3 py-1 bg-slate-100 border hover:bg-white"
                                onclick="editTypeChoose(this, 'link')">Link</button>
                        </div>
                    </div>
                    <input type="file" name="file" class="w-full px-3 py-3 rounded-md border" id="editFileChoose" />
                    <p id='editfileError' class="text-rose-500 mb-5"></p>
                    <p class="text-sm">Title</p>
                    <textarea name="title" id="edittitle" class="border w-full"></textarea>
                    <p id='edittitleError' class="text-rose-500 mb-5"></p>
                    <p class="text-sm">Description</p>
                    <textarea name="description" id="editdescription" class="border w-full"></textarea>
                    <p id='editdescriptionError' class="text-rose-500 mb-5"></p>
                    <progress class="progress w-full mb-8" value="0" max="100" style="display: none"></progress>
                </div>
                <button type="submit" class="px-7 py-2 bg-sky-500 text-white rounded">Submit</button>
            </form>
        </div>
    </div>
    <div class="bg-slate-800 bg-opacity-50 fixed top-0 right-0 bottom-0 left-0" id="modalSee" style="display: none">
        <div class="fixed top-5 right-5 w-full text-right text-xl px-2">
            <p class="p-5 px-7 bg-[#0000007a] inline-block rounded-full text-white active:bg-black hover:bg-[#000000ad] cursor-pointer" onclick="viewHandler(null, null)">
                <i class="fa-sharp fa-solid fa-xmark cursor-pointer"></i>
            </p>
        </div>
        <div class="flex items-stretch px-8 py-7 rounded-md text-center fixed top-1/2 -translate-y-1/2 w-full lg:w-7xl">
            <div class="w-3/5" id="player">

            </div>
            <div class="w-2/5 bg-white px-5 py-10 text-left">
                <p class="text-3xl font-bold pb-5 border-b" id="seeTitle"></p>
                <p class="text-base" id="seeDescription"></p>
                <p class="text-base" id="playList"></p>
            </div>
        </div>
    </div>
    <div class="bg-slate-800 bg-opacity-50 flex justify-center items-center absolute top-0 right-0 bottom-0 left-0"
        id="modalDelete" style="display: none">
        <div class="bg-white px-8 py-7 rounded-md text-center relative w-[400px]">
            <div class="absolute top-0 right-0 w-full text-right text-xl px-2">
                <i class="fa-sharp fa-solid fa-xmark cursor-pointer" onclick="modalDelHandler()"></i>
            </div>
            <h1 class="text-xl mb-4 font-bold text-slate-500">Delete File</h1>
            <p class="text-sm mb-7 font-bold text-slate-500">Are you Sure?</p>
            <div class="flex justify-center">
                <a id="deletUploadLInk" href="" class="px-7 py-2 bg-red-500 text-white rounded mr-3">Delete</a>
                <button class="px-7 py-2 bg-sky-500 text-white rounded ml-3" onclick="modalDelHandler()">Cancel</button>
            </div>
        </div>
    </div>


    <div class="bg-slate-800 bg-opacity-50 flex justify-center items-center fixed top-0 right-0 bottom-0 left-0"
         id="bulk_modal" style="display: none">
        <div class="bg-white px-8 py-7 rounded-md text-center relative w-full md:w-[600px]">
            <form data-url="{{ route('addBulkFile') }}" method="POST" id="frmBulkUpload">
                <input type="hidden" name="id" id="bulk_id" value=""/>
                <div class="absolute top-0 right-0 w-full text-right text-xl px-2">
                    <i class="fa-sharp fa-solid fa-xmark cursor-pointer" onclick="bulkModalHandler()"></i>
                </div>
                @csrf
                <h1 class="text-xl mb-4 font-bold text-slate-500">Bulk Upload</h1>
                <div class="text-left">
                    <select class="w-full px-3 py-3 rounded-md mb-5" name="type" id="bulk_type" required>
                        <option value="bulkvideo">Video</option>
                        <option value="bulkaudio">Audio</option>
                    </select>
                    <div class="justify-between items-center">
                        <p class="text-sm">Directory Path on Server</p>
                        <div class=" items-center">
                            <input type="text" name="file" id="bulk_file" class="border w-full" required></input>
                            <div style="color: #ccc; font-size: 0.85em">Root directory <b>/public/assets/Upload/</b></div>
                        </div>
                    </div>
                    <p id='fileError' class="text-rose-500 mb-5"></p>
                    <p class="text-sm">Title</p>
                    <textarea name="title" id="bulk_title" class="border w-full" required></textarea>
                    <p id='titleError' class="text-rose-500 mb-5"></p>
                    <p class="text-sm">Description</p>
                    <textarea name="description" id="bulk_description" class="border w-full" required></textarea>
                    <p id='descriptionError' class="text-rose-500 mb-5"></p>
                    <progress class="progress w-full mb-8" value="0" max="100" style="display: none"></progress>
                </div>
                <button type="submit" class="px-7 py-2 bg-sky-500 text-white rounded">Submit</button>
                <button type="button" class="px-7 py-2 bg-gray-500 text-white rounded" onclick="bulkModalHandler()">Cancel</button>
                <div id="bulk_message" style="color: red; margin-top: 15px;"></div>
            </form>
        </div>
    </div>


    @push('js')
    <script src="{{ url('/') }}/assets/js/jquery-3.6.3.min.js"></script>
    <script type="text/javascript" src="{{ url('/') }}/assets/js/plyr.js"></script>
    <script src="{{ url('/') }}/assets/js/toastr.js"></script>
    <script src="{{ url('/') }}/assets/js/Upload.js"></script>
    <script>
        @if(session('message'))
        toastr.success('{{ session('message') }}')
        @endif
    </script>
    @endpush
</x-app-layout>
