@props(['cardTitle' => '', 'cardTools' => '', 'cardFooter' => ''])

<div class="card">
    <div class="card-header">
        <div class="card-title">{{$cardTitle}}</div>
        <div class="card-tools">
            {{$cardTools}}
        </div>
    </div>
        <div class="card-body">
            {{$slot}}
        </div>
        <div class="card-footer">
            <div>
                {{$cardFooter}}
            </div>
        </div>

</div>
