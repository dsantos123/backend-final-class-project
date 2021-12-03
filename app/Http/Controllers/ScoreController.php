<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
    use App\Models\Score;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

class ScoreController extends Controller
{
    public function setScore(Request $request){
        if(!$user = auth()->user()){
            return response()->json(['error' => 'user_not_found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'score' => 'required|numeric',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors(), 400);
        }

        $score = Score::create([
            'user_id' => $user->id,
            'score' => $request->get('score'),
        ]);

        return response()->json(compact('score'),201);
    }

    public function getHighestScore(){
        if(!$user = auth()->user()){
            return response()->json(['error' => 'user_not_found'], 404);
        }
        if($score = Score::where('user_id', $user->id)->orderByDesc('score')->take(1)->first()){
            $score = $score->score;   
        }
        return response()->json(compact('score', 'user'));

    }
}
