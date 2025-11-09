<?php

declare(strict_types=1);

namespace App\Livewire\Admin\SekolahJenjang;

use App\Models\SekolahJenjang as SekolahJenjangModel;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Sekolah Jenjang')]
class SekolahJenjang extends Component
{
    use WithPagination;

    public int $paginate = 25;

    public string $code = '';

    public string $name = '';

    public string $sortField = 'order_number';

    public bool $sortAsc = true;

    // Create/Edit properties
    public bool $showDialog = false;
    public ?int $editId = null;
    public string $formCode = '';
    public string $formName = '';
    public int $formOrderNumber = 0;
    public bool $formIsActive = true;

    public function render(): View
    {
        return view('livewire.admin.sekolah-jenjang.index');
    }

    public function builder(): mixed
    {
        return SekolahJenjangModel::orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        }

        $this->sortField = $field;
    }

    /**
     * @return LengthAwarePaginator<int, SekolahJenjangModel>
     */
    public function sekolahJenjang(): LengthAwarePaginator
    {
        return $this->builder()
            ->when($this->code, fn (Builder $query) => $query->where('code', 'like', '%'.$this->code.'%'))
            ->when($this->name, fn (Builder $query) => $query->where('name', 'like', '%'.$this->name.'%'))
            ->paginate($this->paginate);
    }

    public function openCreateDialog(): void
    {
        $this->resetForm();
        $this->editId = null;
        $this->showDialog = true;
    }

    public function openEditDialog(int $id): void
    {
        $sekolahJenjang = SekolahJenjangModel::findOrFail($id);
        $this->editId = $id;
        $this->formCode = $sekolahJenjang->code;
        $this->formName = $sekolahJenjang->name;
        $this->formOrderNumber = $sekolahJenjang->order_number;
        $this->formIsActive = $sekolahJenjang->is_active;
        $this->showDialog = true;
    }

    public function closeDialog(): void
    {
        $this->showDialog = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->formCode = '';
        $this->formName = '';
        $this->formOrderNumber = 0;
        $this->formIsActive = true;
        $this->editId = null;
    }

    public function store(): void
    {
        $this->validate([
            'formCode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('sekolah_jenjang', 'code'),
            ],
            'formName' => [
                'required',
                'string',
                'max:100',
            ],
            'formOrderNumber' => [
                'required',
                'integer',
                'min:0',
            ],
            'formIsActive' => [
                'required',
                'boolean',
            ],
        ]);

        $sekolahJenjang = SekolahJenjangModel::create([
            'code' => $this->formCode,
            'name' => $this->formName,
            'order_number' => $this->formOrderNumber,
            'is_active' => $this->formIsActive,
        ]);

        add_user_log([
            'title' => 'created sekolah jenjang '.$this->formName,
            'link' => route('admin.master-data.sekolah-jenjang.index'),
            'reference_id' => $sekolahJenjang->id,
            'section' => 'Sekolah Jenjang',
            'type' => 'created',
        ]);

        $this->closeDialog();
        $this->dispatch('swal:success', message: 'Data berhasil ditambahkan!');
    }

    public function update(): void
    {
        $this->validate([
            'formCode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('sekolah_jenjang', 'code')->ignore($this->editId),
            ],
            'formName' => [
                'required',
                'string',
                'max:100',
            ],
            'formOrderNumber' => [
                'required',
                'integer',
                'min:0',
            ],
            'formIsActive' => [
                'required',
                'boolean',
            ],
        ]);

        $sekolahJenjang = SekolahJenjangModel::findOrFail($this->editId);
        $sekolahJenjang->code = $this->formCode;
        $sekolahJenjang->name = $this->formName;
        $sekolahJenjang->order_number = $this->formOrderNumber;
        $sekolahJenjang->is_active = $this->formIsActive;
        $sekolahJenjang->save();

        add_user_log([
            'title' => 'updated sekolah jenjang '.$this->formName,
            'link' => route('admin.master-data.sekolah-jenjang.index'),
            'reference_id' => $sekolahJenjang->id,
            'section' => 'Sekolah Jenjang',
            'type' => 'Update',
        ]);

        $this->closeDialog();
        $this->dispatch('swal:success', message: 'Data berhasil diupdate!');
    }

    public function deleteSekolahJenjang(string $id): void
    {
        $sekolahJenjang = SekolahJenjangModel::findOrFail($id);
        $sekolahJenjang->delete();

        add_user_log([
            'title' => 'deleted sekolah jenjang '.$sekolahJenjang->name,
            'link' => route('admin.master-data.sekolah-jenjang.index'),
            'reference_id' => $sekolahJenjang->id,
            'section' => 'Sekolah Jenjang',
            'type' => 'deleted',
        ]);

        $this->dispatch('swal:success', message: 'Data berhasil dihapus!');
    }
}

