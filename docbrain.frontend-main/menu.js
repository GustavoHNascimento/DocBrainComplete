
//Seleciona os itens clicado
var menuItem = document.querySelectorAll('.item-menu')

function selectLink(){
    menuItem.forEach((item)=>
        item.classList.remove('ativo')
    )
    this.classList.add('ativo')
}

menuItem.forEach((item)=>
    item.addEventListener('click', selectLink)
)

//Expandir o menu

var btnExp = document.querySelector('#btn-exp')
var menuSide = document.querySelector('.menu-lateral')

btnExp.addEventListener('click', function(){
    menuSide.classList.toggle('expandir')
})

window.addEventListener('load', () => {
    //...
})

// Selecionar Arquivos

function openFileInput() {
    document.getElementById('file-input').click();
}

function handleFiles(files) {
    // Processar os arquivos aqui e preencher a tabela
    const tableBody = document.getElementById('file-table-body');

    for (const file of files) {
        const row = tableBody.insertRow();
        const fileNameCell = row.insertCell(0);
        const fileTypeCell = row.insertCell(1);
        const statusCell = row.insertCell(2);
        const actionCell = row.insertCell(3);

        fileNameCell.textContent = file.name;
        fileTypeCell.textContent = file.type;
        statusCell.textContent = 'Pendente';
        actionCell.innerHTML = '<button onclick="reprocessFile(this)">Reprocessar</button>';
    }
}

function dropHandler(event) {
    event.preventDefault();
    const files = event.dataTransfer.files;
    handleFiles(files);
}

function dragOverHandler(event) {
    event.preventDefault();
}

function reprocessFile(button) {
    const row = button.closest('tr');
    const statusCell = row.cells[2];
    statusCell.textContent = 'Concluído';
}

// Chave de API do OpenAI
const apiKey = 'sua_api_key'

function sendMessage(){
    var message = document.getElementById('message-input')
    if(!message.value)
    {
        message.style.border = '1px solid red'
        return;
    }
    message.style.border = 'none'

    var status = document.getElementById('status')
    var btnSubmit = document.getElementById('btn-submit')

    status.style.display = 'block'
    status.innerHTML = 'Carregando...'
    btnSubmit.disabled = true
    btnSubmit.style.cursor = 'not-allowed'
    message.disabled = true

    fetch("https://api.openai.com/v1/completions",{
        method: 'POST',
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            Authorization: `Bearer ${apiKey}`,
        },
        body: JSON.stringify({
            model: "text-davinci-003",
            prompt: message.value,
            max_tokens: 2048, // tamanho da resposta
            temperature: 0.5 // criatividade na resposta
        })
    })
    .then((response) => response.json())
    .then((response) => {
        let r = response.choices[0]['text']
        status.style.display = 'none'
        showHistory(message.value,r)
    })
    .catch((e) => {
        console.log(`Error -> ${e}`)
        status.innerHTML = 'Erro, tente novamente mais tarde...'
    })
    .finally(() => {
        btnSubmit.disabled = false
        btnSubmit.style.cursor = 'pointer'
        message.disabled = false
        message.value = ''
    })
}

function showHistory(message,response){
    var historyBox = document.getElementById('history')

    // My message
    var boxMyMessage = document.createElement('div')
    boxMyMessage.className = 'box-my-message'

    var myMessage = document.createElement('p')
    myMessage.className = 'my-message'
    myMessage.innerHTML = message

    boxMyMessage.appendChild(myMessage)

    historyBox.appendChild(boxMyMessage)

    // Response message
    var boxResponseMessage = document.createElement('div')
    boxResponseMessage.className = 'box-response-message'

    var chatResponse = document.createElement('p')
    chatResponse.className = 'response-message'
    chatResponse.innerHTML = response

    boxResponseMessage.appendChild(chatResponse)

    historyBox.appendChild(boxResponseMessage)

    // Levar scroll para o final
    historyBox.scrollTop = historyBox.scrollHeight
}
    //Alterar Pagina
    function showPage(pageId) {
        // Oculta todas as páginas
        document.getElementById('dashboardPage').style.display = 'none';
        document.getElementById('bibliotecaPage').style.display = 'none';
        document.getElementById('chatPage').style.display = 'none';

        // Exibe a página correspondente ao item clicado
        document.getElementById(`${pageId}Page`).style.display = 'block';
    }

    function uploadFiles() {
        let form = document.getElementById('uploadForm');
        let formData = new FormData(form);

        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Lidar com a resposta do servidor aqui
            console.log(data);
        })
        .catch(error => {
            // Lidar com erros aqui
            console.error('Erro:', error);
        });
    }


 