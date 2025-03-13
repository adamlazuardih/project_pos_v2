<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Produk;
use App\Models\Setting;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Models\PenjualanDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{

    public function index()
    {

        return view('penjualan.index');
    }

    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->id_member = null;
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->id_user = Auth::user()->id;
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function data()
    {
        $penjualan = Penjualan::orderBy('id_penjualan', 'DESC')->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return '<div class="text-right">Rp. ' . format_uang($penjualan->total_harga) . '</div>';
            })
            ->addColumn('diskon', function ($penjualan) {
                return $penjualan->diskon . ' %';
            })
            ->addColumn('bayar', function ($penjualan) {
                return '<div class="text-right">Rp. ' . format_uang($penjualan->bayar) . '</div>';
            })
            ->addColumn('diterima', function ($penjualan) {
                return '<div class="text-right">Rp. ' . format_uang($penjualan->diterima) . '</div>';
            })
            ->addColumn('nama_member', function ($penjualan) {
                $member = Member::find($penjualan->id_member);
                return $member ? $member->nama_member : 'Tanpa Member';
            })
            ->addColumn('kasir', function ($penjualan) {
                return $penjualan->user->name ?? '';
            })
            ->addColumn('aksi', function ($penjualan) {
                $setting = Setting::first();
                $detailButton = '<button onclick="showDetail(`' . route('penjualan.show', $penjualan->id_penjualan) . '`)" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></button>';
                $deleteButton = '<button onclick="deleteData(`' . route('penjualan.destroy', $penjualan->id_penjualan) . '`)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>';

                if ($setting->tipe_nota == 1) {
                    $notaButton = '<button onclick="notaKecil(`' . route('penjualan.cetak-nota', $penjualan->id_penjualan) . '`, `Nota Kecil`)" class="btn btn-success btn-xs"><i class="fa fa-download"></i></button>';
                } else {
                    $notaButton = '<button onclick="notaBesar(`' . route('penjualan.cetak-nota', $penjualan->id_penjualan) . '`, `Nota Besar`)" class="btn btn-success btn-xs"><i class="fa fa-download"></i></button>';
                }

                return $detailButton . ' ' . $deleteButton . ' ' . $notaButton;

                return '';
            })
            ->rawColumns(['aksi', 'total_harga', 'bayar', 'diterima'])
            ->make(true);
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-default">' . $detail->produk->kode_produk . '</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_jual', function ($detail) {
                return '<div class="text-right">Rp. ' . format_uang($detail->harga_jual) . '</div>';
            })
            ->addColumn('jumlah', function ($detail) {
                return $detail->jumlah;
            })
            ->addColumn('diskon', function ($detail) {
                return $detail->diskon . ' %';
            })
            ->addColumn('subtotal', function ($detail) {
                return '<div class="text-right">Rp. ' . format_uang($detail->subtotal) . '</div>';
            })
            ->rawColumns(['kode_produk', 'harga_jual', 'subtotal'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $penjualan = Penjualan::latest()->first();
        $penjualan->id_member = $request->id_member;
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total;
        $penjualan->diskon = $request->diskon;
        $penjualan->bayar = $request->bayar;
        $penjualan->diterima = $request->diterima;
        $penjualan->update();

        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            $produk->stok -= $item->jumlah;
            $produk->update();
        }

        session()->flash('success', 'Transaksi berhasil disimpan.');
        return redirect()->route('penjualan.index');
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();

        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
            }
            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function cetak_nota($id)
    {

        $transaksi = Penjualan::with(['penjualan_detail.produk', 'member'])->where('id_penjualan', $id)->first();
        $setting = Setting::first();
        $kasir = Auth::user();

        $diskon = $transaksi->total_harga * ($transaksi->diskon / 100);
        $diskon_nilai = $transaksi->total_harga - $diskon;

        if ($setting->tipe_nota == 1) {
            return view('penjualan.nota_kecil', compact('transaksi', 'setting', 'kasir', 'diskon'));
        } else {
            $pdf = Pdf::loadView('penjualan.nota_besar', compact('transaksi', 'kasir', 'setting', 'diskon'))->setPaper(0,0,609,440, 'portrait');
            return $pdf->stream('Nota Besar No.' . $transaksi->id_penjualan . '.pdf');
        }

        // return $transaksi;
    }
}
