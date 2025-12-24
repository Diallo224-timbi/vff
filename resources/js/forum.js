let newThreadModal = document.getElementById('newThreadModal');
let allThreadsModal = document.getElementById('allThreadsModal');
let threadForm = document.getElementById('threadForm');

function openNewThreadModal() {
    newThreadModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeNewThreadModal() {
    newThreadModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    threadForm.reset();
}

function openAllThreadsModal() {
    allThreadsModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeAllThreadsModal() {
    allThreadsModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Fermer modals overlay
[newThreadModal, allThreadsModal].forEach(modal => {
    modal.addEventListener('click', e => {
        if(e.target === modal) modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        if(modal === newThreadModal) threadForm.reset();
    });
});

// ESC
document.addEventListener('keydown', function(event){
    if(event.key === 'Escape'){
        if(!newThreadModal.classList.contains('hidden')) closeNewThreadModal();
        if(!allThreadsModal.classList.contains('hidden')) closeAllThreadsModal();
    }
});

// Validation formulaire
threadForm.addEventListener('submit', function(e){
    const title = document.getElementById('title').value.trim();
    const body = document.getElementById('body').value.trim();
    const category = document.getElementById('category_id').value;
    if(!title || !body || !category){
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }
});
