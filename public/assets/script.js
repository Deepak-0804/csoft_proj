

document.addEventListener('DOMContentLoaded', () => {

    // For Public App
    //about.php 
    //products.php 
    // For Admin App

    // for logout popup
    const logoutContainer = document.querySelector('.logout-container');
    const logoutPopup = document.querySelector('.logout-popup');

    if (logoutContainer && logoutPopup) {
        logoutContainer.addEventListener('click', () => {
            logoutPopup.style.display =
                logoutPopup.style.display === 'block' ? 'none' : 'block';
        });

        window.addEventListener('click', (e) => {
            if (!logoutContainer.contains(e.target)) {
                logoutPopup.style.display = 'none';
            }
        });
    }



    // for Role creation 
    const createBtn = document.getElementById('createRoleBtn');
    const modal = document.getElementById('roleModal');
    const cancelBtn = document.getElementById('cancelRole');

    if (createBtn && modal && cancelBtn) {
        createBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        cancelBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }


    const saveBtn = document.getElementById('saveRole');
    if (saveBtn && modal) {

        saveBtn.addEventListener('click', () => {
            const roleName = document.getElementById('roleName').value.trim();
            const csrf = document.getElementById('csrf').value.trim();

            if (roleName === '') {
                alert('Enter a role name');
                return;
            }

            debugger;

            console.log('Sending role:', roleName, 'CSRF:', csrf);

            fetch('index.php?page=saverole&area=admin', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'roleName=' + encodeURIComponent(roleName) + '&csrf=' + encodeURIComponent(csrf)
            })

                .then(res => res.text())
                .then(data => {
                    alert('Role saved!');
                    modal.style.display = 'none';
                    location.reload(); // refresh to see new role
                });
        });


    }
    // for Role deletion 
    // --- Role Deletion (Admin only) ---
    const deleteModal = document.getElementById('deleteModal');
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    const confirmDeleteBtn = document.getElementById('confirmDelete');

    if (deleteModal && cancelDeleteBtn && confirmDeleteBtn) {

        let selectedRoleId = null;

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                selectedRoleId = btn.dataset.roleid;
                const roleName = btn.dataset.rolename;

                const msgEl = document.getElementById('deleteMessage');
                if (msgEl) {
                    msgEl.textContent = `Are you sure you want to remove the role "${roleName}"?`;
                }

                deleteModal.style.display = 'flex';
            });
        });

        // Cancel
        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.style.display = 'none';
        });

        // Confirm delete
        confirmDeleteBtn.addEventListener('click', () => {
            if (!selectedRoleId) return;

            const csrf = document.getElementById('csrf').value;

            fetch('index.php?page=deleterole&area=admin', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'roleId=' + encodeURIComponent(selectedRoleId) +
                    '&csrf=' + encodeURIComponent(csrf)
            })
                .then(res => res.text())
                .then(data => {
                    alert('Role Deleted!');
                    deleteModal.style.display = 'none';
                    location.reload();
                });
        });
    }

});
