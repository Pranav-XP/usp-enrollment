<x-layouts.app :title="__('Application for Completion of Programme')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Application for Completion of Programme</flux:heading>
        <flux:subheading>
            Please complete this form to apply for your graduation. Ensure all details are accurate.
            <br>
            <span class="text-red-500">Note: Your application will be deemed unsuccessful if outstanding issues (e.g., unpaid fees, active holds, unsubmitted thesis) are not sorted before the ceremony.</span>
        </flux:subheading>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('graduation.store') }}" method="POST" class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md space-y-6">
            @csrf

            {{-- SECTION A: PERSONAL DETAILS --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4">SECTION A: PERSONAL DETAILS</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Student ID Number --}}
                <flux:field>
                    <flux:label for="studentIdNumber">Student ID Number</flux:label>
                    <flux:input id="studentIdNumber" type="text" name="studentIdNumber" value="{{ old('studentIdNumber', $formData['studentIdNumber']) }}" required readonly />
                    <flux:error name="studentIdNumber" />
                </flux:field>

                {{-- Name --}}
                <flux:field>
                    <flux:label for="name">Full Name</flux:label>
                    <flux:input id="name" type="text" name="name" value="{{ old('name', $formData['name']) }}" required readonly />
                    <flux:error name="name" />
                </flux:field>
            </div>

            {{-- Postal Address --}}
            <flux:field>
                <flux:label for="postalAddress">Postal Address</flux:label>
                <flux:input id="postalAddress" type="text" name="postalAddress" value="{{ old('postalAddress', $formData['postalAddress']) }}" required readonly />
                <flux:error name="postalAddress" />
            </flux:field>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Date of Birth --}}
                <flux:field>
                    <flux:label for="dateOfBirth">Date of Birth</flux:label>
                    <flux:input id="dateOfBirth" type="date" name="dateOfBirth" value="{{ old('dateOfBirth', $formData['dateOfBirth']) }}" required readonly />
                    <flux:error name="dateOfBirth" />
                </flux:field>

                {{-- Telephone --}}
                <flux:field>
                    <flux:label for="telephone">Telephone</flux:label>
                    <flux:input id="telephone" type="tel" name="telephone" value="{{ old('telephone', $formData['telephone']) }}" required readonly />
                    <flux:error name="telephone" />
                </flux:field>
            </div>

            {{-- Email --}}
            <flux:field>
                <flux:label for="email">Email</flux:label>
                <flux:input id="email" type="email" name="email" value="{{ old('email', $formData['email']) }}" required readonly />
                <flux:error name="email" />
            </flux:field>

            {{-- SECTION B: GRADUATION PROGRAMME DETAILS --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4 mt-8">SECTION B: GRADUATION PROGRAMME DETAILS</h3>

            {{-- Programme Type Radio Group --}}
            <flux:radio.group name="programmeType" label="Programme Type" required>
                <flux:radio value="Undergraduate" label="Undergraduate" :checked="old('programmeType', $formData['programmeType']) == 'Undergraduate'" />
                <flux:radio value="Postgraduate" label="Postgraduate" :checked="old('programmeType', $formData['programmeType']) == 'Postgraduate'" />
                <flux:radio value="Pacific TAFE" label="Pacific TAFE" :checked="old('programmeType', $formData['programmeType']) == 'Pacific TAFE'" />
            </flux:radio.group>
            <flux:error name="programmeType" />


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Programme you are completing? --}}
                <flux:field>
                    <flux:label for="programme">Programme you are completing?</flux:label>
                    <flux:input id="programme" type="text" name="programme" value="{{ old('programme', $formData['programme']) }}" required />
                    <flux:error name="programme" />
                </flux:field>

                {{-- Major 1 --}}
                <flux:field>
                    <flux:label for="major1">Major 1</flux:label>
                    <flux:input id="major1" type="text" name="major1" value="{{ old('major1', $formData['major1']) }}" required />
                    <flux:error name="major1" />
                </flux:field>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Major 2 (Optional) --}}
                <flux:field>
                    <flux:label for="major2">Major 2 (Optional)</flux:label>
                    <flux:input id="major2" type="text" name="major2" value="{{ old('major2', $formData['major2']) }}" />
                    <flux:error name="major2" />
                </flux:field>

                {{-- Minor (Optional) --}}
                <flux:field>
                    <flux:label for="minor">Minor (Optional)</flux:label>
                    <flux:input id="minor" type="text" name="minor" value="{{ old('minor', $formData['minor']) }}" />
                    <flux:error name="minor" />
                </flux:field>
            </div>

            {{-- SECTION C: GRADUATION ATTENDANCE --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4 mt-8">SECTION C: GRADUATION ATTENDANCE</h3>

            <flux:field>
                <flux:label for="graduationCeremonyVenue">Please select only ONE graduation ceremony:</flux:label>
                <flux:select id="graduationCeremonyVenue" name="graduationCeremonyVenue" required>
                    <option value="">-- Select Venue --</option>
                    <option value="Laucala" {{ old('graduationCeremonyVenue', $formData['graduationCeremonyVenue']) == 'Laucala' ? 'selected' : '' }}>Laucala: 4th and 5th September 2025</option>
                    <option value="Solomon Islands" {{ old('graduationCeremonyVenue', $formData['graduationCeremonyVenue']) == 'Solomon Islands' ? 'selected' : '' }}>Solomon Islands: 26th September 2025</option>
                    <option value="Tonga" {{ old('graduationCeremonyVenue', $formData['graduationCeremonyVenue']) == 'Tonga' ? 'selected' : '' }}>Tonga: 10th October 2025</option>
                </flux:select>
                <flux:error name="graduationCeremonyVenue" />
            </flux:field>

            {{-- Will you attend the graduation ceremony? Radio Group --}}
            <flux:radio.group name="willAttendGraduation" label="Will you attend the graduation ceremony?" required>
                <flux:radio value="1" label="I will attend" :checked="old('willAttendGraduation', $formData['willAttendGraduation']) === true" />
                <flux:radio value="0" label="I will NOT attend" :checked="old('willAttendGraduation', $formData['willAttendGraduation']) === false" />
            </flux:radio.group>
            <flux:error name="willAttendGraduation" />


            {{-- SECTION D: DECLARATION --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4 mt-8">SECTION D: DECLARATION</h3>

              {{-- Declaration Checkbox --}}
            <flux:field variant="inline">
                <flux:checkbox id="declarationAgreed" name="declarationAgreed" value="1" :checked="old('declarationAgreed', $formData['declarationAgreed'])" required />

                <flux:label for="declarationAgreed">
                    I certify that the particulars in this form are correct, I have read the notes above, and I will abide by the rules set out in the Statutes, Ordinances Regulations and the Charter of the University of the South Pacific. I have read the above information.
                </flux:label>

                <flux:error name="declarationAgreed" />
            </flux:field>

            <div class="flex justify-end mt-6">
                <flux:button type="submit" variant="primary">Submit Application</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>