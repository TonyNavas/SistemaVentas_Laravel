<?php

namespace App\Livewire\Home;

use App\Models\Item;
use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\OrderDetail;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

#[Title('Inicio')]
class Inicio extends Component
{
    // Ventas hoy
    public $ventasHoy = 0;
    public $totalventasHoy = 0;
    public $articulosHoy = 0;
    public $productosHoy = 0;

    // Ventas mes grafica
    public $listTotalVentasMes = '';

    // Cajas de reportes
    public $cantidadVentas = 0;
    public $totalVentas = 0;
    public $cantidadArticulos = 0;
    public $cantidadProductos = 0;

    public $cantidadProducts = 0;
    public $cantidadStock = 0;
    public $cantidadCategories = 0;
    public $cantUsuarios = 0;

    // Productos mas vendidos y recientes report
    public $productosMasVendidosHoy = 0;
    public $productosMasVendidosMes = 0;
    public $productosMasVendidos = 0;
    public $productosRecienAgregados = 0;

    public function sales_today()
    {
        $today = date('Y-m-d');

        $this->ventasHoy = Sale::whereDate('fecha', $today)->count();
        $this->totalventasHoy = Sale::whereDate('fecha', $today)->sum('total');
        $this->articulosHoy = OrderDetail::whereDate('fecha', $today)->sum('quantity');
        $this->productosHoy = OrderDetail::whereDate('fecha', $today)->distinct('product_id')->count('product_id');
    }

    public function VentasMes()
    {
        for ($i = 1; $i < 12; $i++) {
            $this->listTotalVentasMes .= Sale::whereMonth('fecha', '=', $i)->sum('total') . ',';
        }
    }

    public function boxesReport()
    {
        $this->cantidadVentas = Sale::whereYear('fecha', '=', date('Y'))->count();
        $this->totalVentas = Sale::whereYear('fecha', '=', date('Y'))->sum('total');
        $this->cantidadArticulos = OrderDetail::whereYear('fecha', '=', date('Y'))->sum('quantity');
        $this->cantidadProductos = OrderDetail::whereYear('fecha', '=', date('Y'))->distinct('product_id')->count('product_id');

        $this->cantidadProducts = Product::count();
        $this->cantidadStock = Product::sum('stock');
        $this->cantidadCategories = Category::count();
        $this->cantUsuarios = User::count();
    }

    // Cargar propiedades productos mas vendidos
    public function set_products_report()
    {
        $this->productosMasVendidosHoy = $this->table_products_report(1);
        $this->productosMasVendidosMes = $this->table_products_report(0,1);
        $this->productosMasVendidos = $this->table_products_report();
        $this->productosRecienAgregados = Product::take(5)->orderBy('id', 'desc')->get();
    }

    // Consulta productos mas vendidos
    public function table_products_report($filtrarDia = 0, $filtrarMes = 0)
    {
        $productsQuery = OrderDetail::select(
            DB::raw('MIN(order_details.id) as id'),
            DB::raw('MIN(order_details.name) as name'),
            DB::raw('MIN(order_details.image) as image'),
            DB::raw('MIN(order_details.price) as price'),
            DB::raw('MIN(order_details.quantity) as quantity'),
            'order_details.product_id',
            DB::raw('SUM(order_details.quantity) as total_quantity')
        )
            ->whereYear('order_details.created_at', date("Y"));

        if ($filtrarDia) {
            $productsQuery = $productsQuery->whereDate('order_details.fecha', date('Y-m-d'));
        }
        if ($filtrarMes) {
            $productsQuery = $productsQuery->whereMonth('order_details.fecha', date('m'));
        }


        $productsQuery = $productsQuery->groupBy('order_details.product_id')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->get();

        return $productsQuery;
    }

    public function render()
    {
        $this->sales_today();
        $this->VentasMes();
        $this->boxesReport();
        $this->set_products_report();

        return view('livewire.home.inicio');
    }
}
