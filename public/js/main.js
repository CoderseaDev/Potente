const users=document.getElementById('users');
   if(users) {
    function deleteUser(e) {
        if (confirm('are you sure?')) {
            fetch(`/user/delete/${e.target.getAttribute('data-id')}`, {method: 'DELETE'})
            .then(res => window.location.reload());
        }
    }
}
