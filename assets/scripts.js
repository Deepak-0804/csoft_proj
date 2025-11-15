 // For Public App



  const swiper = new Swiper('.swiper', {
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: 'auto',
    loop: true,
    speed: 800,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    coverflowEffect: {
      rotate: 30,
      stretch: 0,
      depth: 100,
      modifier: 1,
      slideShadows: true,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });


  




 // For Admin App
 
 // for logout popup
const logoutContainer = document.querySelector('.logout-container');
const logoutPopup = document.querySelector('.logout-popup');

logoutContainer.addEventListener('click', () => {
  logoutPopup.style.display = logoutPopup.style.display === 'block' ? 'none' : 'block';
});
window.addEventListener('click', (e) => {
  if (!logoutContainer.contains(e.target)) {
    logoutPopup.style.display = 'none';
  }
});


// for Role creation 
const createBtn = document.getElementById('createRoleBtn');
const modal = document.getElementById('roleModal');
const cancelBtn = document.getElementById('cancelRole');

createBtn.addEventListener('click', () => {
    modal.style.display = 'flex'; // show modal
});

cancelBtn.addEventListener('click', () => {
    modal.style.display = 'none'; // hide modal
});


const saveBtn = document.getElementById('saveRole');

saveBtn.addEventListener('click', () => {
    const roleName = document.getElementById('roleName').value.trim();
    const csrf = document.getElementById('csrf').value.trim();

    if(roleName === '') {
        alert('Enter a role name');
        return;
    }
    
      debugger;  

    console.log('Sending role:', roleName, 'CSRF:', csrf);

    fetch('index.php?page=saverole&area=admin', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'roleName=' + encodeURIComponent(roleName) + '&csrf=' + encodeURIComponent(csrf)
})

    .then(res => res.text())
    .then(data => {
        alert('Role saved!');
        modal.style.display = 'none';
        location.reload(); // refresh to see new role
    });
});


// for Role deletion 
const deleteModal = document.getElementById('deleteModal');

let selectedRoleId = null;

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        selectedRoleId = btn.dataset.roleid;
        const roleName = btn.dataset.rolename;
        document.getElementById('deleteMessage').textContent = 
            `Are you sure you want to remove the role "${roleName}"?`;
        deleteModal.style.display = 'flex';
    });
});

// Cancel button
document.getElementById('cancelDelete').addEventListener('click', () => {
    deleteModal.style.display = 'none';
});



confirmDelete.addEventListener('click', () => {
    if (!selectedRoleId) return;

    const csrf = document.getElementById('csrf').value; // reuse CSRF token


    fetch('index.php?page=deleterole&area=admin', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'roleId=' + encodeURIComponent(selectedRoleId) + '&csrf=' + encodeURIComponent(csrf)
    })
    .then(res => res.text())
    .then(data => {
        alert('Role Deleted!'); // or use a nicer toast message
        deleteModal.style.display = 'none';
        location.reload(); // refresh table
    });
});


