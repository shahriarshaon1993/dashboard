function deleteData(id) {
    console.log(id);
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("delete-form-" + id).submit();
        }
    });
}

function restoreData(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to restore this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, restore it!",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("restore-form-" + id).submit();
        }
    });
}

function permanentData(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to permanently delete this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, permanently delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("permanent-form-" + id).submit();
        }
    });
}