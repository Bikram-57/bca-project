@foreach ($facultyDropdown as $facultyId => $facultyData)
    <tr>
        <td>{{ $facultyData['name'] }}</td>
        <td>
            <select name="courses[{{ $facultyId }}]" class="form-select">
                @foreach ($facultyData['subjects'] as $subject)
                    <option value="{{ $subject['cid'] }}">
                        {{ $subject['year'] }} - {{ $subject['name'] }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <button class="btn btn-primary editAssignedSubject" type="button" data-faculty-id="{{ $facultyId }}"
                data-bs-toggle="modal" data-bs-target="#assignEditModal">
                <i class="bi bi-pencil-fill"></i>
            </button>
        </td>
    </tr>
@endforeach
