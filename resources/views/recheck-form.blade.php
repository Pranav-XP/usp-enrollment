<x-layouts.app :title="__('Apply for Grade Recheck')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Application for Reconsideration of Course Grade</flux:heading>
        <flux:subheading>
            Please complete this form for {{ $course->course_code }} - {{ $course->course_title }}.
            <br>
            <span class="text-red-500">Note: A fee applies for each course. Please confirm the fee with your local campus (e.g., $50.00 FJD) and upload payment confirmation.</span>
        </flux:subheading>

        <form action="{{ route('recheck.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md space-y-6">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            {{-- SECTION A: PERSONAL DETAILS --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4">SECTION A: PERSONAL DETAILS</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Student ID Number --}}
                <flux:field>
                    <flux:label for="student_id_number">Student ID Number</flux:label>
                    <flux:input id="student_id_number" type="text" name="student_id_number" value="{{ old('student_id_number', $student->student_id) }}" readonly />
                    <flux:error name="student_id_number" />
                </flux:field>

                {{-- Full Name --}}
                <flux:field>
                    <flux:label for="full_name">Full Name</flux:label>
                    <flux:input id="full_name" type="text" name="full_name" value="{{ old('full_name', $student->first_name . ' ' . $student->last_name) }}" required />
                    <flux:error name="full_name" />
                </flux:field>
            </div>

            {{-- Postal Address --}}
            <flux:field>
                <flux:label for="postal_address">Postal Address</flux:label>
                <flux:input id="postal_address" type="text" name="postal_address" value="{{ old('postal_address', $student->postal_address) }}" />
                <flux:error name="postal_address" />
            </flux:field>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Date of Birth --}}
                <flux:field>
                    <flux:label for="date_of_birth">Date of Birth</flux:label>
                    <flux:input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->dob ? \Carbon\Carbon::parse($student->dob)->format('Y-m-d') : '') }}" />
                    <flux:error name="date_of_birth" />
                </flux:field>

                {{-- Telephone --}}
                <flux:field>
                    <flux:label for="telephone">Telephone</flux:label>
                    <flux:input id="telephone" type="tel" name="telephone" value="{{ old('telephone', $student->phone) }}" required />
                    <flux:error name="telephone" />
                </flux:field>
            </div>

            {{-- Email --}}
            <flux:field>
                <flux:label for="email">Email</flux:label>
                <flux:input id="email" type="email" name="email" value="{{ old('email', $student->email) }}" required />
                <flux:error name="email" />
            </flux:field>

            {{-- Sponsorship Status (FIXED HERE) --}}
            <flux:field>
                <flux:label for="sponsorship_status">Are you sponsored or private student?</flux:label>
                <flux:select id="sponsorship_status" name="sponsorship_status" required>
                    <option value="">Select an option</option>
                    {{-- Corrected ternary for Private --}}
                    <option value="Private" {{ old('sponsorship_status', $student->sponsorship_status) == 'Private' ? 'selected' : '' }}>Private</option>
                    {{-- Corrected ternary for Sponsored --}}
                    <option value="Sponsored" {{ old('sponsorship_status', $student->sponsorship_status) == 'Sponsored' ? 'selected' : '' }}>Sponsored</option>
                </flux:select>
                <flux:error name="sponsorship_status" />
            </flux:field>

            {{-- SECTION B: REQUEST DETAILS --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4 mt-8">REQUEST DETAILS</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Course Code --}}
                <flux:field>
                    <flux:label for="course_code">Course Code</flux:label>
                    <flux:input id="course_code" type="text" name="course_code" value="{{ old('course_code', $course->course_code) }}" readonly />
                    <flux:error name="course_code" />
                </flux:field>

                {{-- Course Title --}}
                <flux:field>
                    <flux:label for="course_title">Course Title</flux:label>
                    <flux:input id="course_title" type="text" name="course_title" value="{{ old('course_title', $course->course_title) }}" readonly />
                    <flux:error name="course_title" />
                </flux:field>
            </div>

            {{-- Course Lecturer Name --}}
            <flux:field>
                <flux:label for="course_lecturer_name">Course Lecturer Name</flux:label>
                <flux:input id="course_lecturer_name" type="text" name="course_lecturer_name" value="{{ old('course_lecturer_name') }}" required />
                <flux:error name="course_lecturer_name" />
            </flux:field>

            {{-- Receipt No --}}
            <flux:field>
                <flux:label for="receipt_no">Receipt No</flux:label>
                <flux:input id="receipt_no" type="text" name="receipt_no" value="{{ old('receipt_no') }}" required />
                <flux:error name="receipt_no" />
            </flux:field>

            {{-- Payment Confirmation Upload --}}
            <flux:field>
                <flux:label for="payment_confirmation_upload">Payment Confirmation Upload</flux:label>
                <flux:input id="payment_confirmation_upload" type="file" name="payment_confirmation_upload" accept="application/pdf,image/jpeg,image/png" required />
                <flux:description>Max File Size: 3 MB (PDF, JPG, PNG)</flux:description>
                <flux:error name="payment_confirmation_upload" />
            </flux:field>

            <div class="flex justify-end mt-6">
                <flux:button type="submit" variant="primary">Submit Application</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>