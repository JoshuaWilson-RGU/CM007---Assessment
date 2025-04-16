var editUserModal = document.getElementById('editUserModal');
editUserModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var userId = button.getAttribute('data-user-id');
    var name = button.getAttribute('data-name');
    var email = button.getAttribute('data-email');
    var role = button.getAttribute('data-role');
    var modal = this;
    modal.querySelector('#edit_user_id').value = userId;
    modal.querySelector('#edit_name').value = name;
    modal.querySelector('#edit_email').value = email;
    modal.querySelector('#edit_role').value = role;
});