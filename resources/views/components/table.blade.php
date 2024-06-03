<div class="d-flex justify-content-between mb-3">
    <div>
        <select class="form-control" wire:model.live = 'pagination'>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
    <div>
        <input type="text" wire:model.live = "search" class="form-control" placeholder="Buscar...">
    </div>

</div>

<div class="table-responsive">
    <table class="table table-sm table-striped table-borderless table-hover text-center rounded overflow-hidden">
        <thead class="thead-theme">
            <tr>
                {{$thead}}
            </tr>
        </thead>
        <tbody>
            {{$slot}}
        </tbody>
    </table>
</div>

<style>
    .thead-theme{
        background: #343a40;
        color: #ffff;
    }
</style>
