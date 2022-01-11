<?php

namespace App\Interfaces;

interface NoteRepositoryInterface 
{
    public function getNotesByUser();
    public function getNoteById($noteId);
    public function deleteNote($noteId);
    public function createNote(array $noteDetails);
    public function updateNote($noteId, array $newDetails);
    public function searchNote($searchDetails);
    public function exportNotesToExcel();
    public function exportNotesToPDF();
}