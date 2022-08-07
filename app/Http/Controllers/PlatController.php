<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plat;

class PlatController extends Controller
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
        $plat=Plat::create([
                'name'=>$request['name'],
                'description'=>$request['description'],
                'price'=>$request['price'],
                'plat_number'=>$request['plat_number'],
                'type'=>$request['type'],
                'image'=>$path,
                'link'=>$request['link'],
                'user_id'=>$request['user_id']
        ]);
        if(!$plat){
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
    public function show($user_id)
    {
        $data= Plat::where('user_id','=',$user_id)->orderBy('updated_at','desc')->paginate(10);
        if($data && $data->total()>0){
            return[
                'status'=>'succes',
                'data'=>$data->items(),
                'nbr'=>$data->total()
            ];
         }
         else{
            return[
                'status'=>'echec'
            ];
         }
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPlat($id)
    {
        $plat=Plat::find($id);
        if($plat){
            return[
                'status'=>'succes',
                'data'=>$plat
            ];
        }
        else{
            return[
                'status'=>'echec'
            ];
        }
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
        $imageData=$request->file;
        $path=$request->path;
        $verif=substr($imageData, 0, 4);
        if($verif=="http"){
            $plat=Plat::find($id);
            $plat->update($request->all());
            return[
                'status'=>'succes'
            ];
        }
        else{
            $path=$request->path;
            $imagetodelete=$request->imgtodelete;
            $imageData=str_replace('data:image/jpeg;base64,','',$imageData);
            $imageData=str_replace('data:image/jpg;base64,','',$imageData);
            $imageData=str_replace(' ','+',$imageData);
            $imageData=base64_decode($imageData);
            file_put_contents($path,$imageData);
            if (file_exists($imagetodelete)){
            if($imagetodelete=="storage/app/foods/unknown-food.jpg"){}
            else{
                unlink($imagetodelete);
                }
            }
            $plat=Plat::find($id);
            $plat->update([
                'name'=>$request['name'],
                'description'=>$request['description'],
                'price'=>$request['price'],
                'plat_number'=>$request['plat_number'],
                'type'=>$request['type'],
                'image'=>$path
        ]);
            return[
                'status'=>'succes'
            ];

        }

    }

    public function updatePlatNumber(Request $request, $id){
        $plat=Plat::find($id);
        $plat->update([
            'plat_number'=>$request['plat_number'],
        ]);
        return[
            'status'=>'succes'
        ];


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Plat::destroy($id)){
            return[
                'status'=>'succes'
            ];
        }
        else{
            return[
                'status'=>'echec'
            ];
        }
    }
}
