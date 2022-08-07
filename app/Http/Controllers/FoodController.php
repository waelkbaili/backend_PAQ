<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imageData=$request->file;
        if($imageData=='assets/icon/unknown-food.jpg'){
            $path='storage/app/foods/unknown-food.jpg';
        }
        else{
            $path=$request->path;
            $imageData=str_replace('data:image/jpeg;base64,','',$imageData);
            $imageData=str_replace('data:image/jpg;base64,','',$imageData);
            $imageData=str_replace(' ','+',$imageData);
            $imageData=base64_decode($imageData);
            file_put_contents($path,$imageData);
        }
        $food=Food::create([
                'name'=>$request['name'],
                'description'=>$request['description'],
                'price'=>$request['price'],
                'plat_number'=>$request['plat_number'],
                'type'=>$request['type'],
                'image'=>$path,
                'user_id'=>$request['user_id']
        ]);
        if(!$food){
            $response=[
                'status'=>'echec'
            ];
        }
        else{
            $response=[
                'status'=>'succes'
            ];
        }

        return response($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
