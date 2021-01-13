<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class crud extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = \App\Models\User::get();
        return collect($data)->map(function ($out)
        {
            return [
                'nama' => $out['name'],
                'email' => $out['email'],
                'image' => public_path().'/berkas/'.$out['image'],
            ];
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $val = $request->validate([
            'name' => 'required',
            'password' => 'required|min:6|max:8',
        ]);

        $data = \App\Models\User::where([
            'name' => $val['name'],
            'password' => $val['password'],
        ])->first();

        if ($data) {
            $msg = [
                'success' => true,
                'token' => $data->createToken($data->name)->accessToken,
                'message' => 'Berhasil Login!'
            ];
        }
        if (!$data) {
            $msg = [
                'success' => false,
                'token' => 'null',
                'message' => 'Gagal Login!'
            ];
        }
        return response()->json($msg);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $val = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:6|max:8',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $data = $request->file('image');
        if ($data->isValid()) {    
            $ekstensi = time().".".$data->getClientOriginalExtension();
            $nama_file = rand(111,99999).'_'.$ekstensi;
            $file = public_path().'/berkas/';
            $data->move($file,$nama_file);
        }
        $project = \App\Models\User::create([
            'name' => $val['name'],
            'email' => $val['email'],
            'password' => $val['password'],
            'image' => $nama_file,
        ]);
        if ($project) {
            $msg = [
                'success' => true,
                'message' => 'Berhasil terbuat!'
            ];
        }
        if (!$project) {
            $msg = [
                'success' => false,
                'message' => 'Gagal terbuat!'
            ];
        }
        return response()->json($msg);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = \App\Models\User::where('id',$id)->first();
        return $data->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
         $data['nama'] = auth()->guard('api')->user()->name;
         $data['email'] = auth()->guard('api')->user()->email;
         $data['image'] = auth()->guard('api')->user()->image;
         return [
            'nama' => $data['nama'],
            'email' => $data['email'],
            'image' => public_path().'/berkas/'.$data['image'], 
         ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $val = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:6|max:8',
        ]);
        
        if ($data['image'] = $request->file('image')) {
            if ($data['image']->isValid()) {    
                $ekstensi = time().".".$data['image']->getClientOriginalExtension();
                $nama_file = rand(111,99999).'_'.$ekstensi;
                $filektp = public_path().'/berkas/';
                $data['image']->move($filektp,$nama_file);
            }
        }
        $data['image'] = 'notfond';

        $project = \App\Models\User::where('id',$id)->update([
            'name' => $val['name'],
            'email' => $val['email'],
            'password' => $val['password'],
            'image' => $data['image'],
        ]);
        if ($project) {
            $msg = [
                'success' => true,
                'message' => 'Berhasil terupdate!'
            ];
        }
        if (!$project) {
            $msg = [
                'success' => false,
                'message' => 'Gagal terupdate!'
            ];
        }
        return response()->json($msg);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = \App\Models\User::find($id);
        if ($data) {
            $data->delete();
            $msg = [
                'success' => true,
                'message' => 'Berhasil tehapus!'
            ];
        }
        if (!$data) {
            $msg = [
                'success' => false,
                'message' => 'Gagal tehapus!'
            ];
        }
        return response()->json($msg);
    }
}
