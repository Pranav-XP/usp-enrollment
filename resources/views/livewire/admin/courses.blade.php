<div>
    <form wire:submit="save" class="flex flex-col gap-6 mb-8">
        <flux:heading size='xl'>Create Course</flux:heading>
        <flux:subheading>Enter course information below.</flux:subheading>

        <flux:checkbox.group wire:model="programId" :label="__('Offered under which courses:')">
            @foreach ($programs as $program )
            <flux:checkbox
            label="{{ $program->name }}"
            value="{{ $program->id }}" 
    />
            @endforeach        
            
        </flux:flux:checkbox.group>
        
        <!-- Course Code -->
        <flux:input
            wire:model="form.course_code"
            :label="__('Course code')"
            type="text"
            required
            autofocus
            :placeholder="__('Enter the course code')"
        />
    
        <!-- Course Title -->
        <flux:input
            wire:model="form.course_title"
            :label="__('Course Title')"
            type="text"
            required
            autofocus
            :placeholder="__('Enter the course title')"
        />
    
            <!-- Description -->
        <flux:textarea
        wire:model="form.description"
        :label="__('Course Description')"
        required
        autofocus
        :placeholder="__('Describe the course')"
        class="h-32"
        />
    
        <!-- Level -->
        <flux:input
            wire:model="form.year"
            :label="__('Course level')"
            type="number"
            required
            placeholder="Enter at which year is this course offered"
        />

        <!-- Course -->
        <flux:input
            wire:model="form.cost"
            :label="__('Cost of course')"
            type="number"
            required
            placeholder="Enter cost in dollars"
        />
        
        <flux:checkbox.group :label="__('Offered in which semester')">
                <flux:checkbox
                wire:model="form.semester_1"
                :label="__('Semester 1')"
                />

            <flux:checkbox
            wire:model="form.semester_2"
            :label="__('Semester 2')"
            />

        </flux:flux:checkbox.group>
        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create course') }}
            </flux:button>
        </div>
    </form>    
</div>

