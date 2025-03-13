<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use App\Http\Controllers\Controller;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');
        $supplier = Supplier::all()->pluck('nama_supplier', 'id_supplier');
        return view('produk.index', compact('kategori', 'supplier'));
    }

    public function data()
    {
        $produk = Produk::leftJoin('kategori', 'kategori.id_kategori', 'produk.id_kategori')
                        ->leftJoin('supplier', 'supplier.id_supplier', 'produk.id_supplier')
                        ->select('produk.*', 'nama_kategori', 'nama_supplier')
                        ->orderBy('kode_produk', 'ASC')->get();

        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '<input type="checkbox" name="id_produk[]" value="' . $produk->id_produk . '">';
            })
            ->addColumn('kode_produk', function ($produk) {
                return '<span class="label label-default">' . $produk->kode_produk . '</span>';
            })
            ->addColumn('harga_beli', function ($produk) {
                return '<div class="text-right">Rp. ' . format_uang($produk->harga_beli) . '</div>';
            })
            ->addColumn('harga_jual', function ($produk) {
                return '<div class="text-right">Rp. ' . format_uang($produk->harga_jual). '</div>';
            })
            ->addColumn('stok', function ($produk) {
                return format_uang($produk->stok);
            })
            ->addColumn('diskon', function ($produk) {
                return $produk->diskon . ' %';
            })
            ->addColumn('aksi', function ($produk) {
                return '
                <button type="button" onclick="editForm(`' . route('produk.update', $produk->id_produk) . '`)" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></button>
                <button type="button" onclick="deleteData(`' . route('produk.destroy', $produk->id_produk) . '`)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                ';
            })
            ->rawColumns(['aksi', 'kode_produk', 'select_all', 'harga_beli', 'harga_jual'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $produk = Produk::latest()->first() ?? new Produk();
        $request['kode_produk'] = mt_rand(1000000000, 9999999999);
        $produk = Produk::create($request->all());

        // $produk = Produk::leftJoin('kategori', 'kategori.id_kategori', 'produk.id_kategori')->select('produk.*', 'nama_kategori')->latest()->first();
        // $kodeTersedia = Produk::pluck('kode_produk')->map(function ($kode){
        //     return(int) substr($kode, 1);
        // })->sort()->values();

        // $kodeBaru = 1;
        // foreach($kodeTersedia as $code){
        //     if($code == $kodeBaru){
        //         $kodeBaru++;
        //     } else {
        //         break;
        //     }
        // }

        // $produk = new Produk();
        // $produk->kode_produk = 'P' . tambah_nol_didepan($kodeBaru, 6);
        // $produk->nama_produk = $request->nama_produk;
        // $produk->id_kategori = $request->id_kategori;
        // $produk->merk = $request->merk;
        // $produk->harga_beli = $request->harga_beli;
        // $produk->harga_jual = $request->harga_jual;
        // $produk->diskon = $request->diskon;
        // $produk->stok = $request->stok;
        // $produk->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $produk = Produk::find($id);
        return response()->json($produk);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::find($id);
        $produk->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_produk as $id) {
            $produk = Produk::find($id);
            $produk->delete();
        }

        return response(null, 204);
    }

    public function cetakBarcode(Request $request)
    {
        $dataproduk = array();
        foreach ($request->id_produk as $id) {
            $produk = Produk::find($id);
            $dataproduk[] = $produk;
        }

        $no = 1;
        $pdf = PDF::loadView('produk.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('produk.pdf');
    }
}
