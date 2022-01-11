<?php

namespace App\Repositories;

use App\Interfaces\NoteRepositoryInterface;
use App\Models\Note;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NotesExport;
use PDF;

class NoteRepository implements NoteRepositoryInterface 
{
    // model injection
    public function __construct(Note $note){
        $this->note = $note;
    }

    public function getNotesByUser(){
        $notes = $this->note->where('user_id', auth()->user()->id)->paginate(10);

        if(count($notes) == 0){
            throw new \Exception('Nenhuma nota encontrada para este usuário');
        }

        Log::info('Usuário de ID ' . auth()->user()->id . ' está visualizando suas próprias notas');

        return $notes;
    }

    public function getNoteById($noteId){
        $note = $this->note->with('user')->where('user_id', auth()->user()->id)->find($noteId);

        if($note === null){
            throw new \Exception('Impossível realizar a exibição. O recurso solicitado não existe');
        }

        return $note;
    }

    public function deleteNote($noteId){
        $deleteNote = $this->note->where('user_id', auth()->user()->id)->find($noteId);

        if($deleteNote === null){
            throw new \Exception('Impossível realizar a exclusão. O recurso solicitado não existe');
        }

        $deleteNote->delete();

        Log::info('Nota de ID ' . $noteId . ' foi removida pelo usuário de ID: ' . auth()->user()->id);

        return $deleteNote;
    }

    public function createNote(array $noteDetails){
        $note = $this->note->create([
            'user_id' => auth()->user()->id,
            'title' => $noteDetails['title'],
            'content' => $noteDetails['content']
        ]);

        Log::info('Nota adicionada pelo usuário de ID ' . auth()->user()->id);

        return $note;
    }

    public function updateNote($noteId, array $newDetails){
        $note = $this->note->where('user_id', auth()->user()->id)->find($noteId);

        if($note === null){
            throw new \Exception('Impossível realizar a atualização. O recurso solicitado não existe');
        }

        $note->update($newDetails);

        Log::info('Nota de ID ' . $noteId . ' foi atualizada pelo usuário de ID: ' . auth()->user()->id);

        return $note;
    }

    public function searchNote($searchDetails){
        $notes = $this->note->where('user_id', auth()->user()->id)->where('title', 'like', "%{$searchDetails}%")->get();

        if(count($notes) == 0){
            throw new \Exception('Nenhuma nota encontrada para estes termos');
        }

        return $notes;
    }

    public function exportNotesToExcel(){
        Log::info('Usuário de ID ' . auth()->user()->id . ' exportou suas notas para Excel');

        return Excel::download(new NotesExport, 'my-notes.xlsx');
    }

    public function exportNotesToPDF(){
        $notes = auth()->user()->notes()->get();
        $pdf = PDF::loadView('app.pdf', ['notes' => $notes]);

        $pdf->setPaper('a4', 'landscape');

        Log::info('Usuário de ID ' . auth()->user()->id . ' exportou suas notas para PDF');

        return $pdf->stream('my-notes.pdf');
    }
}