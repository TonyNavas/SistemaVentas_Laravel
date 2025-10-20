<div>
    <x-card cardTitle="Detalles del producto">
        <x-slot:cardTools>
            <a href="{{ route('product.index') }}" class="btn btn-primary">
                <span>
                    <i class="fas fa-arrow-alt-circle-left"></i>
                    Regresar
                </span>
            </a>
        </x-slot:cardTools>
        <!-- Default box -->
        <div class="card card-solid">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-5">
                        <div class="col-12">
                            <img src="{{$product->imagen}}" class="product-image rounded image-fluid" alt="Product Image">
                        </div>

                    </div>
                    <div class="col-12 col-sm-7">
                        <h3 class="my-3">{{$product->name}}</h3>
                        <p>{{$product->desc}}</p>

                        <hr>

                        <div class="row">
                            <!-- Caja stock -->
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-box-open"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Stock</span>
                                        <span class="info-box-number">{!! $product->stockLabel !!}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                            </div>

                            <!-- Caja stock minimo-->
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-box-open"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Stock minimo</span>
                                        <span class="info-box-number">
                                            <span class="badge badge-success">{{$product->stock_minimo}}</span>
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                            </div>

                            <!-- Caja categoria -->
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-th-large"></i>
                                    </span>
                                    <div class="info-box-content">

                                        <span class="info-box-text">Categoria</span>
                                        <span class="info-box-number">{{$product->category->name}}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                            </div>

                            <!-- Caja estado -->
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-toggle-on"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Estado</span>
                                        <span class="info-box-number">{!! $product->activeLabel !!}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                            </div>

                            <!-- Caja codigo barras -->
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-barcode"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Codigo barras</span>
                                        <span class="info-box-number">{{$product->codigo_barras}}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                            </div>

                            <!-- Caja fecha vencimiento -->
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-calendar-times"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Fecha vencimiento</span>
                                        <span class="info-box-number">{{ \Carbon\Carbon::parse($product->fecha_vencimiento)->diffForHumans() }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                            </div>

                            <!-- Caja fecha creacion -->
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-calendar-plus"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Fecha creacion</span>
                                        <span class="info-box-number">{{$product->created_at->diffForHumans()}}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="bg-lightblue py-2 px-3 mt-4 col-md-6">
                                <h2 class="mb-0">
                                    {!! $product->precio !!}
                                </h2>
                                <h4 class="mt-0">
                                    <small>Precio venta </small>
                                </h4>
                            </div>
                            <div class="bg-gray py-2 px-3 mt-4 col-md-6">
                                <h2 class="mb-0">
                                    <b>C${{number_format($product->precio_compra, 2,',','.')}}</b>
                                </h2>
                                <h4 class="mt-0">
                                    <small>Precio compra</small>
                                </h4>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </x-card>
</div>
