<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config(app . name) }}</title>

    @include('components.layouts.partials.styles')

    @livewireStyles
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
        </div>

        @include('components.layouts.partials.navbar')

        @include('components.layouts.partials.sidebar')

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">{{ $title }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
                                <li class="breadcrumb-item active">{{ $title }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">

                    @livewire('alerts-component')
                    {{ $slot }}
                </div>
            </section>
        </div>

        @include('components.layouts.partials.footer')
    </div>

    @include('components.layouts.partials.scripts')

    <script>
        document.addEventListener('livewire:init', () => {

            Livewire.on('close-modal', (idModal) => {
                $('#' + idModal).modal('hide');
            })
            Livewire.on('open-modal', (idModal) => {
                $('#' + idModal).modal('show');
            })

            Livewire.on('delete', (e) => {

                // alert(e.id+'-'+e.eventName)

                Swal.fire({
                    title: "Estas seguro?",
                    text: "No podras revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, Eliminar!",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch(e.eventName, {id: e.id})
                    }
                });
            })
        })
    </script>

    @livewireScripts
</body>

</html>
