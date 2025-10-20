<div wire:click='resetNotification'>
    @if ($this->notifications->count())
        @foreach ($this->notifications as $notification)
            <a wire:click="readNotification('{{ $notification->id }}')" href="{{ $notification->data['url'] }}"
                class="dropdown-item {{ !$notification->read_at ? 'bg-info' : 'bg-light' }}">

                <!-- Message Start -->
                <div class="media">
                    <img src="{{ asset('dist/img/notification.png') }}" alt="User Avatar"
                        class="img-size-50 mr-3 img-circle">
                    <div class="media-body">
                        <h3 class="dropdown-item-title">
                            {{ $notification->data['title'] }}
                            <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                        </h3>
                        <p class="text-sm">{{ $notification->data['message'] }}</p>
                        <p class="text-sm text-muted {{ !$notification->read_at ? 'text-white' : 'text-purple' }}">
                            <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
        @endforeach

        <button type="button" wire:click="incrementCount" class="dropdown-item dropdown-footer">
            Ver más notificaciones
        </button>
    @else
        <div class="p-3 text-center">
            No hay notificaciones
        </div>
    @endif
</div>
