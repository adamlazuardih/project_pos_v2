<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class PembelianDetailController extends Controller
{
    public function index()
    {
        $id_pembelian = session('id_pembelian');
        $id_supplier = session('id_supplier');
        $produk = Produk::where('id_supplier', $id_supplier)->orderBy('nama_produk')->get();
        $supplier = Supplier::find(session('id_supplier'));
        $diskon = Pembelian::find($id_pembelian)->diskon ?? 0;

        // return session('id_supplier');
        if (! $supplier) {
            abort(404);
        }

        return view('pembelian_detail.index', compact('id_pembelian', 'produk', 'supplier', 'diskon'));
    }

    public function data($id)
    {
        $detail = PembelianDetail::with('produk')->where('id_pembelian', $id)->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-default">' . $item->produk['kode_produk'] . '</span>';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_beli'] = '<div class="text-right">Rp. ' . format_uang($item->harga_beli) . '</div>';
            $row['jumlah'] = '<input type="number" class="form-control-xs text-center edit-quantity" data-id="' . $item->id_pembelian_detail . '" value="' . $item->jumlah . '">';
            $row['subtotal'] = '<div class="text-right">Rp. ' . format_uang($item->subtotal) . '</div>';
            $row['aksi'] = '<button type="button" onclick="deleteData(`' . route('pembelian_detail.destroy', $item->id_pembelian_detail) . '`)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Hapus</button>';
            $data[] = $row;

            $total += $item->harga_beli * $item->jumlah;
            $total_item += $item->jumlah;
        }

        $data[] = [
            'kode_produk' => '
                <div class="total hide">' . $total . '</div>
                <div class="total_item hide">' . $total_item . '</div>',
            'nama_produk' => '',
            'harga_beli' => '',
            'jumlah' => '',
            'subtotal' => '',
            'aksi' => ''
        ];

        // return $data;

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah', 'harga_beli', 'subtotal'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data gagal di simpan', 400);
        }

        $detail = PembelianDetail::where('id_pembelian', $request->id_pembelian)->where('id_produk', $produk->id_produk)->first();
        if($detail){
            $detail->jumlah += 1;
            $detail->subtotal = $detail->harga_beli * $detail->jumlah;
            $detail->save();
        } else {
            $detail = new PembelianDetail();
            $detail->id_pembelian = $request->id_pembelian;
            $detail->id_produk = $produk->id_produk;
            $detail->harga_beli = $produk->harga_beli;
            $detail->jumlah = 1;
            $detail->subtotal = $produk->harga_beli;
            $detail->save();
        }
        return response()->json($detail);
    }

    public function update(Request $request, $id)
    {
        $detail = PembelianDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_beli * $request->jumlah;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = PembelianDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function load_form($diskon, $total)
    {
        $bayar = $total - ($diskon / 100 * $total);
        $data = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp'=> format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
        ];

        return response()->json($data, 200);
    }

}
