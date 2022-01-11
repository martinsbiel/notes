<?php

namespace App\Http\Controllers;

use App\Interfaces\NoteRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Note;
use App\Http\Requests\NoteRequest;

class NoteController extends Controller
{
    private NoteRepositoryInterface $noteRepository;

    public function __construct(NoteRepositoryInterface $noteRepository) 
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return response()->json($this->noteRepository->getNotesByUser(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
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
     * @param  \App\Http\Requests\NoteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoteRequest $request)
    {
        $validated = $request->validated();

        return response()->json($this->noteRepository->createNote($validated), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return response()->json($this->noteRepository->getNoteById($id), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
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
     * @param  \App\Http\Requests\NoteRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NoteRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            return response()->json($this->noteRepository->updateNote($id, $validated), 200);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
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
        try {
            $delete = $this->noteRepository->deleteNote($id);

            return response()->json(['msg' => 'A nota foi removida com sucesso'], 200);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function search(Request $request){
        try {
            $data = $request->get('search');

            return response()->json($this->noteRepository->searchNote($data), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function exportExcel(){
        return $this->noteRepository->exportNotesToExcel();
    }

    public function exportPDF(){
        return $this->noteRepository->exportNotesToPDF();
    }
}
