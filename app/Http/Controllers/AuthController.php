<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request){

        $rules=array(
            'email'=>'unique:users'
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails()){
            $response=[
                'status'=>'Email existe'
            ];
        }
        else{
            $user=User::create([
                'name'=>$request['name'],
                'email'=>$request['email'],
                'password'=>bcrypt($request['password']),
                'gender'=>$request['gender'],
                'date_birth'=>$request['date_birth'],
                'address'=>$request['address'],
                'country'=>$request['country']
            ]);
                $response=[
                    'status'=>'succes'
                ];
        }
        return response($response);

    }

    public function logout(Request $request){
        auth()->user()->currentAccessToken()->delete();
        return[
            'status'=>'logout'
        ];
    }

    public function login(Request $request){


        $user=User::where('email',$request['email'])->first();
        if(!$user){
            return response([
                'status'=>'not exist',
            ]);
        }
        else if(!Hash::check($request['password'],$user->password)){
            return response([
                'status'=>'false password',
            ],
        );
        }

        $token=$user->createToken('myapptoken')->plainTextToken;

        $response=[
            'status'=>'succes',
            'data'=>[
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email,
                'gender'=>$user->gender,
                'date_birth'=>$user->date_birth,
                'address'=>$user->address,
                'country'=>$user->country,
                'rate'=>$user->rate,
                'img'=>$user->img,
                'token'=>$token
            ],
        ];
        return response($response);
    }
    public function search($country)
    {
         $data=User::where('country','like','%'.$country.'%')->orderBy('rate','desc')->paginate(10);
         if($data){
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

    public function show($id){
        $data=User::find($id);
         if($data){
            return[
                'status'=>'succes',
                'data'=>$data
            ];
         }
         else{
            return[
                'status'=>'echec'
            ];
         }

    }

    public function updateUserImage(Request $request,$id){
        $imageData=$request->file;
        $path=$request->path;
        $imgtodelete=$request->imgtodelete;
        $verif=substr($imageData, 0, 4);
        if($verif=="http"){}
        else{
            $imageData=str_replace('data:image/jpeg;base64,','',$imageData);
            $imageData=str_replace('data:image/jpg;base64,','',$imageData);
            $imageData=str_replace(' ','+',$imageData);
            $imageData=base64_decode($imageData);
            file_put_contents($path,$imageData);
            $user=User::find($id);
            $user->update([
            'img'=>$path,
        ]);

        }
        if (file_exists($imgtodelete)) {
            if($imgtodelete=="storage/app/users/photo-profil-default.png"){
            }
            else{
                unlink($imgtodelete);
            }
          }
        return[
            'status'=>'succes'
        ];

        $response=[
            'status'=>'succes'
        ];
        return response($response);
    }
}
