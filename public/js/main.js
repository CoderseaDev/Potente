const users = document.getElementById('users');
if (users) {
    function deleteUser(e) {
        if (confirm('are you sure?')) {
            fetch(`/user/delete/${e.target.getAttribute('data-id')}`, {method: 'DELETE'})
                .then(res => window.location.reload());
        }
    }
}

$( document ).ready( function(){
    $( "#imageUpladForm" ).submit( function( e ) {
        e.preventDefault();
        var data = new FormData();
        var files = $('[type="file"]').get(0).files;
        // Add the uploaded image content to the form data collection
        if (files.length > 0) {
            data.append("image_upload", files[0]);
        }
        // the actual request to your newAction
        $.ajax({
            url: '/saveProfileImage',
            type: 'POST',
            dataType: 'script',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                var url = JSON.parse(data).url;
                $('#image_upload_file').attr('src', window.location.origin + url);
            }
        });
    } );
} );

function uploadImage() {
    $("#imageUpladForm").submit();

}



