<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commentaire;

class CommentaireController extends Controller
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
        $commentaire=Commentaire::create([
            'createur'=>$request['createur'],
            'commentaire'=>$request['commentaire'],
            'plat_id'=>$request['plat_id'],
        ]);
        if(!$commentaire){
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
    public function show($plat_id)
    {
        $data= Commentaire::where('plat_id','=',$plat_id)->orderBy('updated_at','desc')->paginate(10);
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
