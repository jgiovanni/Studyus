<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Http\Requests\AnswerRequest;
use App\Http\Transformers\v1\AnswerTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnswersController extends Controller
{

    private $answer;

    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $answers = $this->answer->filter($request->all())->paginate($request->get('per_page', 10));
        return $this->response->paginator($answers, new AnswerTransformer);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnswerRequest $request)
    {
        $answer = $this->answer->create($request->all());
        //$answer->save();
        return $this->response->item($answer, new AnswerTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $answer = $this->answer->findOrFail($id);
        return $this->response->item($answer, new AnswerTransformer);

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
        $answer = $this->answer->findOrFail($id);
        $answer->update($request->all());
        return $this->response->item($answer, new AnswerTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $answer = $this->answer->findOrFail($id);
        $answer->delete();
        return $this->response->noContent();

    }
}
