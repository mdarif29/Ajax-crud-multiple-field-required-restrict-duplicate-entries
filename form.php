<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX CRUD Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Records</h2>
    <form id="employeeForm">
        <input type="hidden" name="id" id="id">
        <input type="text" name="firstname" id="firstname" placeholder="First Name" >
        <input type="text" name="lastname" id="lastname" placeholder="Last Name" >
        <input type="email" name="email" id="email" placeholder="Email" >
        <input type="text" name="emp_id" id="emp_id" placeholder="Employee ID" >
        <button type="submit">Save</button>
        <div class="error" id="formError"></div>
    </form>

    <table id="employeeTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Employee ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be fetched by AJAX -->
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $('#employeeTable').DataTable();

            function fetchEmployees() {
                $.ajax({
                    url: 'operation.php',
                    type: 'POST',
                    data: { action: 'read' },
                    dataType: 'json',
                    success: function(response) {
                        table.clear().draw();
                        response.forEach(function(employee) {
                            table.row.add([
                                employee.id,
                                employee.firstname,
                                employee.lastname,
                                employee.email,
                                employee.emp_id,
                                `<button onclick="editEmployee(${employee.id}, '${employee.firstname}', '${employee.lastname}', '${employee.email}', '${employee.emp_id}')">Edit</button>
                                 <button onclick="deleteEmployee(${employee.id})">Delete</button>`
                            ]).draw(false);
                        });
                    }
                });
            }

            function validateForm() {
                let firstname = $('#firstname').val().trim();
                let lastname = $('#lastname').val().trim();
                let email = $('#email').val().trim();
                let emp_id = $('#emp_id').val().trim();
                let isValid = true;
                let errorMsg = '';

                if (!firstname || !lastname || !email || !emp_id) {
                    errorMsg = 'All fields are required.';
                    isValid = false;
                } else if (!validateEmail(email)) {
                    errorMsg = 'Invalid email format.';
                    isValid = false;
                }

                $('#formError').text(errorMsg);
                return isValid;
            }

            function validateEmail(email) {
                const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                return re.test(String(email).toLowerCase());
            }

            $('#employeeForm').on('submit', function(e) {
                e.preventDefault();
                if (validateForm()) {
                    let formData = $(this).serialize();
                    $.ajax({
                        url: 'operation.php',
                        type: 'POST',
                        data: formData + '&action=' + ($('#id').val() ? 'update' : 'create'),
                        success: function(response) {
                            if (response === 'duplicate') {
                                $('#formError').text('Duplicate Employee ID is not allowed.');
                            } else {
                                alert(response);
                                fetchEmployees();
                                $('#employeeForm')[0].reset();
                                $('#id').val('');
                                $('#formError').text('');
                            }
                        }
                    });
                }
            });

            window.editEmployee = function(id, firstname, lastname, email, emp_id) {
                $('#id').val(id);
                $('#firstname').val(firstname);
                $('#lastname').val(lastname);
                $('#email').val(email);
                $('#emp_id').val(emp_id);
            };

            window.deleteEmployee = function(id) {
                if (confirm('Are you sure to delete this record?')) {
                    $.ajax({
                        url: 'operation.php',
                        type: 'POST',
                        data: { id: id, action: 'delete' },
                        success: function(response) {
                            alert(response);
                            fetchEmployees();
                        }
                    });
                }
            };

            fetchEmployees();
        });
    </script>
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
