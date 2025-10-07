@props(['item' => null, 'width' => '50', 'height' => '45', 'position' => '', 'class' => '', 'style' => ''])

<img src="{{$item->image ? Storage::url('public/'.$item->image->url) : asset('noimage.jpg')}}" class="rounded {{$position}} {{$class}}"
    width="{{$width}}" height="{{$height}}" style="{{$style}}">
