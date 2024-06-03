@props(['item' => null, 'width' => '50', 'height' => '45', 'position' => '', 'class' => ''])

<img src="{{$item->image ? Storage::url('public/'.$item->image->url) : asset('noimage.jpg')}}" class="rounded {{$position}} {{$class}}"
    width="{{$width}}" height="{{$height}}">
