<?php

namespace App\Http\Controllers\Api;

use App\ClassRoom;
use App\Http\Requests\ClassRoomRequest;
use App\Http\Transformers\v1\ClassRoomTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassroomsController extends Controller
{
    private $classroom;

    public function __construct(ClassRoom $classroom)
    {
        $this->classroom = $classroom;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $classrooms = $this->classroom->filter($request->all())->paginate($request->get('per_page', 10));
        return $this->response->paginator($classrooms, new ClassRoomTransformer);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassRoomRequest $request)
    {
        $classroom = $this->classroom->create($request->except('instructors'));

        if ($request->has('instructors')) {
            $instructors = collect($request->get('instructors'));
            $instructors->each(function ($instructor, $key) use ($classroom) {
                $classroom->instructors()->attach($instructor['user_id'], ['permissions' => $instructor['permissions']]);
            });

            //$classroom->instructors()->add($request->only('instructors'));
        }
        return $this->response->item($classroom, new ClassRoomTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $classroom = $this->classroom->findOrFail($id);
        return $this->response->item($classroom, new ClassRoomTransformer);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClassRoomRequest $request, $id)
    {
        $classroom = $this->classroom->findOrFail($id);
        $classroom->update($request->except('instructors'));
        return $this->response->item($classroom, new ClassRoomTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $classroom = $this->classroom->findOrFail($id);
        $classroom->delete();
        return $this->response()->noContent();

    }
}
