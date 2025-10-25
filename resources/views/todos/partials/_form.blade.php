@php
     $task = $task ?? new \App\Models\TodoModel();
@endphp

<x-validation-errors class="mb-4" />

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-6">
        <div>
            <x-input-label for="title" :value="__('العنوان')" />
            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $task->title)" required autofocus />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="description" :value="__('الوصف')" />
            <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $task->description) }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>
    </div>

    <div class="space-y-6">
        <div>
            <x-input-label for="status" :value="__('الحالة')" />
            <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ old('status', $task->status?->value) == $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="due_date" :value="__('تاريخ الاستحقاق')" />
            <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', $task->due_date?->format('Y-m-d'))" />
            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="attachment" :value="__('المرفق (صورة أو PDF)')" />
            <input id="attachment" name="attachment" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
            <x-input-error :messages="$errors->get('attachment')" class="mt-2" />

            @if($task->attachment)
                <div class="mt-2 text-sm">
                    المرفق الحالي: <a href="{{ Storage::url($task->attachment) }}" target="_blank" class="text-blue-500 hover:underline">عرض الملف</a>
                </div>
            @endif
        </div>

    </div>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('tasks.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
        {{ __('إلغاء') }}
    </a>

    <x-primary-button>
        {{ $task->exists ? __('تحديث المهمة') : __('إنشاء المهمة') }}
    </x-primary-button>
</div>
