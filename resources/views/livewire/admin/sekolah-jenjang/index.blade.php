<div x-data="{ deleteConfirmation: '' }"
     @swal:success.window="Swal.fire({
         icon: 'success',
         title: 'Berhasil!',
         text: $event.detail.message || 'Operasi berhasil dilakukan',
         timer: 2000,
         showConfirmButton: false
     })">

    <div class="flex justify-between">
        <h1>{{ __('Sekolah Jenjang') }}</h1>
        <div>
            <x-button wire:click="openCreateDialog">{{ __('Add Sekolah Jenjang') }}</x-button>
        </div>
    </div>

    @include('errors.messages')

    <div class="card">
        <div class="grid sm:grid-cols-1 md:grid-cols-4 gap-4">
            <div class="col-span-2">
                <x-form.input type="search" id="code" name="code" wire:model.live="code" label="none" :placeholder="__('Search Code')" />
            </div>
            <div class="col-span-2">
                <x-form.input type="search" id="name" name="name" wire:model.live="name" label="none" :placeholder="__('Search Name')" />
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>
                        <a class="link" href="#" wire:click.prevent="sortBy('order_number')">{{ __('Order') }}</a>
                    </th>
                    <th>
                        <a class="link" href="#" wire:click.prevent="sortBy('code')">{{ __('Code') }}</a>
                    </th>
                    <th>
                        <a class="link" href="#" wire:click.prevent="sortBy('name')">{{ __('Name') }}</a>
                    </th>
                    <th>
                        <a class="link" href="#" wire:click.prevent="sortBy('is_active')">{{ __('Status') }}</a>
                    </th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->sekolahJenjang() as $item)
                    <tr>
                        <td>{{ $item->order_number }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="badge badge-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge badge-danger">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="#" wire:click.prevent="openEditDialog({{ $item->id }})" class="link">{{ __('Edit') }}</a>
                                <div x-data="{ showDeleteModal: false }">
                                    <a href="#" @click.prevent="showDeleteModal = true" class="link text-red-600">{{ __('Delete') }}</a>
                                    <x-modal>
                                        <x-slot name="trigger">
                                            <div x-show="showDeleteModal" x-cloak></div>
                                        </x-slot>
                                        <x-slot name="modalTitle">
                                            <div class="pt-5">
                                                {{ __('Are you sure you want to delete') }}: <b>{{ $item->name }}</b>
                                            </div>
                                        </x-slot>
                                        <x-slot name="content">
                                            <label class="flex flex-col gap-2">
                                                <div>{{ __('Type') }} <span class="font-bold">"{{ $item->name }}"</span> {{ __('to confirm') }}</div>
                                                <input autofocus x-model="deleteConfirmation" class="px-3 py-2 border border-slate-300 rounded-lg">
                                            </label>
                                        </x-slot>
                                        <x-slot name="footer">
                                            <x-button variant="gray" @click="showDeleteModal = false; deleteConfirmation = ''">{{ __('Cancel') }}</x-button>
                                            <x-button variant="red"
                                                x-bind:disabled="deleteConfirmation !== '{{ $item->name }}'"
                                                wire:click="deleteSekolahJenjang('{{ $item->id }}')"
                                                @click="showDeleteModal = false; deleteConfirmation = ''">
                                                {{ __('Delete') }}
                                            </x-button>
                                        </x-slot>
                                    </x-modal>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $this->sekolahJenjang()->links() }}
    </div>

    <!-- Create/Edit Modal -->
    <x-modal>
        <x-slot name="trigger">
            <button x-show="$wire.showDialog"
                    x-cloak
                    @click="on = true"
                    style="display: none;"
                    x-init="$watch('$wire.showDialog', value => { if (value) { $nextTick(() => { $el.click(); }); } })"></button>
        </x-slot>
        <x-slot name="modalTitle">
            {{ $editId ? __('Edit Sekolah Jenjang') : __('Add Sekolah Jenjang') }}
        </x-slot>
        <x-slot name="content">
            <x-form.input autofocus wire:model="formCode" :label="__('Code')" name="formCode" required maxlength="20" />
            <x-form.input wire:model="formName" :label="__('Name')" name="formName" required maxlength="100" />
            <x-form.input type="number" wire:model="formOrderNumber" :label="__('Order Number')" name="formOrderNumber" required min="0" />
            <x-form.checkbox wire:model="formIsActive" :label="__('Active')" name="formIsActive" />
        </x-slot>
        <x-slot name="footer">
            <x-button variant="gray" @click="close(); $wire.closeDialog()">{{ __('Close') }}</x-button>
            <x-button wire:click="{{ $editId ? 'update' : 'store' }}">
                {{ $editId ? __('Update') : __('Create') }}
            </x-button>
        </x-slot>
    </x-modal>

</div>

