const articles=document.getElementById('articles');
   if(articles) {
    function deleteuser(e) {
        if (confirm('are you sure?')) {
            fetch(`/user/delete/${e.target.getAttribute('data-id')}`, {method: 'DELETE'})
            .then(res => window.location.reload());
        }
    }
}
