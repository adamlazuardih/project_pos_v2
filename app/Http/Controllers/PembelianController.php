<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produk;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\PembelianDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    public function index()
    {
        $supplier = Supplier::orderBy('nama_supplier', 'ASC')->get();

        return view('pembelian.index', compact('supplier'));
    }

    public function data()
    {
        $pembelian = Pembelian::orderBy('id_pembelian', 'DESC')->get();

        return datatables()
            ->of($pembelian)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($pembelian) {
                return tanggal_indonesia($pembelian->created_at, false);
            })
            ->addColumn('supplier', function ($pembelian) {
                return $pembelian->supplier->nama_supplier;
            })
            ->addColumn('total_harga', function ($pembelian) {
                return '<div class="text-right">Rp. ' . format_uang($pembelian->total_harga) . '</div>';
            })
            ->addColumn('diskon', function ($pembelian) {
                return $pembelian->diskon . ' %';
            })
            ->addColumn('bayar', function ($pembelian) {
                return '<div class="text-right">Rp. ' . format_uang($pembelian->bayar) . '</div>';
            })
            ->addColumn('aksi', function ($pembelian) {
                return '

                <button onclick="showDetail(`' . route('pembelian.show', $pembelian->id_pembelian) . '`)" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></button>
                <button onclick="deleteData(`' . route('pembelian.destroy', $pembelian->id_pembelian) . '`)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                <button onclick="cetakInvoice(`' . route('pembelian.cetak-invoice', $pembelian->id_pembelian) . '`, `Invoice`)" class="btn btn-success btn-xs"><i class="fa fa-download"></i></button>

                ';
            })
            ->rawColumns(['aksi', 'total_harga', 'bayar'])
            ->make(true);
    }

    public function show($id)
    {
        $detail = PembelianDetail::with('produk')->where('id_pembelian', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-default">' . $detail->produk->kode_produk . '</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_beli', function ($detail) {
                return '<div class="text-right">Rp. ' . format_uang($detail->harga_beli) . '</div>';
            })
            ->addColumn('jumlah', function ($detail) {
                return $detail->jumlah;
            })
            ->addColumn('subtotal', function ($detail) {
                return '<div class="text-right">Rp. ' . format_uang($detail->subtotal). '</div>';
            })
            ->rawColumns(['kode_produk', 'harga_beli', 'subtotal'])
            ->make(true);
    }

    public function create($id)
    {

        $pembelian = new Pembelian();
        $pembelian->id_supplier = $id;
        $pembelian->total_item = 0;
        $pembelian->total_harga = 0;
        $pembelian->diskon = 0;
        $pembelian->bayar = 0;
        $pembelian->save();

        session(['id_pembelian' => $pembelian->id_pembelian]);
        session(['id_supplier' => $pembelian->id_supplier]);

        return redirect()->route('pembelian_detail.index');
    }

    public function store(Request $request)
    {
        $pembelian = Pembelian::latest()->first();
        $pembelian->total_item = $request->total_item;
        $pembelian->total_harga = $request->total;
        $pembelian->diskon = $request->diskon;
        $pembelian->bayar = $request->bayar;
        $pembelian->update();
        $user = Auth::user();

        $detail = PembelianDetail::where('id_pembelian', $pembelian->id_pembelian)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            $produk->stok += $item->jumlah;
            $produk->update();
        }

        session()->flash('success', 'Transaksi berhasil disimpan!');
        return redirect()->route('pembelian.index');
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::find($id);
        $detail = PembelianDetail::where('id_pembelian', $pembelian->id_pembelian)->get();

        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk && ($produk->stok - $item->jumlah) < 0) {
                return response()->json("['error' => 'Tidak dapat menghapus, cek Transaksi Aktif']", 400);
            }
        }

        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok -= $item->jumlah;
                $produk->update();
            }
            $item->delete();
        }

        $pembelian->delete();

        return response(null, 204);
    }

    public function cetak_invoice($id)
    {
        $pembelian = Pembelian::with(['pembelian_detail.produk'])->where('id_pembelian', $id)->first();
        $setting = Setting::first();
        $user = Auth::user();

        $diskon = $pembelian->total_harga * ($pembelian->diskon / 100);
        $diskon_nilai = $pembelian->total_harga - $diskon;

        $pdf = Pdf::loadView('pembelian.invoice_new', compact('pembelian', 'user', 'setting', 'diskon'));
        $pdf->setPaper(0,0,609,440, 'portrait');
        return $pdf->stream('Invoice Beli No.'. $pembelian->id_pembelian .'.pdf');
    }

}
