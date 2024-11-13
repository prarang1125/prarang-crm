document.addEventListener("DOMContentLoaded", function() {
    CKEDITOR.replace('editor', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
});

// post analytics maker total data from viewership to user inpute
function calculateTotal() {
    var fromDate = document.getElementById('postViewershipFrom').value;
    var toDate = document.getElementById('to').value;

    if (fromDate && toDate) {
        // Convert to Date objects
        var from = new Date(fromDate);
        var to = new Date(toDate);

        // Calculate the difference in days
        var timeDifference = to - from;
        var daysDifference = timeDifference / (1000 * 3600 * 24); // Convert from milliseconds to days

        // Display the result in the Total field
        if (daysDifference >= 0) {
            document.getElementById('total').value = daysDifference;
        } else {
            alert('The "To" date cannot be earlier than the "From" date.');
            document.getElementById('total').value = ''; // Clear total if invalid
        }
    }
}
