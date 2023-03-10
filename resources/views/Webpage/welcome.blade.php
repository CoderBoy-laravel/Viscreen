@push('css')
<link rel="stylesheet" href="{{ url('/') }}/assets/css/plyr.css">
@endpush
<x-app-layout>
    <div class="mt-8">
        <div class="text-xl mb-8 text-center">
            <p>Watch {{ $slug }} Files</p>
        </div>
        @if(checkTimeline() || (Auth::user() && Auth::user()->role == 'admin'))
        <div class="flex">
            <div class="w-2/3 min-w-2/3">
                <div class="media">
                @if (count($data) >= 1)
                @foreach ($data as $item)
                <div class="flex h-[200px] mb-3">
                    <a class="w-[200px] h-full shadow-md cursor-pointer" @if( $item->type == 'bulkvideo' ||  $item->type == 'bulkaudio')
                        onclick="viewBulkHandler('{{ url('/') }}',{{ json_encode(['file' => $item->file, 'link' => $item->link, 'thumb' => $item->thumb, 'title' => $item->title, 'description' => $item->description,'playlist' =>getPlayListFile($item->type,$item->file)]) }})"
                        @else
                        onclick="viewHandler('{{ url('/') }}',{{ json_encode(['file' => $item->file, 'link' => $item->link, 'thumb' => $item->thumb, 'title' => $item->title, 'description' => $item->description]) }})"
                        @endif>
                        @if ($item->type == 'video' || $item->type == 'bulkvideo')
                            @if ($item->thumb == null)
                                <img src="{{ url('/') }}{{ '/assets/Upload/video.png' }}" class="w-[200px] h-full shadow-md" />
                            @else
                                <img src="{{ url('/') }}{{ $item->thumb }}" class="w-[200px] h-full shadow-md"/>
                        @endif
                    </a>

                    @else
                    <img src="{{ url('/') }}/assets/Upload/music.png" class="w-[200px] h-full shadow-md"/>
                    @endif

                    @if( $item->type == 'bulkvideo' ||  $item->type == 'bulkaudio')
                        <div class="bg-white cursor-pointer ml-5 px-5 shadow-md w-full" onclick="viewBulkHandler('{{ url('/') }}',{{ json_encode(['file' => $item->file, 'link' => $item->link, 'thumb' => $item->thumb, 'title' => $item->title, 'description' => $item->description,'playlist' =>getPlayListFile($item->type,$item->file)]) }})">
                            <a class="text-lg font-bold py-5 block rounded-lg w-full">{{ $item->title }}</a>
                            <p class="text-sm w-full">{{ substr($item->description, 0, 200) }}{{ strlen($item->description) > 200 ? '...' : null}}</p>
                        </div>
                    @else
                        <div class="bg-white cursor-pointer ml-5 px-5 shadow-md w-full" onclick="viewHandler('{{ url('/') }}',{{ json_encode(['file' => $item->file, 'link' => $item->link, 'thumb' => $item->thumb, 'title' => $item->title, 'description' => $item->description]) }})">
                            <a class="text-lg font-bold py-5 block rounded-lg w-full">{{ $item->title }}</a>
                            <p class="text-sm w-full">{{ substr($item->description, 0, 200) }}{{ strlen($item->description) > 200 ? '...' : null}}</p>
                        </div>
                    @endif

                </div>
                @endforeach
                    @else
                    <p class="text-xl font-bold">No Data Found</p>
                @endif
                {{ $data->links() }}
                </div>

                <div class="articles mt-5">
                    @if (count($articles) >= 1)
                        @foreach ($articles as $item)
                            <div class="flex h-[200px] mb-3">
                                <a class="w-[200px] h-full shadow-md cursor-pointer"  href="{{route('new_detail',$item->id)}}">
                                @if (!$item->image)
                                    <img src="{{ '/assets/noimage.jpg' }}" class="w-[200px] h-full shadow-md" />
                                @else
                                    <img src="{{asset($item->image)}}" class="w-[200px] h-full shadow-md"/>
                                @endif
                                </a>
                                <div class="bg-white cursor-pointer ml-5 px-5 shadow-md w-full" >
                                    <a href="{{route('new_detail',$item->id)}}" class="text-lg mt-3 font-bold block rounded-lg w-full">{{ $item->title }}</a>
                                    <a style="background: #f1f1f1; display: inline-block; padding: 2px 10px; font-size: 0.85em; border-radius: 10px;" >{{ $item->category->title }}</a>
                                    <p class="text-sm w-full mt-3">{{ substr($item->description, 0, 200) }}{{ strlen($item->description) > 200 ? '...' : null}}</p>
                                </div>

                            </div>
                        @endforeach
                    @else
                        <p class="text-xl font-bold">No Data Found</p>
                    @endif
                    {{ $articles->links() }}
                </div>
            </div>
            <div class="w-1/3 px-10">
                <p class="pb-5 border-b">Category</p>
                <ul>
                    <li><a class="py-3 block px-5 bg-slate-50 w-full border-b hover:bg-white" href="{{ url('/') }}">All</a></li>
                    <li><a class="py-3 block px-5 bg-slate-50 w-full border-b hover:bg-white" class="" href="{{ url('/') }}/video">Video</a></li>
                    <li><a class="py-3 block px-5 bg-slate-50 w-full hover:bg-white" href="{{ url('/') }}/audio">Audio</a></li>
                </ul>

                <p class="pb-5 border-b mt-5">Articles</p>
                <ul>
                    @foreach($article_categories as $cate)
                    <li><a class="py-3 block px-5 bg-slate-50 w-full border-b hover:bg-white" href="{{ route('new_category',$cate->id) }}">{{$cate->title}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        @else
            <div >
                <div class="text-center text-red-500">
                    You aren't allowed to access at this time.<br> The content can access available in {{getAvaiableTime()}} (Timezone: {{Config::get('app.timezone')}})
                </div>
            </div>
        @endif
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
    @push('js')
    <script src="{{ url('/') }}/assets/js/jquery-3.6.3.min.js"></script>
    <script type="text/javascript" src="{{ url('/') }}/assets/js/plyr.js"></script>
    <script src="{{ url('/') }}/assets/js/toastr.js"></script>
    <script src="{{ url('/') }}/assets/js/show.js"></script>
    @endpush
</x-app-layout>
