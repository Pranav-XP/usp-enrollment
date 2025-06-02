<x-layouts.app :title="__('Application for Special Pass')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Application for Compassionate / Aegrotat Pass / Special Exam</flux:heading>
        <flux:subheading>
            Please complete this form if you missed or were impaired during an exam.
            <br>
            <span class="text-red-500">
                Note: Submit this as soon as possible. Supporting documents (e.g. medical, employer letters) are required.
            </span>
        </flux:subheading>

        <form action="{{ route('pass.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md space-y-6">
            @csrf

            {{-- SECTION A: PERSONAL DETAILS --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4">SECTION A: PERSONAL DETAILS</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label for="studentIdNumber">Student ID Number</flux:label>
                    <flux:input id="studentIdNumber" type="text" name="studentIdNumber" value="{{ old('studentIdNumber', $formData['studentIdNumber']) }}" readonly />
                    <flux:error name="studentIdNumber" />
                </flux:field>

                <flux:field>
                    <flux:label for="fullName">Full Name</flux:label>
                    <flux:input id="fullName" type="text" name="fullName" value="{{ old('fullName', $formData['fullName']) }}" readonly />
                    <flux:error name="fullName" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label for="postalAddress">Postal Address</flux:label>
                <flux:input id="postalAddress" type="text" name="postalAddress" value="{{ old('postalAddress', $formData['postalAddress']) }}" readonly />
                <flux:error name="postalAddress" />
            </flux:field>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label for="dateOfBirth">Date of Birth</flux:label>
                    <flux:input id="dateOfBirth" type="date" name="dateOfBirth" value="{{ old('dateOfBirth', $formData['dateOfBirth']) }}" readonly />
                    <flux:error name="dateOfBirth" />
                </flux:field>

                <flux:field>
                    <flux:label for="telephone">Telephone</flux:label>
                    <flux:input id="telephone" type="tel" name="telephone" value="{{ old('telephone', $formData['telephone']) }}" readonly />
                    <flux:error name="telephone" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label for="email">Email</flux:label>
                <flux:input id="email" type="email" name="email" value="{{ old('email', $formData['email']) }}" required readonly />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label for="campus">Campus</flux:label>
                <flux:select id="campus" name="campus" required>
                    <option value="">Select Campus</option>
                    @foreach($campuses as $campus)
                        <option value="{{ $campus }}" {{ old('campus', $formData['campus']) == $campus ? 'selected' : '' }}>{{ $campus }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="campus" />
            </flux:field>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label for="semesterTrimester">Semester/Trimester</flux:label>
                    <flux:select id="semesterTrimester" name="semesterTrimester" required>
                        <option value="">Select</option>
                        @foreach($semesterTrimesterOptions as $option)
                            <option value="{{ $option }}" {{ old('semesterTrimester', $formData['semesterTrimester']) == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="semesterTrimester" />
                </flux:field>

                <flux:field>
                    <flux:label for="year">Year</flux:label>
                    <flux:input id="year" type="number" name="year" value="{{ old('year', $formData['year']) }}" required min="{{ date('Y') - 5 }}" max="{{ date('Y') + 5 }}" />
                    <flux:error name="year" />
                </flux:field>
            </div>

            {{-- SECTION B: MISSED EXAM DETAILS --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4 mt-8">SECTION B: MISSED EXAM DETAILS</h3>

            <div class="p-4 border border-gray-200 dark:border-zinc-700 rounded-md space-y-4">
                <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300">Course #1</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label for="courseCode_1">Course Code</flux:label>
                        <flux:select id="courseCode_1" name="courseCode_1" required>
                            <option value="">Select a Course</option>
                            @foreach($completedCourseCodes as $code)
                                <option value="{{ $code }}" {{ old('courseCode_1', $formData['courseCode_1']) == $code ? 'selected' : '' }}>{{ $code }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="courseCode_1" />
                    </flux:field>

                    <flux:field>
                        <flux:label for="examDate_1">Exam Date</flux:label>
                        <flux:input id="examDate_1" type="date" name="examDate_1" value="{{ old('examDate_1', $formData['examDate_1']) }}" required />
                        <flux:error name="examDate_1" />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label for="examStartTime_1">Exam Start Time</flux:label>
                        <flux:input id="examStartTime_1" type="time" name="examStartTime_1" value="{{ old('examStartTime_1', $formData['examStartTime_1']) }}" required />
                        <flux:error name="examStartTime_1" />
                    </flux:field>

                    <flux:field>
                        <flux:label for="applyingFor_1">Applying For</flux:label>
                        <flux:select id="applyingFor_1" name="applyingFor_1" required>
                            <option value="">Select</option>
                            @foreach($applyingForOptions as $option)
                                <option value="{{ $option }}" {{ old('applyingFor_1', $formData['applyingFor_1']) == $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="applyingFor_1" />
                    </flux:field>
                </div>
            </div>

            {{-- SECTION C: APPLICATION DETAILS --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4 mt-8">SECTION C: APPLICATION DETAILS</h3>

            <flux:field>
                <flux:label for="reasons">Reason for Application</flux:label>
                <flux:textarea id="reasons" name="reasons" required>{{ old('reasons', $formData['reasons']) }}</flux:textarea>
                <flux:error name="reasons" />
            </flux:field>

            <flux:field>
                <flux:label for="supportingDocuments">Attach Supporting Documents</flux:label>
                <input type="file" name="supportingDocuments[]" id="supportingDocuments" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 dark:file:bg-zinc-700 dark:file:text-teal-400 dark:text-gray-300">
                <flux:description>Upload up to 7 files. Max 3MB each.</flux:description>
                <flux:error name="supportingDocuments" />
                <flux:error name="supportingDocuments.*" />
            </flux:field>

            {{-- DECLARATION --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4 mt-8">DECLARATION</h3>

            <flux:field variant="inline">
    {{-- Temporarily replaced flux:checkbox with standard HTML input for debugging --}}
    <input type="checkbox"
           id="declarationAgreed"
           name="declarationAgreed"
           value="1"
           {{ old('declarationAgreed', $formData['declarationAgreed']) ? 'checked' : '' }}
           class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
           required />
    <flux:label for="declarationAgreed">
        I declare the information provided is true and I accept the University's policy on special passes.
    </flux:label>
    <flux:error name="declarationAgreed" />
</flux:field>

            <div class="flex justify-end mt-6">
                <flux:button type="submit" variant="primary">Submit Application</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
