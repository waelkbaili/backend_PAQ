<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\User;
use DB;
use Carbon\Carbon;

class CommandeController extends Controller
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
        $commande=Commande::create([
            'listCmd'=>serialize($request['listCmd']),
            'note'=>$request['note'],
            'address'=>$request['address'],
            'client_id'=>$request['client_id'],
            'cuistot_id'=>$request['cuistot_id'],
        ]);
        if(!$commande){
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
    public function showHistorique($client_id)
    {
        $commande= Commande::where('client_id','=',$client_id)->orderBy('created_at','desc')->paginate(10);
        if($commande && $commande->total()>0){
            $data=$commande->items();
            foreach ($data as $rows) {
                $cuistotid=$rows['cuistot_id'];
                $cuistotname=User::where('id', $cuistotid)->value('name');
                $fataFood[]=array(
                    'client_id' => $rows['client_id'],
                    'created_at' => $rows['created_at'],
                    'listCmd' => unserialize($rows['listCmd']) ,
                    'cuistotname' =>$cuistotname
                );
            }

            return[

                'status'=>'succes',
                'data'=>$fataFood,
                'nbr'=>$commande->total()
            ];
         }
         else{
            return[
                'status'=>'echec'
            ];
         }
    }

    public function showLivaison($cuistot_id)
    {
        $commande= Commande::where('cuistot_id','=',$cuistot_id)->orderBy('created_at','desc')->paginate(10);
        if($commande&& $commande->total()>0){
            foreach ($commande as $rows) {
                $client_id=$rows['client_id'];
                $clientname=User::where('id', $client_id)->value('name');
                $fataFood[]=array(
                    'address' => $rows['address'],
                    'note' => $rows['note'],
                    'created_at' => $rows['created_at'],
                    'listCmd' => unserialize($rows['listCmd']),
                    'clientname' =>$clientname
                );
            }

            return[

                'status'=>'succes',
                'data'=>$fataFood,
                'nbr'=>$commande->total()

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

    public function rate(Request $request){
        $sommeRate = 0;
        $client_id=$request->client_id;
        $created_at=$request->created_at;
        $rate=$request->rate;
        $plat_id=$request->plat_id;

        $commande= Commande::where([['client_id','=',$client_id],['created_at','=',$created_at]])->get(['listCmd','cuistot_id','id']);
        $cuistot_id=$commande[0]['cuistot_id'];
        $list=unserialize($commande[0]['listCmd']);
        $id=$commande[0]['id'];

        for ($index = 0; $index < count($list); $index++){
            $dec=explode("@",$list[$index]);
            if($dec[0]==$plat_id){

                $list[$index]=substr_replace($list[$index],$rate, -1);
            }
            $sommeRate=$sommeRate+(intval(explode("@",$list[$index])[3])) ;
        }
        $sommeRate=$sommeRate/(count($list));
        $newlist=serialize($list);

        $newCommande=Commande::find($id);
        $newCommande->update([
            'listCmd'=>$newlist,
            'cmdRate'=>$sommeRate
        ]);

        $count=Commande::where('cuistot_id','=',$cuistot_id)->count();
        $sum=Commande::where('cuistot_id','=',$cuistot_id)->sum('cmdRate');
        $newRate=$sum/$count;
        $user=User::find($cuistot_id);
        $user->update([
            'rate'=>$newRate
        ]);

        return [
            'status'=>'succes'
        ];
    }

    public function state(Request $request){
        $year=$now = Carbon::now()->year;
        $cle=$request->cle;
        $cuistot_id=$request->cuistot_id;
        $sommeRate=0;
        if($cle=='week'){
            for($i=0;$i<=52;$i++){
                $week[$i]=(new Carbon('first day of January'.$year))->addWeeks($i)->format("Y-m-d 00:00:00");
            }
            for($i=0;$i<count($week)-1;$i++){
                $commande= Commande::where([['cuistot_id','=',$cuistot_id],['created_at','>=',$week[$i]],
                ['created_at','<=',$week[$i+1]]])->get('listCmd');
           for ($index = 0; $index < count($commande); $index++){
               $list=unserialize($commande[$index]['listCmd']);
               for ($jndex = 0; $jndex < count($list); $jndex++){
                   $dec=explode("@",$list[$jndex]);
                  $sommeRate=$sommeRate+intval($dec[1]);
               }
           }
           $tabSomme[$i]=$sommeRate;
           $tabWekk[$i]=$i+1;
           $sommeRate=0;
           }
           return [
               'status'=>'succes',
               'somme'=>$tabSomme,
               'details'=>$tabWekk
           ];
        }
        if($cle=='mounth'){
            for($i=1;$i<=12;$i++){
                $start[]=Carbon::now()->month($i)->day(1)->format("Y-m-d 00:00:00");
                $end[]=Carbon::now()->month($i)->endOfMonth()->format("Y-m-d H:i:s");
            }
            for($i=0;$i<12;$i++){
                 $commande= Commande::where([['cuistot_id','=',$cuistot_id],['created_at','>=',$start[$i]],
                 ['created_at','<=',$end[$i]]])->get('listCmd');
            for ($index = 0; $index < count($commande); $index++){
                $list=unserialize($commande[$index]['listCmd']);
                for ($jndex = 0; $jndex < count($list); $jndex++){
                    $dec=explode("@",$list[$jndex]);
                   $sommeRate=$sommeRate+intval($dec[1]);
                }
            }
            $tabSomme[$i]=$sommeRate;
            $sommeRate=0;
            $tabWekk[$i]=$i+1;


            }
            return [
                'status'=>'succes',
                'somme'=>$tabSomme,
                'details'=>$tabWekk
            ];
        }




    }
}
