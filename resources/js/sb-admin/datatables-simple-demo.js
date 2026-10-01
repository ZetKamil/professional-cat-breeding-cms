window.addEventListener('DOMContentLoaded', event => {
    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple && typeof simpleDatatables !== 'undefined') {
        new simpleDatatables.DataTable(datatablesSimple);
    }
});
