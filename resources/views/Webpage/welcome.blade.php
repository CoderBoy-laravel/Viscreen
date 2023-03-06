@push('css')
<link rel="stylesheet" href="{{ url('/') }}/assets/css/plyr.css">
@endpush
<x-app-layout>
    <div class="mt-8">
        <div class="text-xl mb-8 text-center">
            <p>Watch {{ $slug }} Files</p>
        </div>
        <div class="flex">
            <div class="w-2/3 min-w-2/3">
                @if (count($data) >= 1)
                @foreach ($data as $item)
                <div class="flex h-[200px] mb-3">
                    @if ($item->type == 'video')
                        @if ($item->thumb == null)
                            <img src="{{ url('/') }}{{ '/assets/Upload/video.png' }}" class="w-[200px] h-full shadow-md" />
                        @else
                            <img src="{{ url('/') }}{{ $item->thumb }}" class="w-[200px] h-full shadow-md"/>
                    @endif

                    @else
                    <img src="{{ url('/') }}/assets/Upload/music.png" class="w-[200px] h-full shadow-md"/>
                    @endif
                    <div class="bg-white cursor-pointer ml-5 px-5 shadow-md w-full" onclick="viewHandler('{{ url('/') }}',{{ json_encode(['file' => $item->file, 'link' => $item->link, 'thumb' => $item->thumb, 'title' => $item->title, 'description' => $item->description]) }})">
                        <a class="text-lg font-bold py-5 block rounded-lg w-full">{{ $item->title }}</a>
                        <p class="text-sm w-full">{{ substr($item->description, 0, 200) }}{{ strlen($item->description) > 200 ? '...' : null}}</p>
                    </div>
                </div>
                @endforeach
                    @else
                    <p class="text-xl font-bold">No Data Found</p>
                @endif
                {{ $data->links() }}
            </div>
            <div class="w-1/3 px-10">
                <p class="pb-5 border-b">Category</p>
                <ul>
                    <li><a class="py-3 block px-5 bg-slate-50 w-full border-b hover:bg-white" href="{{ url('/') }}">All</a></li>
                    <li><a class="py-3 block px-5 bg-slate-50 w-full border-b hover:bg-white" class="" href="{{ url('/') }}/video">Video</a></li>
                    <li><a class="py-3 block px-5 bg-slate-50 w-full hover:bg-white" href="{{ url('/') }}/audio">Audio</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="bg-slate-800 bg-opacity-50 absolute top-0 right-0 bottom-0 left-0" id="modalSee" style="display: none">
        <div class="absolute top-5 right-5 w-full text-right text-xl px-2">
            <p class="p-5 px-7 bg-[#0000007a] inline-block rounded-full text-white active:bg-black hover:bg-[#000000ad] cursor-pointer" onclick="viewHandler(null, null)">
                <i class="fa-sharp fa-solid fa-xmark cursor-pointer"></i>
            </p>
        </div>
        <div class="flex items-stretch px-8 py-7 rounded-md text-center relative top-1/2 -translate-y-1/2 w-full lg:w-7xl">
            <div class="w-3/5" id="player">

            </div>
            <div class="w-2/5 bg-white px-5 py-10 text-left">
                <p class="text-3xl font-bold pb-5 border-b" id="seeTitle"></p>
                <p class="text-base" id="seeDescription"></p>
            </div>
        </div>
    </div>
    @push('js')
    <script src="{{ url('/') }}/assets/js/jquery-3.6.3.min.js"></script>
    <script type="text/javascript" src="{{ url('/') }}/assets/js/plyr.js"></script>
    <script src="{{ url('/') }}/assets/js/toastr.js"></script>
    <script src="{{ url('/') }}/assets/js/show.js"></script>
    @endpush
</x-app-layout>
