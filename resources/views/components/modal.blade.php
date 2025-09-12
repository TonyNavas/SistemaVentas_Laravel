@props(['modalTitle' => '', 'modalId' => '', 'modalSize' => '', 'modalStyle' => ''])

<div wire:ignore.self class="modal fade" id="{{ $modalId }}" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog {{$modalSize}}">
        <div class="modal-content">
            <div class="modal-header {{$modalStyle}}">
                <h5 class="modal-title" id="staticBackdropLabel">{{ $modalTitle }}</h5>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
