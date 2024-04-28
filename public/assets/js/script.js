//thid is for select option

(() => {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)
    })
})()

function getFacultyInfo(fid, callback) {
    $.ajax({
        url: '/admin/getFacultyInfo/' + fid,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            callback(response[0]);
            // console.log(response);
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

$(document).ready(function () {
    $(document).on('click', '.facultyViewButton', function () {
        var fid = $(this).closest('tr').find('.facultyId').text();
        getFacultyInfo(fid, function (response) {
            $('#faculty-view-id').text(response.regno);
            $('#faculty-view-email').text(response.email);
            $('#faculty-view-name').text(response.name);
            $('#facultyViewModal').modal('show');
        });
    });

    $(document).on('click', '#editFacultyBtn', function () {
        var fid = $(this).closest('tr').find('.facultyId').text();

        getFacultyInfo(fid, function (response) {
            $('#faculty-edit-id').val(response.regno);
            $('#faculty-edit-email').val(response.email);
            $('#faculty-edit-name').val(response.name);
            $('#facultyEditModal').modal('show');
        });
    });
    $(document).on('click', '#deleteFacultyBtn', function () {
        $('#delete-faculty-id').val(($(this).data('faculty-id')));
    });

    $(document).on('input', '#searchInput', function () {
        const searchQuery = this.value.trim();
        // Send AJAX request to the server
        fetch(`/course/search?query=${encodeURIComponent(searchQuery)}`)
            .then(response => response.json())
            .then(data => {
                // Update table rows with filtered data
                const tableBody = document.querySelector('#table tbody');
                tableBody.innerHTML = '';


                if (data.length === 0) {
                    // If no results found, display a message
                    tableBody.innerHTML = `
                    <tr>
                    <td colspan="3" class="text-center text-muted">No results found</td>
                    </tr>
                    `;
                }
                else {
                    if ($('#searchInput').val() == '') {
                        $('#pagination').css('display', 'block');
                    } else {
                        $('#pagination').css('display', 'none');
                    }
                    data.forEach(course => {
                        tableBody.innerHTML += `
                    <tr>
                        <td class="courseId">${course.cid}</td>
                        <td>${course.cname}</td>
                        <td>${course.course}</td>
                        <td>
                            <div class="more-btn">
                                <button class="dropdown" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bi bi-three-dots fs-4"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button class="dropdown-item editButton" type="button" data-bs-toggle="modal"
                                            data-bs-target="#editSubjectModal">Edit</button>
                                    </li >
                                    <li>
                                        <button class="dropdown-item deleteBtn" type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteSubjectModal" data-course-id=${course.cid}>Delete</button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `;
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    // faculty search ajax

    $('#facultySearch').on('input', function () {
        var searchString = $(this).val();
        // alert(searchString);
        $.ajax({
            type: 'GET',
            url: '/admin/get-faculty-info/',
            data: {
                searchData: searchString,
            },
            success: function (response) {
                $('.facultyFilter').html(response);
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });

    $('#co_po_search').on('input', function () {
        var searchString = $(this).val();
        // alert(searchString);
        $.ajax({
            type: 'GET',
            url: '/admin/get-co_po_relation/',
            data: {
                searchData: searchString,
            },
            success: function (response) {
                $('.co_po_filter').html(response);
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });

    // edit subject modal ajax
    // $('.editButton').click(function () {
    $(document).on('click', '.editButton', function () {
        var cid = $(this).closest('tr').find('.courseId').text();
        $.ajax({
            url: '/admin/getCourseInfo/' + cid,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                console.log(response[0]);
                $('#edit-modal-subject-code').val(response[0].cid);
                $('#edit-modal-subject-name').val(response[0].cname);
                $('#edit-modal-subject-course').val(response[0].course);
                $('#editSubjectModal').modal('show');
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    $(document).ready(function () {
        $('#course').change(function () {
            var selectedCourse = $(this).val();
            var subjectDropdown = $('#subjectId');

            // Clear existing options in subject dropdown
            subjectDropdown.html('<option value="" selected disabled class="text-dark">Select subject code</option>');

            // Fetch subjects based on the selected course using AJAX
            $.ajax({
                url: '/admin/getSubjects/' + selectedCourse,
                type: 'GET',
                dataType: 'json',
                success: function (subjects) {
                    console.log(subjects);
                    // Populate subjects in the subject dropdown
                    $.each(subjects, function (key, value) {
                        subjectDropdown.append($('<option>', {
                            value: value,
                            text: value + ' - ' + key
                        }));
                    });

                    // Refresh the selectpicker after updating options
                    subjectDropdown.selectpicker('refresh');
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching subjects:', error);
                }
            });
        });
    });
});


// javaScript for number restiction start

function restrictAlphabets(event) {
    let x = event.which || event.keyCode;
    if ((x >= 48 && x <= 57) || x === 8) {
        return true;
    } else {
        event.preventDefault();
        return false;
    }
}
// javaScript for number restiction end

// javaScript for Alphabet restiction start

function restrictInput(event) {
    // Get the key code of the pressed key
    let keyCode = event.keyCode || event.which;
    // Allow only alphanumeric characters
    let allowedChars = /[1-3]/;
    let keyChar = String.fromCharCode(keyCode);
    // Check if the input length is already 1
    if (event.target.value.length >= 1 || !allowedChars.test(keyChar)) {
        // If the input length is already 1 or the pressed key is not alphanumeric, prevent the default action (typing)
        event.preventDefault();
        return false;
    }
}

// javaScript for Alphabet restiction end

// javaScript for Sidebar Toggle start

// const sidebarToggle = document.querySelector("#sidebar-toggle");
// sidebarToggle.addEventListener("click",function(){
//   document.querySelector("#sidebar").classList.toggle("collapsed");
// })
// javaScript for Sidebar Toggle start


// Chart Script for MCA/Bca Report Start
function generateYears(count) {
    const currentYear = new Date().getFullYear();
    const years = [];
    for (let i = 0; i < count; i++) {
        years.push(currentYear - count + 1 + i);
    }
    return years;
}

// Generate labels for 5 years
const labels = generateYears(5);

// Data configuration
const data = {
    labels: labels,
    datasets: [{
        label: 'Subject Report',
        data: [1.5, 2.1, 1.8, 2.1, 1.9 /* Add your data for 5 years here */],
        fill: false,
        borderColor: '#355389',
        tension: 0.2
    }]
};
const config = {
    type: 'line',
    data: data,
};

const ctx = document.getElementById('myChart').getContext('2d');

// Create the chart
const myChart = new Chart(ctx, {
    type: 'line',
    data: data,
    options: {
        scales: {
            y: {
                min: 1,
                max: 3,
                ticks: {
                    stepSize: 0.1
                }
            }
        }
    }
});

// Chart Script for MCA/Bca Report End
