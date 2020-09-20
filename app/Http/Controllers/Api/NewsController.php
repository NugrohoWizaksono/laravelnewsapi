<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::all();
        $response = [
            'success'=>true,
            'data' => $news
        ];

        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (Auth::user()->isAdmin())
        {
            $validated = Validator::make($request->all(),[
                'title' => 'required|min:5',
                'subject' => 'required|min:10'
            ]);

            if ($validated->fails())
            {
                return response(['success'=>false, 'message'=>$validated->errors()], 401);
            }
            $news = $request->user()->news()->create([
                'title' => $request->title,
                'subject' => $request->subject
            ]);

            $response = [
                'success'=>true,
                'message'=>'Successfuly create news'
            ];

            return response()->json($response,200);
        }
        else
        {
            return response(['success'=>false, 'message'=>'Not Authorize'], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $news = News::findOrFail($id);
        $response = [
            'success'=>true,
            'data' => $news
        ];

        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        if (Auth::user()->isAdmin())
        {
            $validated = Validator::make($request->all(),[
                'title' => 'required|min:5',
                'subject' => 'required|min:10'
            ]);

            if ($validated->fails())
            {
                return response(['success'=>false, 'message'=>$validated->errors()], 401);
            }

            $news = News::FindOrFail($id);
            if($news->isOwner())
            {
                $news->update([
                    'title' => $request->title,
                    'subject' => $request->subject
                ]);

                $response = [
                    'success'=>true,
                    'message'=>'Successfuly update news'
                ];

                return response()->json($response,200);
            }
            else
            {
                return response(['success'=>false, 'message'=>'Not Authorize'], 401);
            }
        }
        else
        {
            return response(['success'=>false, 'message'=>'Not Authorize'], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->isAdmin())
        {
            $news = News::FindOrFail($id);
            if($news->isOwner())
            {
                $news->delete();
                $response = [
                    'success'=>true,
                    'message'=>'Successfuly delete news'
                ];

                return response()->json($response,200);
            }
            else
            {
                return response(['success'=>false, 'message'=>'Not Authorize'], 401);
            }
        }
        else
        {
            return response(['success'=>false, 'message'=>'Not Authorize'], 401);
        }
    }
}
