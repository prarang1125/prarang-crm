document.addEventListener("DOMContentLoaded", function() {
    CKEDITOR.replace('editor', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
});

// total post analytics maker data
function calculateTotal() {
    const fields = ['citySubscribers', 'prarangApplication', 'facebookLinkClick', 'websiteGd', 'email', 'instagram'];
    let total = 0;

    fields.forEach(field => {
        const value = parseFloat(document.getElementById(field).value) || 0;
        total += value;
    });

    document.getElementById('total').value = total;
}
