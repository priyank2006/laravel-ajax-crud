@extends('layouts.app')

@section('title', 'Users CRUD')

@section('main')
    <div class="row my-1">
        <div class="col-4"></div>
        <div class="col-4">
            <div class="appendErrors" id='appendErrors'>

            </div>
            <form id="userForm" id="userForm" novalidate method="POST">
                @csrf
                <input hidden type="text" name="userID" class="userID">
                <div class="row my-1">
                    <div class="col">
                        <label for="" class="form-label"> Name <span class="fw-bolder text-danger">*</span> </label>
                        <input required type="text" class="userName form-control" name='userName' placeholder="A-Z">
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col">
                        <label for="" class="form-label"> Email <span class="fw-bolder text-danger">*</span>
                        </label>
                        <input required type="email" class="userEmail form-control" name='userEmail'
                            placeholder="Valid Email">
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col">
                        <label for="" class="form-label"> Phone No <span class="fw-bolder text-danger">*</span>
                        </label>
                        <input min="1111111111" minlength="10" maxlength="10" required type="number"
                            class="userPhoneNo form-control" name='userPhoneNo' placeholder="0-9">
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col">
                        <label for="" class="form-label"> Gender <span class="fw-bolder text-danger">*</span>
                        </label>
                        <div class="row my-1">
                            <div class="col">
                                <input required type="radio" class="form-check-input" name='userGender' value="Male">
                                Male
                                <input required type="radio" class="form-check-input" name='userGender' value="Female">
                                Female
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row my-1">
                    <div class="col">
                        <label for="" class="form-label"> Education <span class="fw-bolder text-danger">*</span>
                        </label>
                        <select multiple name="userEducation[]" class="userEducation form-select">
                            <option value="Primary School"> Primary School </option>
                            <option value="Secondary School"> Secondary School </option>
                            <option value="Higher Secondary School"> Higher Secondary School </option>
                            <option value="Bachelors"> Bachelors </option>
                            <option value="Masters"> Masters </option>
                            <option value="Others"> Others </option>
                        </select>
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col">
                        <label for="" class="form-label"> Hobby <span class="fw-bolder text-danger">*</span>
                        </label>
                        <div class="row my-1">
                            <div class="col">
                                <input required type="checkbox" class="form-check-input" name='userHobby[]' value="Cricker">
                                Cricker
                                <input required type="checkbox" class="form-check-input" name='userHobby[]' value="Singing">
                                Singing
                                <input required type="checkbox" class="form-check-input" name='userHobby[]'
                                    value="Travelling"> Travelling
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row my-1">
                    <div class="col">
                        <label for="" class="form-label"> Experiences <span class="fw-bolder text-danger">*</span>
                        </label>
                        <div class="row my-1 toCopy">
                            <div class="col">
                                <input type="text" class="form-control" name="userExperience[]">
                            </div>
                            <div class="col">
                                <button class="addExp btn btn-primary btn-sm"> Add </button>
                                <button class="remExp btn btn-primary btn-sm"> Remove </button>
                            </div>
                        </div>
                        <div class="toAppend">

                        </div>

                    </div>
                </div>
                <div class="row my-1">
                    <div class="col">
                        <label for="" class="form-label"> Picture <span class="fw-bolder text-danger">*</span>
                        </label>
                        <div class="row my-1">
                            <div class="col">
                                <input accept="image/jpg,image/jfif,image/png,image,jpeg" type="file"
                                    onchange="loadFile(event)" name="userPicture" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col">
                        <label for="" class="form-label"> Message <span class="fw-bolder text-danger">*</span>
                        </label>
                        <div class="row my-1">
                            <div class="col">
                                <textarea name="userMessage" id="" cols="30" rows="3" class="userMessage form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success submit"> Submit </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-4">
            <div class="row mt-5">
                <div class="col">
                    <img width="200px" height="200px" id="target" src="" alt="" class="preview">
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        {{-- <div class="col-4"></div> --}}
        <div class="row my-1">
            <div class="col-3">
                <input class="form-control" id="searchInput" placeholder="Search..." />
            </div>
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th> SR. NO. </th>
                            <th> Name </th>
                            <th> Hobby </th>
                            <th> Email </th>
                            <th> Picture </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody class="customDataTable">

                    </tbody>
                </table>
            </div>
            <div class="row my-2 text-center">
                <div class="col text-center">
                    <button id="show-more" class="btn btn-primary btn-md"> Show More </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var currentPage = 1;

            var searchTerm = ""; // Initialize the search term


            function appendRecord(item) {
                srNo++;
                var recordHtml = `<tr class="text-center">
                    <td>` + srNo + `</td>
                    <td>` + item.userName + `</td>
                    <td>` + item.userHobby + `</td>
                    <td>` + item.userEmail + `</td>
                    <td> <img width='100px' src="/user_images/` + item.userPicture + `"> </td>
                    <td> <button class="editUser" value='` + item.userID +
                    `'> Edit </button> | <a class='deleteUser' href="/deleteUser/` +
                    item.userID + `">Delete</a></td>
                    </tr>`;
                $(".customDataTable").append(recordHtml);
            }

            function getData() {
                $('#show-more').attr('disabled', 'disabled');
                $.ajax({
                    url: '/getUserData/' + currentPage,
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        search: searchTerm
                    },
                    success: function(response) {
                        srNo = 0;
                        var data = response.data;
                        if (data.length > 0) {
                            $(".customDataTable").html("");
                            currentPage++;
                        } else {
                            alert("No More Records !!");
                        }
                        data.forEach(function(item) {
                            appendRecord(item);
                        });

                        $("#show-more").removeAttr('disabled');
                    }
                });
            }


            getData();
            $('#show-more').on('click', function() {
                getData();
            });

            function searchAndLoadData() {
                // Reset current page and search for the new term
                currentPage = 1;
                $(".customDataTable").html(""); // Clear existing data
                searchTerm = $("#searchInput").val();
                getData();
            }

            $('#searchInput').on('input', function() {
                searchAndLoadData();
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $(document).on("click", ".deleteUser", function(e) {
                e.preventDefault(); // Preventiing Refdirect of User Without Permission

                var href = $(this).attr('href'); // Getting HREF URL of Deleting

                if (confirm("Are You Sure to Delete Record ?") ==
                    true) { // Confirming User to Delete or Not
                    $.ajax({
                        url: href,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}" // Pass the CSRF token as part of the data
                        },
                        success: function(info) {
                            alert("Deletion Successful !");
                            getData();
                        }
                    });
                }
                // User Will Not Get Deleted If Cancelled Confirmation
                return false;
            });

            $(document).on("submit", "#userForm", function(e) {
                e.preventDefault(); // Preventing Server-Side Form Submit



                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Initiating AJAX Form Submit
                $.ajax({
                    url: "/api/createUser",
                    type: "POST",
                    data: new FormData(userForm),
                    contentType: false,
                    processData: false,
                    success: function(info) {
                        if (info.status == 0) {
                            $(".appendErrors").html("");
                            $.each(info.errors, function(key, item) {
                                $(".appendErrors").addClass("alert alert-danger");
                                $(".appendErrors").append("<ul>");
                                $(".appendErrors").append("<li>" + item[0] + "</li>");
                                $(".appendErrors").append("</ul>");
                            });


                        } else {
                            $(".appendErrors").removeClass("alert alert-danger");
                            $(".appendErrors").html("Successful !!");
                            $(".appendErrors").addClass("alert alert-success");

                            $("form").trigger("reset");
                        }
                        location.href = "#appendErrors";
                        getData();
                    },
                });
            });
        });

        $(document).on("click", ".addExp", function(e) {
            e.preventDefault();

            $cloneData = $(this).closest(".row").clone();

            $(".toAppend").append($cloneData);

        });

        $(document).on("click", ".remExp", function(e) {
            e.preventDefault();

            $cloneCount = $("input[name='userExperience[]']").length;
            if ($cloneCount > 1) {
                $cloneData = $(this).closest(".row").remove();
            } else {
                alert("Its Compulsary To Fill 1 Experience");
            }


        });
        $(document).on("click", ".editUser", function(e) {
            e.preventDefault();

            var val = $(this).val();
            $.ajax({
                url: '/api/userData/' + val,
                type: "GET",
                success: function(info) {
                    $.each(info.data, function(key, item) {
                        $("img").attr("src", "/user_images/" + item.userPicture);
                        $.each(item.education, function(key2, item2) {
                            $("select").find("option[value='" + item2.userEducation +
                                "']").prop("selected", "selected");
                        });
                        $(".toCopy").html("");
                        $.each(item.experience, function(key4, item4) {

                            $(".toAppend").append(`
                            <div class="row my-1 toCopy">
                            <div class="col">
                                <input type="text" class="form-control" name="userExperience[]" value="` + item4
                                .userExperience + `">
                            </div>
                            <div class="col">
                                <button class="addExp btn btn-primary btn-sm"> Add </button>
                                <button class="remExp btn btn-primary btn-sm"> Remove </button>
                            </div>
                        </div>`);
                        });
                        $.each(item, function(key2, item2) {

                            // Entering Values in All Input Fields

                            $("." + key2).val(item2);

                            $('input[type="radio"][value="' + item2 + '"]').prop(
                                "checked", true);

                            inputString = item
                                .userHobby; // Splitting Comma Seperated Hobbies in Array
                            var hobbiesArray = inputString.split(',');

                            $.each(hobbiesArray, function(key3, item3) {
                                // Checking Checkboxes With Matching Values
                                $('.form-check-input[value="' + item3 + '"]')
                                    .prop("checked", true);
                            });

                        });
                    });
                }
            });
        });
    </script>
    <script>
        var loadFile = function(event) {
            var output = document.getElementById('target');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
            location.href = "#target";
        };
    </script>
@endsection
