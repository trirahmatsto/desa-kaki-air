<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Infographic;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.app')]
class InfographicController extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $file;

    public function render()
    {
        return view('livewire.admin.infographic-controller', [
            'infographics' => Infographic::latest()->get(),
        ]);
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $filePath = $this->file->store('infographics', 'public');

        Infographic::create([
            'title' => $this->title,
            'description' => $this->description,
            'file_path' => $filePath,
            'file_type' => $this->file->extension(),
        ]);

        $this->reset(['title', 'description', 'file']);
        session()->flash('message', 'Infografis berhasil diunggah.');
    }

    public function delete($id)
    {
        $infographic = Infographic::findOrFail($id);
        Storage::disk('public')->delete($infographic->file_path);
        $infographic->delete();
        session()->flash('message', 'Infografis berhasil dihapus.');
    }
}
