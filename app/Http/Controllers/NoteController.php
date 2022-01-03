<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NotesExport;
use PDF;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\NoteRequest;

class NoteController extends Controller
{
    // model injection
    public function __construct(Note $note){
        $this->note = $note;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->get('user_id');

        $notes = $this->note->where('user_id', $id)->paginate(10);

        if(count($notes) == 0){
            return response()->json(['error' => 'Nenhuma nota encontrada para este usuário'], 404);
        }

        Log::info('Usuário de ID ' . $id . ' está visualizando suas próprias notas');

        return response()->json($notes, 200);
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
    public function store(NoteRequest $request)
    {
        $request->validate($request->rules(), $request->messages());

        $note = $this->note->create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'content' => $request->content
        ]);

        Log::info('Nota adicionada pelo usuário de ID ' . $request->user_id);

        return response()->json($note, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = $this->note->with('user')->find($id);

        if($note === null){
            return response()->json(['error' => 'Impossível realizar a exibição. O recurso solicitado não existe'], 404);
        }

        return response()->json($note, 200);
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
    public function update(NoteRequest $request, $id)
    {
        $userId = $request->get('user_id');

        $note = $this->note->where('user_id', $userId)->find($id);

        if($note === null){
            return response()->json(['error' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        $request->validate($request->rules(), $request->messages());

        $note->fill($request->all())->save();

        Log::info('Nota de ID ' . $id . ' foi atualizada pelo usuário de ID: ' . $userId);

        return response()->json($note, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $userId = $request->get('user_id');

        $note = $this->note->where('user_id', $userId)->find($id);

        if($note === null){
            return response()->json(['error' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $note->delete();

        Log::info('Nota de ID ' . $id . ' foi removida pelo usuário de ID: ' . $userId);

        return response()->json(['msg' => 'A nota foi removida com sucesso'], 200);
    }

    public function search(Request $request){
        $id = $request->get('user_id');
        $data = $request->get('search');

        $notes = $this->note->where('user_id', $id)->where('title', 'like', "%{$data}%")->get();

        if(count($notes) == 0){
            return response()->json(['error' => 'Nenhuma nota encontrada para estes termos'], 404);
        }

        return response()->json($notes, 200);
    }

    public function exportExcel(){
        Log::info('Usuário de ID ' . auth()->user()->id . ' exportou suas notas para Excel');

        return Excel::download(new NotesExport, 'my-notes.xlsx');
    }

    public function exportPDF(){
        $notes = auth()->user()->notes()->get();
        $pdf = PDF::loadView('app.pdf', ['notes' => $notes]);

        $pdf->setPaper('a4', 'landscape');

        Log::info('Usuário de ID ' . auth()->user()->id . ' exportou suas notas para PDF');

        return $pdf->stream('my-notes.pdf');
    }
}
