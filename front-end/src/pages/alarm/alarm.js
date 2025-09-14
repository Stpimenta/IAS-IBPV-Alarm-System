


/* modal */

const btnHistory = document.getElementById('btn-history');
const modal = document.getElementById('modal-history');
const closeBtn = document.querySelector('.modal-close');
const historyList = modal.querySelector('.history-list');

btnHistory.addEventListener('click', () => {
    modal.style.display = 'flex'; 
    loadHistory(); 
});

closeBtn.addEventListener('click', () => {
    modal.style.display = 'none'; 
});


window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});


//get history
function loadHistory() {

    historyList.innerHTML = '';

    fetch('../../services/logs/logs_getlogs.php')
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                historyList.innerHTML = `<li>${data.error}</li>`;
                return;
            }

            // percorre do último para o primeiro
            for (let i = data.length - 1; i >= 0; i--) {
                const log = data[i];
                const li = document.createElement('li');
                li.textContent = `Alarme ${log.event === 'on' ? 'ligado' : 'desligado'} por ${log.user} - ${log.time}`;
                historyList.appendChild(li);
            }
        })
        .catch(err => {
            console.error('Erro ao carregar logs:', err);
            historyList.innerHTML = `<li>Erro ao carregar histórico</li>`;
        });
}


/*Drop Down*/

const userIcon = document.getElementById('div-header-user-icon');
const dropdown = document.getElementById('user-dropdown');

userIcon.addEventListener('click', (e) => {
    e.stopPropagation(); // evita que o clique feche imediatamente
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
});

window.addEventListener('click', () => {
    dropdown.style.display = 'none';
});
