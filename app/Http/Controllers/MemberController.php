<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('member.index');
    }

    public function data()
    {
        $member = Member::orderBy('kode_member', 'ASC')->get();

        return datatables()
            ->of($member)
            ->addIndexColumn()
            ->addColumn('select_all', function ($member) {
                return '<input type="checkbox" name="id_member[]" value="' . $member->id_member . '">';
            })
            ->addColumn('kode_member', function ($member) {
                return '<span class="label label-default">' . $member->kode_member . '</span>';
            })
            ->addColumn('aksi', function ($member) {
                return '
                <button type="button" onclick="editForm(`' . route('member.update', $member->id_member) . '`)" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></button>
                <button type="button" onclick="deleteData(`' . route('member.destroy', $member->id_member) . '`)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                ';
            })
            ->rawColumns(['aksi', 'kode_member', 'select_all'])
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
        // $member = Member::latest()->first() ?? new Member();
        // $request['kode_member'] = tambah_nol_didepan((int)$member->id_member, 6);
        // $member = Member::create($request->all());

        $member = Member::latest()->first();
        $kodeTersedia = Member::pluck('kode_member')->map(function ($kode){
            return(int) substr($kode, 1);
        })->sort()->values();

        $kodeBaru = 1;
        foreach($kodeTersedia as $code){
            if($code == $kodeBaru){
                $kodeBaru++;
            } else {
                break;
            }
        }

        $member = new Member();
        $member->kode_member = tambah_nol_didepan($kodeBaru, 5);
        $member->nama_member = $request->nama_member;
        $member->alamat = $request->alamat;
        $member->telepon = $request->telepon;
        $member->save();

        return response()->json('Data berhasil disimpan', 200);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $produk = Member::find($id);
        return response()->json($produk);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $member = Member::find($id)->update($request->all());

        return response()->json('Data berhasil di update', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $member = Member::find($id);
        $member->delete();

        return response(null, 204);
    }

    public function cetakMember(Request $request)
    {
        $datamember = collect(array());
        foreach ($request->id_member as $id) {
            $member = Member::find($id);
            $datamember[] = $member;
        }

        $datamember = $datamember->chunk(2);
        // $setting = Setting::first();

        $no = 1;
        $pdf = PDF::loadView('member.cetak', compact('datamember', 'no'));
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('member.pdf');
    }
}
